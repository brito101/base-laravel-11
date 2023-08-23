<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolObservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'observation', 'tool_id', 'user_id'
    ];

    /** Relationships */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'Não informado']);
    }
}
