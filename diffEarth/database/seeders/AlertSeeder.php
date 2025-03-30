<?php

namespace Database\Seeders;

use App\Models\Alert;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 alerts for dataset_id 1
        Alert::factory()->count(5)->create([
            'location' => 'Warehouse A',
        ]);

        // Create 5 alerts for dataset_id 2
        Alert::factory()->count(5)->create([
            'location' => 'Warehouse B',
        ]);
    }
}
