<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');
        $mediaId = $this->route('media')?->id ?? $this->route('id');

        return [
            'judul' => $isPatch
                ? "sometimes|string|max:255|unique:media,judul,{$mediaId}"
                : 'required|string|max:255|unique:media,judul',
            'tipe' => $isPatch
                ? 'sometimes|string|in:Anime,Manga,Drama'
                : 'required|string|in:Anime,Manga,Drama',
            'genre' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:10',
            'tahun' => $isPatch
                ? 'sometimes|integer|min:1900|max:2100'
                : 'required|integer|min:1900|max:2100',
            'status' => 'nullable|string|max:50',
            'episode' => 'nullable|integer|min:1',
            'chapter' => 'nullable|integer|min:1',
            'studio' => 'nullable|string|max:150',
            'penulis' => 'nullable|string|max:150',
            'network' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul media wajib diisi',
            'judul.unique' => 'Judul media sudah ada di katalog',
            'tipe.required' => 'Tipe media wajib diisi',
            'tipe.in' => 'Tipe media harus berupa Anime, Manga, atau Drama',
            'tahun.required' => 'Tahun rilis wajib diisi',
            'tahun.integer' => 'Tahun harus berupa bilangan bulat',
            'rating.numeric' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal adalah 0',
            'rating.max' => 'Rating maksimal adalah 10',
        ];
    }
}
