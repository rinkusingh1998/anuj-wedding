<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true).' Decoration';

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'short_name' => Str::title($name),
            'tagline' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'is_visible' => true,
            'priority' => 50,
            'sort_order' => 0,
        ];
    }

    /**
     * Website par na dikhne wali service (uske saare city pages 404 ho jate hain).
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => false,
        ]);
    }
}
