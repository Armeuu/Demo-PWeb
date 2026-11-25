<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMediaToCollectionRequest extends FormRequest
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
        return [
            'media_id' => 'required|integer|exists:media,id',
            'status' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'media_id.required' => 'ID media wajib disertakan',
            'media_id.integer' => 'ID media harus berupa bilangan bulat',
            'media_id.exists' => 'Media tidak ditemukan di dalam katalog',
        ];
    }
}
