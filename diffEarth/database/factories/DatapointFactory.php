<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Datapoint>
 */
class DatapointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'data' => json_encode([
                'value' => $this->faker->randomFloat(2, 0, 100),
                'unit' => $this->faker->word(),
            ])
        ];
    }
}
