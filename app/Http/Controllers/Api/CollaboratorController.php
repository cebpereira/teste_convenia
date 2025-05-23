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
     *         @OA\JsonContent(ref="#/components/schemas/CreateCollaboratorRequest")
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
        try {
            $collaborator = $this->collaboratorService->create($request->validated());

            return response()->json(['collaborator' => $collaborator], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/collaborators/import",
     *     tags={"Collaborators"},
     *     security={{ "apiAuth": {} }},
     *     summary="Import collaborators from a CSV file",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ImportCollaboratorRequest")
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Send content of the CSV file to import job successfully"
     *     )
     * )
     */
    public function import(ImportCollaboratorRequest $request)
    {
        try {
            $this->collaboratorService->import($request->file('file'));

            return response()->json([
                'message' => 'Import occurring in the background, you will be notified when complete'
            ], 202);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
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
        try {
            $collaborators = $this->collaboratorService->list();

            return response()->json([$collaborators], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
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
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCollaboratorRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Collaborator updated successfully"
     *     )
     * )
     */
    public function update(UpdateCollaboratorRequest $request, Collaborator $collaborator)
    {
        try {
            $updated = $this->collaboratorService->update($request->validated(), $collaborator);

            return response()->json([
                'collaborator' => $updated,
                'message' => 'Collaborator updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
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
        try {
            $this->collaboratorService->delete($collaborator);

            return response()->json([
                'message' => 'Collaborator deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
