<?php

namespace App\Http\Controllers\Admin\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Chat\MessageRequest;
use App\Models\Chat\Message;
use App\Models\User;
use App\Models\Chat\User as ViewsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $me = Auth::user()->id;
        $list = [];

        $name = null;
        $lastContact = null;
        if ($request->contact) {
            $user = User::find($request->contact);
            $name = $user->name;
            $lastContact = $user->id;
        } else {
            $message = Message::where('sender_id', $me)->orderBy('created_at', 'desc')->first();
            if ($message) {
                $user = $message->receiver;
                $name = $user->name;
                $lastContact = $message->receiver_id;
            }
        }

        $messages = Message::where(function ($query) use ($me, $lastContact) {
            $query->where('sender_id',  $me)
                ->where('receiver_id', $lastContact);
        })->orWhere(function ($query) use ($me, $lastContact) {
            $query->where('receiver_id', $me)
                ->where('sender_id', $lastContact);
        })->orderBy('created_at', 'desc')->with('sender')->latest()->take(100)->get();

        $unreadMessages = Message::where('receiver_id', $me)->where('read', 0)->count();
        $users = ViewsUser::where('id', '!=', $me)->get();

        $list['messages'] = $messages;
        $list['lastContact'] = $lastContact;
        $list['unreadMessages'] = $unreadMessages;
        $list['users'] = $users;
        $list['name'] = $name;
        return \response()->json($list);
    }

    public function read(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'exists:users,id',
        ]);

        $me = Auth::user()->id;

        if (!$validator->fails()) {
            $messages = Message::where('sender_id',  $request->contact)
                ->where('receiver_id', $me)
                ->where('read', false)
                ->get();

            foreach ($messages as $message) {
                $message->read = true;
                $message->save();
            }
        } else {
            return \response()->json($validator->errors()->first());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MessageRequest $request)
    {
        $me = Auth::user()->id;
        $message = new Message();
        $message->sender_id = $me;
        $message->message = $request->message;
        $message->sent = true;
        $message->receiver_id = $request->receiver_id;

        if ($message->save()) {
            $messages = Message::where('sender_id',  $request->receiver_id)
                ->where('receiver_id', $me)
                ->where('read', false)
                ->get();

            foreach ($messages as $message) {
                $message->read = true;
                $message->save();
            }
        }
        return response()->json('success');
    }
}
