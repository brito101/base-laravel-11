<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'description', 'image', 'creator', 'editor'
    ];

    /** Relationships */
    public function observations()
    {
        return $this->hasMany(ToolObservation::class);
    }

    public function images()
    {
        return $this->hasMany(ToolImage::class);
    }

    public function files()
    {
        return $this->hasMany(ToolFile::class);
    }

    public function relatedSteps()
    {
        return $this->hasMany(RelatedStep::class);
    }

    public function relatedStepsName()
    {
        $values = [];
        foreach ($this->relatedSteps as $item) {
            $values[] = $item->step['name'];
        }

        return implode(", ", $values);
    }

    public function tags()
    {
        return $this->hasMany(ToolTag::class);
    }

    /** Cascade actions */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($tool) {
            $tool->observations()->delete();
            $tool->images()->delete();
            $tool->files()->delete();
            $tool->relatedSteps()->delete();
            $tool->tags()->delete();
        });
    }
}
