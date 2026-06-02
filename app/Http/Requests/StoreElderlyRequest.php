<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreElderlyRequest extends FormRequest
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
            'identity_number' => 'required|string|unique:elderlies,identity_number',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'social_security_number' => 'nullable|string|max:50',
            'health_facility' => 'nullable|in:Klinik,Puskesmas,RS',
            'blood_type' => 'nullable|string|max:5',
        ];
    }
}
