<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title', 'reference', 'code', 'spindle',
        'situation', 'type', 'mission', 'start', 'end', 'classification', 'step_id',
        'execution', 'instructions', 'logistics', 'file', 'creator', 'editor'
    ];

    /** Relationships */
    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function operationSteps()
    {
        return $this->hasMany(OperationStep::class);
    }

    public function operationTeams()
    {
        return $this->hasMany(OperationTeam::class);
    }

    public function operationActions()
    {
        return $this->hasMany(OperationAction::class)->orderBy('created_at', 'desc');
    }

    /** Cascade actions */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($operation) {
            $operation->operationSteps()->delete();
            $operation->operationTeams()->delete();
        });
    }
}
