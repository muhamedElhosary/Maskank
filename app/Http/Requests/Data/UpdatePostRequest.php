<?php

namespace App\Http\Requests\Data;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'images' => 'sometimes',
            'description' => 'sometimes|string',
            'price' => 'sometimes|integer',
            'size' => 'sometimes|string',
            'purpose' => 'sometimes|string',
            'bedrooms' => 'sometimes|integer',
            'bathrooms' => 'sometimes|integer',
            'region' => 'sometimes|string',
            'city' => 'sometimes|string',
            'floor' => 'sometimes|string',
            'condition' => 'sometimes|string',
            'booked' => 'sometimes|integer',
            'owner_id' => 'sometimes|integer',
            'admin_id' => 'sometimes|integer',
        ];
    }
}
