<?php

namespace App\Services;

use App\Jobs\ImportCollaboratorsJob;
use App\Models\Collaborator;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;

class CollaboratorService
{
    /**
     * Create a new collaborator for the authenticated user.
     *
     * @param  mixed  $data  The data required to create a collaborator.
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception If the user is not found or collaborator creation fails.
     */
    public function create($data)
    {
        try {
            $user = Auth::user();

            throw_if(!$user, 'User not authenticated', 401);

            $collaborator = Collaborator::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'city' => $data['city'],
                'state' => $data['state'],
                'managed_by' => $user->id,
            ]);

            throw_if(!$collaborator, 'Failed to create collaborator', 500);

            return response()->json([
                'collaborator' => $collaborator,
            ], 201);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Get all collaborators of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception If the user is not found or no collaborators are found.
     */
    public function list()
    {
        try {
            $user = Auth::user();

            throw_if(!$user, 'User not authenticated', 401);

            $collaborators = Collaborator::where('managed_by', $user->id)->get();

            throw_if(!$collaborators, 'No collaborators found', 404);

            return response()->json($collaborators, 201);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function import($data)
    {
        try {
            $user = Auth::user();

            throw_if(!$user, 'User not authenticated', 401);

            throw_if(!$data, 'File not found', 400);

            $timestamp = now()->format('Ymd_His');
            $filename = 'import_' . $timestamp . '.' . $data->getClientOriginalExtension();

            $filePath = $data->storeAs('imports', $filename, 'local');

            throw_if (!Storage::disk('local')->exists($filePath), 'Failed to store the import file', 500);

            dispatch(new ImportCollaboratorsJob($user->id, $filePath));

            return response()->json('Import occurring in the background, you will be notified when the process is complete', 201);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Update a collaborator.
     *
     * @param  mixed  $data  The data required to update a collaborator.
     * @param  mixed  $collaborator  The collaborator to be updated.
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception If the user is not found, collaborator is not found, or collaborator update fails.
     */
    public function update($data, $collaborator)
    {
        try {
            $user = Auth::user();

            throw_if(!$user, 'User not authenticated', 401);

            $collaborator = Collaborator::where('id', $collaborator)
                ->where('managed_by', $user->id)
                ->first();

            throw_if(!$collaborator, 'Collaborator not found or not managed by user', 404);

            $updateData = $this->prepareUpdateData($data);

            throw_if(!$updateData, 'No data to update', 400);

            $collaborator->update($updateData);
            $collaborator->save();

            return response()->json([
                'collaborator' => $collaborator,
                'message' => 'Collaborator updated successfully'
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        } catch (JWTException $exjwt) {
            return response()->json(['error' => $exjwt->getMessage()], 500);
        }
    }

    /**
     * Delete a collaborator.
     *
     * @param  Collaborator  $collaborator
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception If the user is not found or collaborator deletion fails.
     */
    public function delete($collaborator)
    {
        try {
            $user = Auth::user();

            throw_if(!$user, 'User not found', 404);

            $collaborator = Collaborator::where('id', $collaborator)
                ->where('managed_by', $user->id)
                ->first();

            throw_if(!$collaborator, 'Collaborator not found or not managed by user', 404);

            $collaborator->delete();

            return response()->json([
                'message' => 'Collaborator deleted successfully'
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
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
