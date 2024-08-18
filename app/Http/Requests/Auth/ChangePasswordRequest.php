<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'old_password' =>['required'],
            'password' =>['required',

                                'string',
                                 'min:8',
                                 'regex:/[A-Z]/',
                                 'regex:/[0-9]/',
                                 'regex:/[@$!%*#?&]/',
                                 'regex:/[a-z]/',
                                   ]
     ];
    }
    public function messages()
    {
        return [
            'old_password.required' => 'The old password is required.',
            'password.required' => 'The new password is required.',
            'password.min' => 'The new password must be at least 8 characters.',
            'password.regex' => 'The new password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
