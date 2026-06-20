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
            ],
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                'unique:tb_users,email',
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/',
            ],
            'gender' => [
                'required',
                'in:M,F',
            ],
            'role' => [
                'required',
                Rule::in(['administrator', 'lectureTeacher', 'homeroomTeacher', 'student']),
            ],
            'dual_teacher' => [
                'nullable',
                'boolean',
            ],
            'nomor_induk' => [
                'required_if:role,administrator,lectureTeacher,homeroomTeacher',
                'nullable',
                'string',
                'max:50',
                'unique:tb_teachers,nomor_induk',
            ],
            'type' => [
                'required_if:role,administrator,lectureTeacher,homeroomTeacher',
                'nullable',
                'in:pns,honorer',
            ],
            'nis' => [
                'nullable',
                'string',
                'max:50',
                'unique:tb_students,nis',
            ],
            'nisn' => [
                'nullable',
                'string',
                'max:50',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',
            'name.min' => 'Nama lengkap minimal 2 karakter.',
            
            'email.email' => 'Harap masukkan alamat email yang valid.',
            'email.max' => 'Alamat email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            
            'phone_number.string' => 'Nomor telepon harus berupa teks.',
            'phone_number.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'phone_number.regex' => 'Format nomor telepon tidak valid.',
            
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin harus M (Laki-laki) atau F (Perempuan).',
            
            'role.required' => 'Peran pengguna wajib dipilih.',
            'role.in' => 'Harap pilih peran pengguna yang valid.',
            
            'dual_teacher.boolean' => 'Opsi dual teacher harus true atau false.',
            
            'nomor_induk.required_if' => 'Nomor induk guru wajib diisi untuk peran guru/admin.',
            'nomor_induk.unique' => 'Nomor induk guru sudah terdaftar.',
            
            'type.required_if' => 'Status kepegawaian wajib dipilih untuk peran guru/admin.',
            'type.in' => 'Status kepegawaian harus PNS atau Honorer.',
            
            'nis.unique' => 'NIS sudah terdaftar.',
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