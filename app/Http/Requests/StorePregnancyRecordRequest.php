<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePregnancyRecordRequest extends FormRequest
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
            'visit_date' => 'required|date',
            'pregnancy_order' => 'required|integer',
            'gestational_age' => 'required|string',
            'weight' => 'required|string',
            'arm_circumference' => 'required|numeric',
            'blood_pressure' => 'required|string',
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
            'visit_date.required' => 'Visit Date is required.',
            'visit_date.date' => 'Visit Date must be a valid date.',
            'pregnancy_order.required' => 'Pregnancy Order is required.',
            'pregnancy_order.integer' => 'Pregnancy Order must be an integer.',
            'gestational_age.required' => 'Gestational Age is required.',
            'gestational_age.string' => 'Gestational Age must be a string.',
            'weight.required' => 'Weight is required.',
            'weight.string' => 'Weight must be a string.',
            'arm_circumference.required' => 'Arm Circumference is required.',
            'arm_circumference.numeric' => 'Arm Circumference must be a number.',
            'blood_pressure.required' => 'Blood Pressure is required.',
            'blood_pressure.string' => 'Blood Pressure must be a string.',
        ];
    }
}
