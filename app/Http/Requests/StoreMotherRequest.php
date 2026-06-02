<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotherRequest extends FormRequest
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
            'husband_name' => 'nullable|string|max:255',
            'identity_number' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'social_security_number' => 'required|string|max:255',
            'health_facility' => 'required|in:Klinik,Puskesmas,RS',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:today',
            'blood_type' => 'required|string|max:255',
            'height' => 'required|string|max:255',
            'weight' => 'required|string|max:255',
            'status' => 'nullable|in:hamil,menyusui',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama ibu wajib diisi.',
            'name.max' => 'Nama ibu maksimal 255 karakter.',
            'identity_number.required' => 'Nomor identitas ibu wajib diisi.',
            'identity_number.max' => 'Nomor identitas ibu maksimal 255 karakter.',
            'phone_number.required' => 'Nomor telepon ibu wajib diisi.',
            'phone_number.max' => 'Nomor telepon ibu maksimal 255 karakter.',
            'address.required' => 'Alamat ibu wajib diisi.',
            'address.max' => 'Alamat ibu maksimal 255 karakter.',
            'social_security_number.required' => 'Nomor asuransi ibu wajib diisi.',
            'social_security_number.max' => 'Nomor asuransi ibu maksimal 255 karakter.',
            'health_facility.required' => 'Tipe faskes wajib dipilih.',
            'health_facility.in' => 'Tipe faskes harus berupa Klinik, Puskesmas, atau RS.',
            'birth_place.required' => 'Tempat lahir ibu wajib diisi.',
            'birth_place.max' => 'Tempat lahir ibu maksimal 255 karakter.',
            'birth_date.required' => 'Tanggal lahir ibu wajib diisi.',
            'blood_type.required' => 'Golongan darah ibu wajib diisi.',
            'blood_type.max' => 'Golongan darah ibu maksimal 255 karakter.',
            'height.required' => 'Tinggi ibu wajib diisi.',
            'height.max' => 'Tinggi ibu maksimal 255 karakter.',
            'weight.required' => 'Berat ibu wajib diisi.',
            'weight.max' => 'Berat ibu maksimal 255 karakter.',
        ];
    }
}
