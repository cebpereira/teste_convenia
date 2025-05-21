<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCollaboratorRequest",
 *     title="UpdateCollaboratorRequest",
 *     description="Update collaborator request",
 *     required={"name", "email", "cpf", "city", "state"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="mail@example.com"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-00"),
 *     @OA\Property(property="city", type="string", example="Sao Paulo"),
 *     @OA\Property(property="state", type="string", example="SP"),
 * )
 */
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
            '*.required' => 'The field :attribute is required.',
            '*.unique' => 'The :attribute is already in use.',
            '*.email' => 'The field :attribute must be a valid email.',
        ];
    }
}
