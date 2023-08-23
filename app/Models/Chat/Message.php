<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $hidden = ['updated_at', 'deleted_at'];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'sent',
        'read',
    ];

    protected $appends = ['senderPhoto'];

    /** Accessors */

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y H:i:s', strtotime($value));
    }

    /** Relationships */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /** Aux */
    public function getSenderPhotoAttribute($value)
    {
        $photo = asset('vendor/adminlte/dist/img/avatar.png');
        $user = User::find($this->sender_id);
        if ($user->photo) {
            $photo = $user->photo;
        }
        return $photo;
    }
}
