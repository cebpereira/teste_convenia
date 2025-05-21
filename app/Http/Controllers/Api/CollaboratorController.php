<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collaborator\UpdateCollaboratorRequest;
use App\Http\Requests\Collaborator\CreateCollaboratorRequest;
use App\Http\Requests\Collaborator\ImportCollaboratorRequest;
use App\Models\Collaborator;
use App\Services\CollaboratorService;
use OpenApi\Annotations as OA;

class CollaboratorController extends Controller
{
    protected $collaboratorService;

    public function __construct(CollaboratorService $collaboratorService)
    {
        $this->collaboratorService = $collaboratorService;
    }

    /**
     * @OA\Post(
     *     path="/api/collaborators",
     *     tags={"Collaborators"},
     *     summary="Create a new collaborator",
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="state", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Collaborator created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *         )
     *     )
     * )
     */
    public function store(CreateCollaboratorRequest $request)
    {
        return $this->collaboratorService->create($request->all());
    }

    /**
     * @OA\Post(
     *     path="/api/collaborators/import",
     *     tags={"Collaborators"},
     *     security={{ "apiAuth": {} }},
     *     summary="Import collaborators from a CSV file",
     *     @OA\Response(
     *         response=200,
     *         description="Send content of the CSV file to import job successfully"
     *     )
     * )
     */
    public function import(ImportCollaboratorRequest $request)
    {
        return $this->collaboratorService->import($request->file('file'));
    }

    /**
     * @OA\Get(
     *     path="/api/collaborators",
     *     tags={"Collaborators"},
     *     summary="Get all collaborators of the authenticated user",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="List of collaborators"
     *     )
     * )
     */
    public function list()
    {
        return $this->collaboratorService->list();
    }

    /**
     * @OA\Put(
     *     path="/api/collaborators/{collaborator}",
     *     tags={"Collaborators"},
     *     summary="Update a collaborator",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *         name="collaborator",
     *         in="path",
     *         required=true,
     *         description="ID of the collaborator to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="cpf", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="state", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Collaborator updated successfully"
     *     )
     * )
     */
    public function update(UpdateCollaboratorRequest $request, Collaborator $collaborator)
    {
        return $this->collaboratorService->update($request->all(), $collaborator->id);
    }

    /**
     * @OA\Delete(
     *     path="/api/collaborators/{collaborator}",
     *     tags={"Collaborators"},
     *     summary="Delete a collaborator",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *         name="collaborator",
     *         in="path",
     *         required=true,
     *         description="ID of the collaborator to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Collaborator deleted successfully"
     *     )
     * )
     */
    public function destroy(Collaborator $collaborator)
    {
        return $this->collaboratorService->delete($collaborator->id);
    }
}
