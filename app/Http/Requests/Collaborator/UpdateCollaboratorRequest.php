<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCollaboratorRequest",
 *     title="UpdateCollaboratorRequest",
 *     description="Update collaborator request",
 *     @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="mail@example.com"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-00"),
 *     @OA\Property(property="city", type="string", maxLength=100, example="Sao Paulo"),
 *     @OA\Property(property="state", type="string", maxLength=100, example="Sao Paulo"),
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
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:collaborators,email,' . $collaborator->id,
            'cpf' => 'sometimes|string|unique:collaborators,cpf,' . $collaborator->id,
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
        ];
    }
}
