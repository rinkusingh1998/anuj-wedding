<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'state_id' => State::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => 'city',
            'district' => fake()->city(),
            'pincode' => (string) fake()->numberBetween(200000, 299999),
            'is_visible' => true,
            'is_featured' => false,
            'priority' => 50,
            'sort_order' => 0,
        ];
    }

    /**
     * Website par na dikhne wala shehar (page 404 ho jata hai).
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
            'priority' => 95,
        ]);
    }

    public function village(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'village',
        ]);
    }
}
