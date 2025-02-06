<?php

namespace Database\Seeders;

use App\Models\Row;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Row::factory()->count(5)->create([
            'dataset_id' => 1,
        ]);
        Row::factory()->count(5)->create([
            'dataset_id' => 2,
        ]);
    }
}
