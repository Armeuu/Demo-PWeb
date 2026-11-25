<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_get_user_with_koleksi_media(): void
    {
        $user = User::first();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'koleksi_media',
                ]
            ]);
    }

    public function test_can_add_media_to_collection_happy_path(): void
    {
        $user = User::first();
        // Cari media yang belum ada di koleksi user (Frieren)
        $media = Media::where('judul', "Frieren: Beyond Journey's End")->first();

        $response = $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => $media->id,
            'status' => 'Sedang Ditonton',
            'catatan' => 'Episode 10',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Media berhasil ditambahkan ke dalam koleksi');

        $this->assertDatabaseHas('media_user', [
            'user_id' => $user->id,
            'media_id' => $media->id,
            'status' => 'Sedang Ditonton',
        ]);
    }

    public function test_cannot_add_duplicate_media_to_collection_returns_409(): void
    {
        $user = User::first();
        // Attack on Titan sudah ada di koleksi user dari seeder
        $media = Media::where('judul', 'Attack on Titan')->first();

        $response = $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => $media->id,
            'status' => 'Sedang Ditonton',
        ]);

        $response->assertStatus(409)
            ->assertJson(['message' => 'Media sudah ada di dalam koleksi pengguna']);
    }

    public function test_cannot_add_nonexistent_media_returns_422(): void
    {
        $user = User::first();

        $response = $this->postJson("/api/users/{$user->id}/collection", [
            'media_id' => 9999, // tidak ada di database
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['media_id']);
    }

    public function test_can_update_collection_status(): void
    {
        $user = User::first();
        $media = Media::where('judul', 'Attack on Titan')->first();

        $response = $this->patchJson("/api/users/{$user->id}/collection/{$media->id}", [
            'status' => 'Selesai Ditonton',
            'catatan' => 'Rating 10/10',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Status koleksi media berhasil diperbarui']);

        $this->assertDatabaseHas('media_user', [
            'user_id' => $user->id,
            'media_id' => $media->id,
            'status' => 'Selesai Ditonton',
        ]);
    }

    public function test_can_remove_media_from_collection(): void
    {
        $user = User::first();
        $media = Media::where('judul', 'Attack on Titan')->first();

        $response = $this->deleteJson("/api/users/{$user->id}/collection/{$media->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Media berhasil dihapus dari koleksi pengguna']);

        $this->assertDatabaseMissing('media_user', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }

    public function test_cannot_remove_uncollected_media_returns_404(): void
    {
        $user = User::first();
        $media = Media::where('judul', "Frieren: Beyond Journey's End")->first();

        $response = $this->deleteJson("/api/users/{$user->id}/collection/{$media->id}");

        $response->assertStatus(404)
            ->assertJson(['message' => 'Media tidak ditemukan di dalam koleksi pengguna']);
    }
}
