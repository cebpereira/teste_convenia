<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collaborator\UpdateCollaboratorRequest;
use App\Http\Requests\Collaborator\CreateCollaboratorRequest;
use App\Models\Collaborator;
use App\Services\CollaboratorService;

class CollaboratorController extends Controller
{
    protected $collaboratorService;

    public function __construct(CollaboratorService $collaboratorService)
    {
        $this->collaboratorService = $collaboratorService;
    }

    public function store(CreateCollaboratorRequest $request)
    {
        return $this->collaboratorService->create($request->all());
    }

    public function import()
    {
        return $this->collaboratorService->import();
    }

    public function list()
    {
        return $this->collaboratorService->list();
    }

    public function update(UpdateCollaboratorRequest $request, Collaborator $collaborator)
    {
        return $this->collaboratorService->update($request->all(), $collaborator->id);
    }

    public function destroy(Collaborator $collaborator)
    {
        return $this->collaboratorService->delete($collaborator->id);
    }
}
