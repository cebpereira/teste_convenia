<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Collaborator",
 *     title="Collaborator",
 *     description="Collaborator model",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="mail@example.com"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-00"),
 *     @OA\Property(property="city", type="string", example="Sao Paulo"),
 *     @OA\Property(property="state", type="string", example="SP"),
 *     @OA\Property(property="manager_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2022-01-01T00:00:00Z"),
 * )
 */
class Collaborator extends Model
{
    /** @use HasFactory<\Database\Factories\CollaboratorFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'city',
        'state',
        'manager_id',
    ];

    /**
     * Get the user that is managing the collaborator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function managedBy()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
