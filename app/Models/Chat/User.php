<?php

namespace App\Models\Chat;

use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class User extends Model
{
    public $table = 'users_view';

    protected $appends = ['photo', 'lastMessage', 'active'];

    public function getPhotoAttribute($value)
    {
        $photo = asset('vendor/adminlte/dist/img/avatar.png');
        $user = ModelsUser::find($this->id);
        if ($user->photo) {
            $photo = url('storage/users/' . $user->photo);
        }
        return $photo;
    }

    public function getLastMessageAttribute($value)
    {
        $me = Auth::user()->id;
        $user = $this->id;

        $message = Message::where(function ($query) use ($me, $user) {
            $query->where('sender_id',  $me)
                ->where('receiver_id', $user);
        })->orWhere(function ($query) use ($me, $user) {
            $query->where('receiver_id', $me)
                ->where('sender_id', $user);
        })->orderBy('created_at', 'desc')->first();

        $lastMessage = ['message' => '', 'date' => ''];
        if ($message) {
            $lastMessage['message'] = $message->message;
            $lastMessage['date'] = $message->created_at;
        }

        return $lastMessage;
    }

    public function getActiveAttribute($value)
    {
        $class = 'text-muted';
        $user = ModelsUser::find($this->id);
        if ($user->isOnline()) {
            $class = 'text-success';
        }
        return $class;
    }
}
