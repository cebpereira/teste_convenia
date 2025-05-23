<?php

namespace App\Http\Requests\Collaborator;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ImportCollaboratorRequest",
 *     title="ImportCollaboratorRequest",
 *     description="Import collaborator request",
 *     required={"file"},
 *     @OA\Property(
 *         property="file",
 *         type="string",
 *         format="binary",
 *         description="CSV file with collaborator data",
 *         @OA\Examples(example="csv", value="collaborators.csv", summary="CSV file")
 *     ),
 * )
 */
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
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ];
    }
}
