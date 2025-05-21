<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CreateUserRequest",
 *     title="CreateUserRequest",
 *     description="Create user request",
 *     required={"name", "email", "password", "password_confirmation"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="mail@example.com"),
 *     @OA\Property(property="password", type="string", example="password"),
 *     @OA\Property(property="password_confirmation", type="string", example="password"),
 * )
 */
class CreateUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
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
            '*.required' => 'The field :attribute is required.',
            '*.unique' => 'O :attribute is already in use.',
            '*.email' => 'The field :attribute must be a valid email.',
            '*.min' => 'The field :attribute must have :min characters.',
            '*.same' => 'The field :attribute must be the same as :other.',
        ];
    }
}
