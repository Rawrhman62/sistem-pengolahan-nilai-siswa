<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = Auth::user();
        $passwordConfig = config('security.password', []);
        
        $passwordRules = [
            'required',
            'confirmed',
            Password::min($passwordConfig['min_length'] ?? 6),
        ];

        // Add complexity requirements based on configuration
        if ($passwordConfig['require_letters'] ?? true) {
            $passwordRules[] = Password::min($passwordConfig['min_length'] ?? 6)->letters();
        }
        
        if ($passwordConfig['require_mixed_case'] ?? true) {
            $passwordRules[] = Password::min($passwordConfig['min_length'] ?? 6)->mixedCase();
        }
        
        if ($passwordConfig['require_numbers'] ?? true) {
            $passwordRules[] = Password::min($passwordConfig['min_length'] ?? 6)->numbers();
        }
        
        if ($passwordConfig['require_symbols'] ?? false) {
            $passwordRules[] = Password::min($passwordConfig['min_length'] ?? 6)->symbols();
        }

        $rules = [
            'password' => $passwordRules,
            'password_confirmation' => [
                'required',
                'string',
            ],
        ];

        // If user already has a password set, require current password
        if ($user && $user->password_set) {
            $rules['current_password'] = [
                'required',
                'current_password',
            ];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'The current password is incorrect.',
            
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 6 characters.',
            
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.string' => 'Password confirmation must be valid text.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'current_password' => 'current password',
            'password' => 'new password',
            'password_confirmation' => 'password confirmation',
        ];
    }
}