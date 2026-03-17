<?php

namespace App\Http\Requests\PIC;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePicRequest extends FormRequest
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
            'name' => 'required|string',
            'status' => 'required|in:active,inactive',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'archievement' => 'nullable|string',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be active or inactive',
            'email.email' => 'Email must be a valid email',
            'phone.string' => 'Phone must be a string',
            'archievement.string' => 'Archievement must be a string',
            'description.string' => 'Description must be a string',
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User ID must be a valid user',
        ];
    }
}
