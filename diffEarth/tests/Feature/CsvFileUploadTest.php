<?php

namespace Tests\Feature;

use App\Jobs\ProcessCsvJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CsvFileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $uploadsDir = public_path('uploads');
        if (is_dir($uploadsDir)) {
            foreach (File::files($uploadsDir) as $file) {
                if (strtolower($file->getExtension()) === 'csv' && $file->getFilename() !== '.gitignore') {
                    File::delete($file->getRealPath());
                }
            }
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $uploadsDir = public_path('uploads');
        if (is_dir($uploadsDir)) {
            foreach (File::files($uploadsDir) as $file) {
                if (strtolower($file->getExtension()) === 'csv' && $file->getFilename() !== '.gitignore') {
                    File::delete($file->getRealPath());
                }
            }
        }
    }

    public function testItRunsJobWithCorrectCsv(): void
    {
        Bus::fake();

        $filename = 'testItRunsJobWithCorrectCsv.csv';
        $csvContent = "timestamp,temperature,pressure\n2024-10-18 00:48:12,35,1013";
        $file = UploadedFile::fake()->createWithContent($filename, $csvContent);

        $response = $this->postJson(route('upload.store'), [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'File uploaded successfully and is being processed. ' .
                'Please do not leave this page until it appears in the table.'
            ]);

        Bus::assertDispatched(ProcessCsvJob::class, function (ProcessCsvJob $job) use ($file) {
            return str_contains($job->originalName, $file->getClientOriginalName());
        });

        // Get the actual saved filename with the timestamp prefix
        $uploadedFiles = File::files(public_path('uploads'));
        $expectedFilename = null;

        foreach ($uploadedFiles as $uploadedFile) {
            if (str_ends_with($uploadedFile->getFilename(), $filename)) {
                $expectedFilename = $uploadedFile->getFilename();
                break;
            }
        }

        $this->assertNotNull($expectedFilename, "Uploaded file with expected name not found.");
        $uploadedFilePath = public_path("uploads/{$expectedFilename}");

        // Assert file exists
        $this->assertTrue(
            file_exists($uploadedFilePath),
            "Uploaded file {$expectedFilename} not found in public/uploads."
        );

        // Delete the file after assertion, but only if it exists
        if (file_exists($uploadedFilePath)) {
            File::delete($uploadedFilePath);
            $this->assertFalse(
                file_exists($uploadedFilePath),
                "Uploaded file {$expectedFilename} was not deleted successfully."
            );
        }
    }

    public function testItGives422WithNoFile(): void
    {
        Bus::fake();

        // Send a request without a file
        $response = $this->postJson(route('upload.store'), []);

        // Ensure validation fails
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');

        // Ensure the job was NOT dispatched
        Bus::assertNotDispatched(ProcessCsvJob::class);

        // Ensure no test-generated file exists in public/uploads
        $testFilename = 'testItGives422WithNoFile.csv';
        $uploadedFilePath = public_path("uploads/{$testFilename}");
        $this->assertFalse(file_exists($uploadedFilePath), "Unexpected file {$testFilename} found in public/uploads.");
    }
}
