<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIlpScreeningRequest extends FormRequest
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
            'subjectable_type' => ['required', 'string', Rule::in(['App\Models\Mother', 'App\Models\Children', 'App\Models\Elderly'])],
            'subjectable_id' => 'required|integer',
            'staff_id' => 'required|exists:staff,id',
            'checkup_date' => 'required|date',
            'results' => 'nullable|array',
            'results.weight' => 'nullable|numeric',
            'results.height' => 'nullable|numeric',
            'results.waist_circumference' => 'nullable|numeric',
            'results.blood_pressure' => 'nullable|string',
            'results.blood_sugar' => 'nullable|numeric',
            'results.uric_acid' => 'nullable|numeric',
            'results.cholesterol' => 'nullable|numeric',
            'results.eyes' => 'nullable|string',
            'results.ears' => 'nullable|string',
            'results.note' => 'nullable|string',
        ];
    }
}
