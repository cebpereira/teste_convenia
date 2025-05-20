<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'managed_by',
    ];

    /**
     * Get the user that is managing the collaborator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function managedBy()
    {
        return $this->belongsTo(User::class, 'managed_by');
    }
}
