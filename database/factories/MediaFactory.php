<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipe = fake()->randomElement(['Anime', 'Manga', 'Drama']);
        return [
            'judul' => fake()->unique()->sentence(3),
            'tipe' => $tipe,
            'genre' => fake()->randomElement(['Action', 'Adventure', 'Drama', 'Fantasy', 'Romance', 'Comedy']),
            'rating' => fake()->randomFloat(1, 6.0, 9.9),
            'tahun' => fake()->numberBetween(1990, 2026),
            'status' => fake()->randomElement(['Selesai', 'Publishing', 'Sedang Tayang']),
            'episode' => $tipe !== 'Manga' ? fake()->numberBetween(12, 100) : null,
            'chapter' => $tipe === 'Manga' ? fake()->numberBetween(50, 1000) : null,
            'studio' => $tipe === 'Anime' ? fake()->company() : null,
            'penulis' => $tipe === 'Manga' ? fake()->name() : null,
            'network' => $tipe === 'Drama' ? fake()->randomElement(['tvN', 'JTBC', 'Netflix', 'KBS']) : null,
            'deskripsi' => fake()->paragraph(),
        ];
    }
}
