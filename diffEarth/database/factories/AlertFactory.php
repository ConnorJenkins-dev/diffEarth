<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Alert;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Alert::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location' => $this->faker->city(),
            'column' => $this->faker->word(),
            'threshold' => $this->faker->numberBetween(1, 100),
            'emaillist' => json_encode([$this->faker->safeEmail(), $this->faker->safeEmail()]),
        ];
    }
}
