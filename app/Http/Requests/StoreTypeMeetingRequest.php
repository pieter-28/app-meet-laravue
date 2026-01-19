<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeMeetingRequest extends FormRequest
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
            'type_meeting_code'=>'nullable',
            'type_meeting_name'=>'required',
            'type_meeting_description'=>'nullable',
        ];
    }

    public function messages()
    {
        return [
            'type_meeting_name.required'=>'Nama Tipe Rapat Wajib Diisi',
        ];
    }
}
