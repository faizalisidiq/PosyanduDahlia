<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthPostRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama Posyandu wajib diisi.',
            'name.string' => 'Nama Posyandu harus berupa string.',
            'name.max' => 'Nama Posyandu maksimal 255 karakter.',
            'address.string' => 'Alamat harus berupa string.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'phone.string' => 'Nomor telepon harus berupa string.',
            'phone.max' => 'Nomor telepon maksimal 255 karakter.',
        ];
    }
}
