<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Row;
use App\Models\Column;
use App\Models\Dataset;
use App\Models\Datapoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessCsvJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public string $filePath;
    public string $originalName;
    public int $locationId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $originalName, int $locationId)
    {
        $this->filePath = $filePath;
        $this->originalName = $originalName;
        $this->locationId = $locationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fullPath = public_path($this->filePath);
        $handle = fopen($fullPath, 'r');
        if ($handle === false) {
            return;
        }

        // Use transaction so nothing gets saved unless everything succeeds
        DB::beginTransaction();
        try {
            // Create dataset
            $dataset = Dataset::create(
                [
                    'dataset_name' => $this->originalName, // Includes timestamp to delineate
                    'location_id' => $this->locationId, // Adds locationId
                ],
            );

            // Create columns
            $columnNames = fgetcsv($handle);
            if (!$columnNames || count($columnNames) === 0) {
                fclose($handle);
                throw new \Exception('Column names must be in first row');
            }

            $columns = [];
            $timestampColumnIndex = null;
            foreach ($columnNames as $index => $name) {
                $cleanName = trim($name);
                if (strtolower($cleanName) === 'timestamp') {
                    $timestampColumnIndex = $index;
                    continue; // Ignore timestamp column
                }
                $column = Column::create(
                    [
                        'column_name' => $cleanName,
                        'dataset_id' => $dataset->id
                    ],
                );
                $columns[$index] = $column;
            }

            if (is_null($timestampColumnIndex)) {
                fclose($handle);
                throw new \Exception('CSV file must contain a timestamp column in first row.');
            }

            // Create rows
            while (($rowData = fgetcsv($handle)) !== false) {
                if (!count($rowData)) {
                    continue; // Ignore empty row
                }
                // If the "timestamp" field is literally the string "timestamp",
                // Skip it; it's probably a repeated header row
                if (strtolower(trim($rowData[$timestampColumnIndex])) === 'timestamp') {
                    continue;
                }
                // Process timestamp
                $rowTimestamp = trim($rowData[$timestampColumnIndex]) ?? null;
                if (is_null($rowTimestamp)) {
                    fclose($handle);
                    throw new \Exception('One or more rows do not have a timestamp.');
                }

                $row = Row::create([
                    'dataset_id' => $dataset->id,
                    'timestamp' => Carbon::parse($rowTimestamp)->toDateTimeString(),
                ]);

                // Create datapoints
                foreach ($rowData as $index => $value) {
                    if ($index === $timestampColumnIndex) {
                        continue;
                    }
                    if (!isset($columns[$index])) {
                        continue;
                    }
                    Datapoint::create([
                        'row_id' => $row->id,
                        'column_id' => $columns[$index]->id,
                        'data' => json_encode($value),
                    ]);
                }
            }

            fclose($handle);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            Log::info($e->getMessage());
            $this->fail($e->getMessage());
        } finally {
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
