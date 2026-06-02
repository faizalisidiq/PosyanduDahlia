<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'health_post_id' => 'required|exists:health_posts,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                'max:255', 
                \Illuminate\Validation\Rule::unique('users')->ignore($this->user_id)
            ],
            'password' => 'nullable|string|min:8',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|string|max:255',
            'status' => 'nullable|string|in:pending,active,inactive',
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
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The user ID does not exist.',
            'health_post_id.required' => 'The health post ID is required.',
            'health_post_id.exists' => 'The health post ID does not exist.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address must not be greater than 255 characters.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone must not be greater than 255 characters.',
            'avatar.image' => 'The avatar must be an image.',
            'avatar.mimes' => 'The avatar must be a jpeg, png, jpg, or gif image.',
            'avatar.max' => 'The avatar must not be greater than 2048 kilobytes.',
            'role.required' => 'The role is required.',
            'role.string' => 'The role must be a string.',
        ];
    }
}
