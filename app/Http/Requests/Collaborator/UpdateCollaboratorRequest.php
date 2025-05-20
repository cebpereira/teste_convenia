<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollaboratorRequest extends FormRequest
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
        $collaborator = $this->route('collaborator');

        return [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:collaborators,email,' . $collaborator->id,
            'cpf' => 'sometimes|required|unique:collaborators,cpf,' . $collaborator->id,
            'city' => 'sometimes|required',
            'state' => 'sometimes|required',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            '*.required' => 'O campo :attribute é obrigatório.',
            '*.unique' => 'O :attribute já está em uso.',
            '*.email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
        ];
    }
}
