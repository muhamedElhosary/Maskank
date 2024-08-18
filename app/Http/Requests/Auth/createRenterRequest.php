<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class createRenterRequest extends FormRequest
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
            "username"    => "required|string|unique:renters|regex:/^\S*$/u",
            "email"       => "required|email|email:rfc,dns|unique:renters,email",
            "renter_name" => "required|string",
            "phone"       => "required|string|unique:renters,phone",
            "password"    => ['required',
            Password::min(3)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised()
        ],
            "photo" => ['sometimes','file','mimes:png,jpg'],
        ];
    }
    public function messages()
{
    return [
        'username.required' => 'The username field is required.',
        'username.string' => 'The username must be a string.',
        'username.unique' => 'The username has already been taken.',
        'username.regex' => 'The username should not contain spaces.',

        'email.required' => 'The email field is required.',
        'email.email' => 'The email must be a valid email address.',
        'email.unique' => 'The email has already been taken.',

        'renter_name.required' => 'The renter name field is required.',
        'renter_name.string' => 'The renter name must be a string.',

        'phone.required' => 'The phone field is required.',
        'phone.string' => 'The phone must be a string.',
        'phone.unique' => 'The phone has already been taken.',

        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 3 characters.',
        'password.letters' => 'The password must contain at least one letter.',
        'password.mixed_case' => 'The password must contain both uppercase and lowercase letters.',
        'password.numbers' => 'The password must contain at least one number.',
        'password.symbols' => 'The password must contain at least one symbol.',
        'password.uncompromised' => 'The password has been compromised. Please choose a different one.',

        'photo.file' => 'The photo must be a file.',
        'photo.mimes' => 'The photo must be a file of type: png, jpg.',
    ];
}
}
