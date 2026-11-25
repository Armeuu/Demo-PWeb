<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMediaToCollectionRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh pengguna beserta jumlah koleksinya.
     */
    public function index(): AnonymousResourceCollection
    {
        $users = User::withCount('media')->latest()->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Membuat pengguna baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama pengguna wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        $validated['password'] = Hash::make($validated['password'] ?? 'password123');

        $user = User::create($validated);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan profil pengguna beserta seluruh daftar koleksi medianya (eager loading).
     */
    public function show(User $user): UserResource
    {
        $user->load('media');

        return new UserResource($user);
    }

    /**
     * Menampilkan daftar media yang ada di koleksi pengguna.
     */
    public function getCollection(User $user): AnonymousResourceCollection
    {
        return MediaResource::collection($user->media);
    }

    /**
     * Custom Action: Menambahkan media ke koleksi pribadi pengguna (attach).
     * Menerapkan DB::transaction dan pengecekan duplikasi (409 Conflict) serta penanganan race condition.
     */
    public function addToCollection(AddMediaToCollectionRequest $request, User $user): JsonResponse
    {
        return DB::transaction(function () use ($request, $user) {
            $mediaId = (int) $request->validated('media_id');
            $status = $request->validated('status') ?? 'Sedang Ditonton';
            $catatan = $request->validated('catatan');

            // 1. Kunci baris user untuk konsistensi data dan cegah race condition
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            // 2. Cek apakah media sudah ada di dalam koleksi pengguna (mencegah duplikasi - status 409)
            if ($lockedUser->media()->where('media_id', $mediaId)->exists()) {
                return response()->json([
                    'message' => 'Media sudah ada di dalam koleksi pengguna'
                ], 409);
            }

            // 3. Tambahkan ke pivot table
            $lockedUser->media()->attach($mediaId, [
                'status' => $status,
                'catatan' => $catatan,
            ]);

            $lockedUser->load('media');

            return response()->json([
                'message' => 'Media berhasil ditambahkan ke dalam koleksi',
                'data' => new UserResource($lockedUser)
            ], 201);
        });
    }

    /**
     * Custom Action: Memperbarui status media di koleksi pengguna (misal dari "Sedang Ditonton" ke "Selesai").
     */
    public function updateCollectionStatus(Request $request, User $user, int $mediaId): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|max:50',
            'catatan' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Status koleksi wajib diisi',
        ]);

        if (!$user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'message' => 'Media tidak ditemukan di dalam koleksi pengguna'
            ], 404);
        }

        $user->media()->updateExistingPivot($mediaId, array_filter([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]));

        return response()->json([
            'message' => 'Status koleksi media berhasil diperbarui'
        ], 200);
    }

    /**
     * Custom Action: Menghapus media dari koleksi pengguna (detach).
     */
    public function removeFromCollection(User $user, int $mediaId): JsonResponse
    {
        return DB::transaction(function () use ($user, $mediaId) {
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            if (!$lockedUser->media()->where('media_id', $mediaId)->exists()) {
                return response()->json([
                    'message' => 'Media tidak ditemukan di dalam koleksi pengguna'
                ], 404);
            }

            $lockedUser->media()->detach($mediaId);

            return response()->json([
                'message' => 'Media berhasil dihapus dari koleksi pengguna'
            ], 200);
        });
    }
}
