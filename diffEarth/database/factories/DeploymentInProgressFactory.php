<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeploymentInProgress>
 */
class DeploymentInProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'info' => $this->faker->paragraphs(3, true),
            'layout' => json_encode([
                    [
                        'x' => 0,
                        'y' => 0,
                        'w' => 2,
                        'h' => 3,
                        'i' => 'graph_seeded_0',
                        'itemData' => [
                            [
                                'columnId' => 1,
                                'traceName' => $this->faker->name(),
                            ],
                            [
                                'columnId' => 2,
                                'traceName' => $this->faker->name(),
                            ]
                        ]
                    ],
                    [
                        'x' => 0,
                        'y' => 3,
                        'w' => 2,
                        'h' => 3,
                        'i' => 'graph_seeded_1',
                        'itemData' => [
                            [
                                'columnId' => 1,
                                'traceName' => $this->faker->name(),
                            ]
                        ]
                    ],
                    [
                        'x' => 2,
                        'y' => 0,
                        'w' => 2,
                        'h' => 3,
                        'i' => 'table_seeded_2',
                        'itemData' => [
                            [
                                'datasetIds' => [1, 2],
                            ]
                        ]
                    ],
            ]),
        ];
    }
}
