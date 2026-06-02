<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGrowthMonitoringRequest extends FormRequest
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
            'child_id' => 'required|exists:childrens,id',
            'staff_id' => 'required|exists:staff,id',
            'checkup_date' => 'required|date',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'arm_circumference' => 'nullable|numeric',
            'head_circumference' => 'nullable|numeric',
            'z_score' => 'nullable|numeric',
            'status' => 'nullable',
            'next_checkup_date' => 'nullable|date|after:checkup_date',
            'note' => 'nullable|string',
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
            'child_id.required' => 'Child ID is required.',
            'child_id.exists' => 'Child ID does not exist.',
            'staff_id.required' => 'Staff ID is required.',
            'staff_id.exists' => 'Staff ID does not exist.',
            'checkup_date.required' => 'Checkup date is required.',
            'checkup_date.date' => 'Checkup date must be a valid date.',
            'weight.required' => 'Weight is required.',
            'weight.numeric' => 'Weight must be a number.',
            'height.required' => 'Height is required.',
            'height.numeric' => 'Height must be a number.',
            'arm_circumference.numeric' => 'Arm circumference must be a number.',
            'head_circumference.numeric' => 'Head circumference must be a number.',
            'z_score.required' => 'Z score is required.',
            'z_score.numeric' => 'Z score must be a number.',
            'status.required' => 'Status is required.',
            'next_checkup_date.date' => 'Jadwal pemeriksaan berikutnya harus berupa tanggal yang valid.',
            'next_checkup_date.after' => 'Jadwal pemeriksaan berikutnya harus setelah tanggal pemeriksaan hari ini.',
            'note.string' => 'Note must be a string.',
        ];
    }
}
