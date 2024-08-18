<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class createOwnerRequest extends FormRequest
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
            "username"    => "required|string|unique:owners|regex: /^\S*$/u",
            "owner_name" => "required|string",
            "email"       => "required|email:rfc,dns|unique:owners,email",
            "phone"       => "required|string|unique:owners,phone",
            "password"    => ['required',
            Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised()
        ],
            'national_id' => ['required',],
            "photo"       => ['sometimes','file','mimes:png,jpg'],
        ];
    }
    public function messages()
    {
        return [

            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.unique' => 'Username already exists.',
            'username.regex' => 'The username must not contain spaces.',

            'owner_name.required' => 'Owner name is required.',
            'owner_name.string' => 'Owner name must be a string.',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email already exists.',

            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a string.',
            'phone.unique' => 'Phone number already exists.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters long.',
            'password.letters' => 'Password must contain letters.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain numbers.',
            'password.symbols' => 'Password must contain symbols.',
            'password.uncompromised' => 'Password has been compromised. Please choose a different one.',

            'national_id.required' => 'National ID is required.',

            'photo.file' => 'Photo must be a file.',
            'photo.mimes' => 'Photo must be a PNG or JPG image.',
    ];
}
}
