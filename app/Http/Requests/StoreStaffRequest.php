<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'health_post_id' => 'required|exists:health_posts,id',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|string|max:255',
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
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not be greater than 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
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
            'role.max' => 'The role must not be greater than 255 characters.',
        ];
    }
}
