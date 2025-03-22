<?php

namespace Tests\Feature;

use App\Models\Column;
use App\Models\Datapoint;
use App\Models\Dataset;
use App\Models\Row;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GraphDataAPITest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testItReturnsAllDatasets(): void
    {
        $dataset = Dataset::factory()->create(
            [
                'dataset_name' => 'Test_Dataset.csv',
            ]
        );
        $column = Column::factory()->create(
            [
                'dataset_id' => $dataset->id,
                'column_name' => 'temperature',
            ],
        );
        $column = Column::factory()->create(
            [
                'dataset_id' => $dataset->id,
                'column_name' => 'pressure',
            ],
        );
        $row = Row::factory()->create([
            'dataset_id' => $dataset->id,
            'timestamp' => '2024-10-18 00:48:12',
            ]);

        $datapoint = Datapoint::factory()->create([
            'row_id' => $row->id,
            'column_id' => $column->id,
            'data' => json_encode(35),
        ]);

        // test datasets.index returns datasets
        $response = $this->get(route('datasets.index'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'dataset_name' => 'Test_Dataset.csv',
            ]);

        // test columns.index returns columns for a dataset
        $response2 = $this->get(route('columns.index', ['datasetId' => $dataset->id]));

        $response2 ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $column->id,
                'column_name' => 'temperature',
            ])
            ->assertJsonFragment([
                'id' => $column->id,
                'column_name' => 'pressure',
            ]);

        // test datapoints.index returns datapoints for a column
        $response3 = $this->get(route('datapoints.index', ['columnId' => $column->id]));

        $response3 ->assertStatus(200)
            ->assertJsonFragment([
                'column_id' => $column->id,
                'data' => '35',
            ]);
    }
}
