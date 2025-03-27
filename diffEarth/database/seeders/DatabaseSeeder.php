<?php

namespace Database\Seeders;

use App\Models\Column;
use App\Models\Datapoint;
use App\Models\Dataset;
use App\Models\Row;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Dataset::factory()->count(2)->create();
        Row::factory()->count(5)->create([
            'dataset_id' => 1,
        ]);
        Row::factory()->count(5)->create([
            'dataset_id' => 2,
        ]);
        Column::factory()->count(5)->create([
            'dataset_id' => 1,
        ]);
        Column::factory()->count(5)->create([
            'dataset_id' => 2,
        ]);
        for ($row = 1; $row <= 5; $row++) {
            for ($col = 1; $col <= 5; $col++) {
                Datapoint::factory()->create([
                    'row_id'    => $row,
                    'column_id' => $col,
                ]);
            }
        }
        $this->call([
            RoleSeeder::class,
            TestUserSeeder::class,
            DeploymentSeeder::class,
        ]);
    }
}
