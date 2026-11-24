<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        $name = Str::title($name);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'meta_title' => $name.' | '.config('app.name'),
            'meta_description' => fake()->sentence(18),
            'is_visible' => true,
            'priority' => 50,
            'sort_order' => 0,
        ];
    }

    /**
     * Website par na dikhne wala (noindex) page.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => false,
        ]);
    }
}
