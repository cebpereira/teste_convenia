<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $user = $this->user();

        return [
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|min:6',
            'password_confirmation' => 'required_if:password|same:password',
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
            '*.required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
            '*.unique' => 'O :attribute já está em uso.',
            '*.email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
            '*.min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            '*.same' => 'O campo :attribute deve ser igual ao campo :other.',
        ];
    }
}
