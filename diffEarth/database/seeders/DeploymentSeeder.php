<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deployment;

class DeploymentSeeder extends Seeder
{
    public function run()
    {

        // Define a list of sample deployments
        $deployments = [
            [
                'uid' => 'cf240001',
                'name' => 'Greenland Deployment',
                'latitude' => 72.575,
                'longitude' => -38.46,
                'description' => 'Sensor deployed near Greenland.',
            ],
            [
                'uid' => 'cf240002',
                'name' => 'Antarctica Deployment',
                'latitude' => -75.0,
                'longitude' => 165.0,
                'description' => 'Sensor deployed near Antarctica.',
            ],
            [
                'uid' => 'cf240003',
                'name' => 'Arctic Circle Deployment',
                'latitude' => 66.56,
                'longitude' => -135.0,
                'description' => 'Sensor deployed near the Arctic Circle.',
            ],
        ];

        // Insert sample deployments into the database
        foreach ($deployments as $deployment) {
            Deployment::create($deployment);
        }
    }
}
