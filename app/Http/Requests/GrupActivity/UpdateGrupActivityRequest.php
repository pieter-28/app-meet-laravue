<?php

namespace App\Http\Requests\GrupActivity;

use App\Models\GrupActivity;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGrupActivityRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'description.required' => 'Deskripsi harus diisi',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'description' => 'Deskripsi',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('code')) {
                $code = $this->input('code');
                $exists = GrupActivity::where('code', $code)->exists();
                if ($exists) {
                    $validator->errors()->add('code', 'Kode sudah digunakan');
                }
            }
        });
    }
}
