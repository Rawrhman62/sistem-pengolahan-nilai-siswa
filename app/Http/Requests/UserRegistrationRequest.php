<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegistrationRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'user_name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z0-9_-]+$/',
                'unique:users,user_name',
            ],
            'user_id' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z0-9_-]+$/',
                'unique:users,user_id',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/',
            ],
            'role' => [
                'required',
                Rule::in(['administrator', 'lectureTeacher', 'homeroomTeacher', 'student']),
            ],
            'dual_teacher' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.string' => 'Full name must be valid text.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'name.min' => 'Full name must be at least 2 characters.',
            'name.regex' => 'Full name can only contain letters and spaces.',
            
            'user_name.required' => 'Username is required.',
            'user_name.string' => 'Username must be valid text.',
            'user_name.max' => 'Username cannot exceed 255 characters.',
            'user_name.min' => 'Username must be at least 3 characters.',
            'user_name.regex' => 'Username can only contain letters, numbers, hyphens, and underscores.',
            'user_name.unique' => 'This username is already taken.',
            
            'user_id.required' => 'User ID is required.',
            'user_id.string' => 'User ID must be valid text.',
            'user_id.max' => 'User ID cannot exceed 255 characters.',
            'user_id.min' => 'User ID must be at least 3 characters.',
            'user_id.regex' => 'User ID can only contain letters, numbers, hyphens, and underscores.',
            'user_id.unique' => 'This User ID is already taken.',
            
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'email.unique' => 'This email address is already registered.',
            
            'phone_number.string' => 'Phone number must be valid text.',
            'phone_number.max' => 'Phone number cannot exceed 20 characters.',
            'phone_number.regex' => 'Please enter a valid phone number format.',
            
            'role.required' => 'User role is required.',
            'role.in' => 'Please select a valid user role.',
            
            'dual_teacher.boolean' => 'Dual teacher option must be true or false.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate dual teacher role logic
            if ($this->dual_teacher && !in_array($this->role, ['lectureTeacher', 'homeroomTeacher'])) {
                $validator->errors()->add('dual_teacher', 'Dual teacher option is only available for teacher roles.');
            }
        });
    }
}