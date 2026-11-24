<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<State>
 */
class StateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->state();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'region' => fake()->randomElement(['North India', 'Delhi NCR', 'Purvanchal', 'Bundelkhand']),
            'is_visible' => true,
            'is_default' => false,
            'priority' => 50,
            'sort_order' => 0,
        ];
    }

    /**
     * Website par na dikhne wala state.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => false,
        ]);
    }
}
