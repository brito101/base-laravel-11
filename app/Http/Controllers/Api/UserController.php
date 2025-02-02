<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Models\Views\User as ViewsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    public function __construct()
    {
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }
    }
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Usuários', true);

        if (Auth::user()->hasRole('Programador')) {
            $users = ViewsUser::all('id', 'name', 'email', 'type', 'photo');
        } elseif (Auth::user()->hasRole('Administrador')) {
            $users = ViewsUser::whereIn('type', ['Administrador', 'Usuário'])->get(['id', 'name', 'email', 'type', 'photo']);
        } else {
            $users = null;
        }

        foreach ($users as $user) {
            if ($user->photo) {
                $user->photo = url('/storage/users/' . $user->photo);
            }
        }

        return response()->json(compact('users'));
    }
    public function profile()
    {
        CheckPermission::checkAuth('Listar Usuários');

        $user = Auth::user()->only('id', 'name', 'email', 'type', 'photo');

        if ($user['photo']) {
            $user['photo'] = url('/storage/users/' . $user['photo']);
        }

        return response()->json(compact('user'));
    }
}
