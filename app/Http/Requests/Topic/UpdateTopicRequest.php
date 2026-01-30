<?php

namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicRequest extends FormRequest
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
        $id = $this->route('topic')->id;
        return [
            'topic_code' => 'required|unique:topics,topic_code,'.$id,
            'topic_name' => 'required|string|max:255',
            'topic_description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'topic_code.required' => 'Topic code is required',
            'topic_code.unique' => 'Topic code must be unique',
            'topic_name.required' => 'Topic name is required',
            'topic_name.string' => 'Topic name must be a string',
            'topic_name.max' => 'Topic name must be less than 255 characters',
        ];
    }
}
