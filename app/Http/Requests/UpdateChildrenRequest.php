<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildrenRequest extends FormRequest
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
            'identity_number' => 'nullable|string|size:16|unique:childrens,identity_number,' . $this->route('children')?->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'birth_weight' => 'required|string|max:255',
            'birth_height' => 'required|string|max:255',
            'bpjs_facility' => 'nullable|in:Klinik,Puskesmas,RS',
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
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field may not be greater than 255 characters.',
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'The gender field must be male or female.',
            'birth_place.required' => 'The birth place field is required.',
            'birth_place.string' => 'The birth place field must be a string.',
            'birth_place.max' => 'The birth place field may not be greater than 255 characters.',
            'birth_date.required' => 'The birth date field is required.',
            'birth_weight.required' => 'The birth weight field is required.',
            'birth_height.required' => 'The birth height field is required.',
        ];
    }
}
