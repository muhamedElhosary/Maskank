<?php

namespace App\Http\Requests\Data;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'images' => 'required',
            'description' => 'required|string',
            'price' => 'required|integer',
            'size' => 'required|string',
            'purpose' => 'required|string',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'region' => 'required|string',
            'city' => 'required|string',
            'floor' => 'required|string',
            'condition' => 'required|string',
            // 'booked' => 'required|integer',
            'owner_id' => 'required|integer',
            'admin_id' => 'sometimes|integer',
        ];
    }
}
