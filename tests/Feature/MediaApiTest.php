<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_list_all_media(): void
    {
        $response = $this->getJson('/api/media');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'judul',
                        'tipe',
                        'rating',
                        'tahun',
                        'status',
                        'info_singkat',
                        'created_at',
                    ]
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_filter_media_by_search_keyword(): void
    {
        $response = $this->getJson('/api/media?search=Piece');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('One Piece', $data[0]['judul']);
    }

    public function test_can_filter_media_by_tipe(): void
    {
        $response = $this->getJson('/api/media?tipe=Anime');

        $response->assertStatus(200);
        $data = $response->json('data');
        foreach ($data as $item) {
            $this->assertEquals('Anime', $item['tipe']);
        }
    }

    public function test_can_create_media_happy_path(): void
    {
        $payload = [
            'judul' => 'Steins;Gate',
            'tipe' => 'Anime',
            'genre' => 'Sci-Fi, Thriller',
            'rating' => 9.1,
            'tahun' => 2011,
            'status' => 'Selesai',
            'episode' => 24,
            'studio' => 'White Fox',
            'deskripsi' => 'Rintaro Okabe menemukan cara mengirim pesan ke masa lalu.',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.judul', 'Steins;Gate')
            ->assertJsonPath('data.tipe', 'Anime');

        $this->assertDatabaseHas('media', ['judul' => 'Steins;Gate']);
    }

    public function test_cannot_create_media_with_missing_fields_422(): void
    {
        $response = $this->postJson('/api/media', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'tipe', 'tahun']);
    }

    public function test_cannot_create_media_with_duplicate_title_422(): void
    {
        $payload = [
            'judul' => 'Attack on Titan', // sudah ada di seeder
            'tipe' => 'Anime',
            'tahun' => 2013,
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    public function test_can_show_media_by_id(): void
    {
        $media = Media::where('judul', 'Attack on Titan')->first();

        $response = $this->getJson("/api/media/{$media->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.judul', 'Attack on Titan')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'judul',
                    'pengguna_koleksi',
                ]
            ]);
    }

    public function test_returns_404_when_media_not_found(): void
    {
        $response = $this->getJson('/api/media/9999');

        $response->assertStatus(404);
    }

    public function test_can_update_media_via_patch(): void
    {
        $media = Media::where('judul', 'Attack on Titan')->first();

        $response = $this->patchJson("/api/media/{$media->id}", [
            'rating' => 9.5,
            'status' => 'Masterpiece',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.rating', 9.5)
            ->assertJsonPath('data.status', 'Masterpiece');

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'rating' => 9.5,
            'status' => 'Masterpiece',
        ]);
    }

    public function test_can_delete_media(): void
    {
        $media = Media::create([
            'judul' => 'Media Dihapus',
            'tipe' => 'Anime',
            'tahun' => 2020,
        ]);

        $response = $this->deleteJson("/api/media/{$media->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Media berhasil dihapus dari sistem']);

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }
}
