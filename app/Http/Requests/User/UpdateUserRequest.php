<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     title="UpdateUserRequest",
 *     description="Update user request",
 *     required={"name", "email", "password", "password_confirmation"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="mail@example.com"),
 *     @OA\Property(property="password", type="string", example="password"),
 *     @OA\Property(property="password_confirmation", type="string", example="password"),
 * )
 */
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
            'password_confirmation' => 'sometimes|required_if:password|same:password',
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
            '*.required_if' => 'The field :attribute is required when :other is :value.',
            '*.unique' => 'The :attribute is already in use.',
            '*.email' => 'The field :attribute must be a valid email.',
            '*.min' => 'The field :attribute must have :min characters.',
            '*.same' => 'The field :attribute must be the same as :other.',
        ];
    }
}
