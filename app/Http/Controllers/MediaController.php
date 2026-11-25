<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Mendukung query filter search (?search=) dan tipe (?tipe=), serta pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $keyword = $request->query('search') ?? $request->query('judul');
        $tipe = $request->query('tipe');
        $perPage = (int) $request->query('per_page', 10);

        $media = Media::query()
            ->search($keyword)
            ->tipe($tipe)
            ->with('users')
            ->latest()
            ->paginate($perPage);

        return MediaResource::collection($media);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request): JsonResponse
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     * Menggunakan Route Model Binding & eager loading relasi users.
     */
    public function show(Media $media): MediaResource
    {
        $media->load('users');

        return new MediaResource($media);
    }

    /**
     * Update the specified resource in storage.
     * Menggunakan Form Request Validation dengan aturan dinamis PATCH/PUT.
     */
    public function update(StoreMediaRequest $request, Media $media): MediaResource
    {
        $media->update($request->validated());

        return new MediaResource($media);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json([
            'message' => 'Media berhasil dihapus dari sistem'
        ], 200);
    }
}
