<?php

namespace App\Services;

use App\Jobs\ImportCollaboratorsJob;
use App\Models\Collaborator;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CollaboratorService
{
    /**
     * Create a new collaborator for the authenticated user.
     *
     * @param  mixed  $data  The data required to create a collaborator.
     * @return \App\Models\Collaborator  The created collaborator instance.
     *
     * @throws \Exception If the user is not authenticated or the collaborator creation fails.
     */
    public function create($data)
    {
        $user = Auth::user();

        throw_if(!$user, 'User not authenticated', 401);

        $collaborator = Collaborator::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'cpf' => $data['cpf'],
            'city' => $data['city'],
            'state' => $data['state'],
            'manager_id' => $user->id,
        ]);

        throw_if(!$collaborator, 'Failed to create collaborator', 500);

        return $collaborator;
    }

    /**
     * Retrieve all collaborators managed by the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception If the user is not found or no collaborators are found.
     */
    public function list()
    {
        $user = Auth::user();

        throw_if(!$user, 'User not authenticated', 401);

        $collaborators = Collaborator::where('manager_id', $user->id)->get();

        throw_if($collaborators->isEmpty(), 'No collaborators found', 404);

        return $collaborators;
    }

    /**
     * Imports collaborators from a file for the authenticated user.
     *
     * @param mixed $data The file data to be imported.
     * @return string The path of the stored import file.
     *
     * @throws \Exception If the user is not authenticated, the file is not found,
     *                    or if there is a failure in storing the import file.
     */
    public function import($data)
    {
        $user = Auth::user();

        throw_if(!$user, 'User not authenticated', 401);

        throw_if(!$data, 'File not found', 404);

        $timestamp = now()->format('Ymd_His');
        $filename = 'import_' . $timestamp . '.' . $data->getClientOriginalExtension();
        $filePath = $data->storeAs('imports', $filename, 'local');

        throw_if(!Storage::disk('local')->exists($filePath), 'Failed to store the import file', 500);

        dispatch(new ImportCollaboratorsJob($user->id, $filePath));

        return $filePath;
    }

    /**
     * Update a collaborator of the authenticated user.
     *
     * @param  mixed  $data  The data required to update a collaborator.
     * @param  Collaborator  $collaborator  The collaborator to update.
     * @return \App\Models\Collaborator
     *
     * @throws \Exception If the user is not found, the collaborator is not managed by user, or no data to update.
     */
    public function update($data, Collaborator $collaborator)
    {
        $user = Auth::user();

        throw_if(!$user, 'User not authenticated', 401);

        throw_if($collaborator->manager_id !== $user->id, 'Collaborator not managed by user', 403);

        $updateData = $this->prepareUpdateData($data);

        throw_if(empty($updateData), 'No data to update', 400);

        $collaborator->update($updateData);

        return $collaborator;
    }

    /**
     * Delete a collaborator managed by the authenticated user.
     *
     * @param  \App\Models\Collaborator  $collaborator  The collaborator to delete.
     * @return bool
     *
     * @throws \Exception If the user is not found or the collaborator is not managed by user.
     */
    public function delete(Collaborator $collaborator)
    {
        $user = Auth::user();

        throw_if(!$user, 'User not found', 404);

        throw_if($collaborator->manager_id !== $user->id, 'Collaborator not managed by user', 403);

        return $collaborator->delete();
    }

    /**
     * Prepare the data to be used when updating a collaborator.
     *
     * We only allow updating the collaborator's name, email, cpf, city, and state. If any
     * of these values are null, they are discarded.
     *
     * @param  object|array  $data  The data required to update the collaborator.
     * @return array
     */
    protected function prepareUpdateData($data)
    {
        $updateData = Arr::only((array)$data, ['name', 'email', 'cpf', 'city', 'state']);

        return array_filter($updateData, function ($value) {
            return $value !== null;
        });
    }
}
