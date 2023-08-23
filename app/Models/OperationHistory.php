<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'operation_id',
        'action',
        'step_id',
        'user_id',
    ];

    /** Relationships */
    public function operation()
    {
        return $this->belongsTo(Operation::class)->withDefault([
            'title' => 'Inexistente',
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Inexistente',
        ]);
    }

    public function step()
    {
        return $this->belongsTo(Step::class)->withDefault([
            'name' => 'Inexistente',
        ]);
    }

    /** Accessors */
    public function getCreatedAtAttribute($value)
    {
        return date("d/m/Y H:i:s", strtotime($value));
    }
}
