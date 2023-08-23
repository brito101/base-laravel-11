<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'capacity', 'description', 'creator', 'editor'
    ];

    /** Relationships */
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function components()
    {
        return $this->hasMany(ComponentOrganization::class);
    }

    /** Cascade actions */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($tool) {
            $tool->members()->delete();
            $tool->components()->delete();
        });
    }
}
