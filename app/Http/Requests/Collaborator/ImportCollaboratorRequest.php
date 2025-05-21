<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Foundation\Http\FormRequest;

class ImportCollaboratorRequest extends FormRequest
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
            'file' => 'required|file|mimes:csv,txt',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '*.required' => 'The field :attribute is required.',
            '*.file' => 'The field :attribute must be a file.',
            '*.mimes' => 'The field :attribute must be a CSV or TXT file.',
        ];
    }
}
