<?php

namespace Database\Seeders;

use App\Models\Column;
use Illuminate\Database\Seeder;

class ColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Column::factory()->count(5)->create([
            'dataset_id' => 1,
        ]);
        Column::factory()->count(5)->create([
            'dataset_id' => 2,
        ]);
    }
}
