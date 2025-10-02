<?php

namespace Database\Factories;

use App\Models\Temuan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Temuan>
 */
class TemuanFactory extends Factory
{
    protected $model = Temuan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_temuan' => 'TM-' . $this->faker->unique()->numberBetween(100000, 999999),
            'ringkasan' => $this->faker->paragraph(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the temuan is open.
     */
    public function withCode(string $code): static
    {
        return $this->state(fn (array $attributes) => [
            'kode_temuan' => $code,
        ]);
    }

    /**
     * Indicate that the temuan is in progress.
     */
    public function withRingkasan(string $ringkasan): static
    {
        return $this->state(fn (array $attributes) => [
            'ringkasan' => $ringkasan,
        ]);
    }

    /**
     * Indicate that the temuan is closed.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => now()->subDays(rand(1, 30)),
            'updated_at' => now(),
        ]);
    }
}
