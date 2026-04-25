<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_-]+$/',
            ],
            'password' => [
                'nullable',
                'string',
                'max:255',
            ],
            'selected_role' => [
                'nullable',
                'string',
                'in:administrator,lectureTeacher,homeroomTeacher,student',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.string' => 'User ID must be a valid text.',
            'user_id.max' => 'User ID cannot exceed 255 characters.',
            'user_id.regex' => 'User ID can only contain letters, numbers, hyphens, and underscores.',
            'password.string' => 'Password must be a valid text.',
            'password.max' => 'Password cannot exceed 255 characters.',
            'selected_role.string' => 'Selected role must be valid text.',
            'selected_role.in' => 'Please select a valid role.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if user has multiple roles and selected_role is required
            $user = \App\Models\User::where('user_id', $this->user_id)->first();
            
            if ($user && count($user->getRoles()) > 1 && empty($this->selected_role)) {
                $validator->errors()->add('selected_role', 'Please select a role to login as.');
            }
            
            // Check if selected role is valid for the user
            if ($user && $this->selected_role && !$user->hasRole($this->selected_role)) {
                $validator->errors()->add('selected_role', 'You do not have permission for the selected role.');
            }
        });
    }
}