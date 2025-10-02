<?php

namespace Database\Factories;

use App\Models\Bukti;
use App\Models\Temuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bukti>
 */
class BuktiFactory extends Factory
{
    protected $model = Bukti::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'temuan_id' => Temuan::factory(),
            'nama_dokumen' => $this->faker->sentence(3),
            'file_path' => 'public/bukti_pendukung/' . $this->faker->word . '.pdf',
            'pengguna_unggah_id' => User::factory(),
            'status' => $this->faker->randomElement(['menunggu verifikasi', 'terverifikasi', 'revisi']),
            'verified_by_user_id' => null,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the bukti is pending verification.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'menunggu verifikasi',
            'verified_by_user_id' => null,
        ]);
    }

    /**
     * Indicate that the bukti is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'terverifikasi',
            'verified_by_user_id' => User::factory(),
        ]);
    }

    /**
     * Indicate that the bukti needs revision.
     */
    public function needsRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'revisi',
            'verified_by_user_id' => User::factory(),
        ]);
    }
}
