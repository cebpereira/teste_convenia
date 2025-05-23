<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CreateCollaboratorRequest",
 *     title="CreateCollaboratorRequest",
 *     description="Create collaborator request",
 *     required={"name", "email", "cpf", "city", "state"},
 *     @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="mail@example.com"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-00"),
 *     @OA\Property(property="city", type="string", maxLength=100, example="Sao Paulo"),
 *     @OA\Property(property="state", type="string", maxLength=100, example="Sao Paulo"),
 * )
 */
class CreateCollaboratorRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:collaborators',
            'cpf' => 'required|string|unique:collaborators',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
        ];
    }
}
