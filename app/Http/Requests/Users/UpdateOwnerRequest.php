<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerRequest extends FormRequest
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
            'username'=>'sometimes|string|unique:owners',
            'owner_name'=>'sometimes|string',
            'email'=>'sometimes|email|unique:owners',
            'phone'=>'sometimes|string|unique:owners',
            'photo' => 'sometimes|image|mimes:jpg,bmp,png',
        ];
    }
}
