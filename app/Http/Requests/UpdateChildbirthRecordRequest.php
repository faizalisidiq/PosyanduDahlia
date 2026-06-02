<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildbirthRecordRequest extends FormRequest
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
            'mother_id' => 'required|exists:mothers,id',
            'staff_id' => 'required|exists:staff,id',
            'delivery_date' => 'required|date',
            'delivery_location' => 'required|string',
            'baby_condition' => 'required|string',
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
            'mother_id.required' => 'Mother ID is required.',
            'mother_id.exists' => 'Mother ID does not exist.',
            'staff_id.required' => 'Staff ID is required.',
            'staff_id.exists' => 'Staff ID does not exist.',
            'delivery_date.required' => 'Delivery date is required.',
            'delivery_date.date' => 'Delivery date must be a valid date.',
            'delivery_location.required' => 'Delivery location is required.',
            'delivery_location.string' => 'Delivery location must be a string.',
            'baby_condition.required' => 'Baby condition is required.',
            'baby_condition.string' => 'Baby condition must be a string.',
        ];
    }
}
