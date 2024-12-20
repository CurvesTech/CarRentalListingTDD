<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Maker;
use App\Models\Listing;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{

    public function configure() : static
    {
        return $this->afterCreating(function (Listing $listing) {
            $listing->images()->createMany([
                ['path' => 'http://placehold.it/500x500']
            ]);
        });
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'user_id' => User::factory(),
            'maker_id' => Maker::factory(),
            'model_id' => CarModel::factory(),
            'year' => $this->faker->year,
            'registration_number' => $this->faker->word,
            'transmission' => $this->faker->randomElement(['manual', 'automatic']),
            'price_per_day' => $this->faker->numberBetween(1000, 10000),
            'phone_number' => $this->faker->phoneNumber
        ];
    }
}
