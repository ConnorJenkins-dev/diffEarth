<?php

namespace Database\Seeders;

use App\Models\Datapoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatapointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($row = 1; $row <= 5; $row++) {
            for ($col = 1; $col <= 5; $col++) {
                Datapoint::factory()->create([
                    'row_id'    => $row,
                    'column_id' => $col,
                ]);
            }
        }
    }
}
