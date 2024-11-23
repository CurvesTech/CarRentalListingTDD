<?php

namespace Database\Factories;

use App\Enums\CarShape;
use App\Models\Maker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'maker_id' => Maker::factory(),
            'title' => $this->faker->name,
            'shape' => $this->faker->randomElement(CarShape::values()),
        ];
    }
}
