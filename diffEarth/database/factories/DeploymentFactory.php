<?php

namespace Database\Factories;

use App\Models\Deployment;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeploymentFactory extends Factory
{
    protected $model = Deployment::class;

    public function definition()
    {
        return [
            'uid' => $this->faker->uuid,
            'name' => $this->faker->word,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'description' => $this->faker->sentence,
        ];
    }
}
