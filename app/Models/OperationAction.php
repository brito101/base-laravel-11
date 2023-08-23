<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'text',
        'image',
        'user_id',
        'operation_id'
    ];

    protected $appends = [
        'user_name',
        'user_photo',
        'date',
        'full_image',
        'delete_action'
    ];

    /** Relationships */

    public function user()
    {
        return $this->belongsTo(User::class)->select('name', 'photo');
    }

    public function getUserNameAttribute($value)
    {
        return $this->user->name;
    }

    public function getUserPhotoAttribute($value)
    {
        return $this->user->photo ? url('storage/users/' . $this->user->photo) : asset('vendor/adminlte/dist/img/avatar.png');
    }

    public function getDateAttribute()
    {
        return date('d/m/Y H:i:s', strtotime($this->created_at));
    }

    public function getFullImageAttribute()
    {
        if ($this->image) {
            return asset('storage/operations/actions/' . $this->image);
        } else {
            return null;
        }
    }

    public function getDeleteActionAttribute()
    {
        return route('admin.kanban.delete.actions', ['id' => $this->id]);
    }
}
