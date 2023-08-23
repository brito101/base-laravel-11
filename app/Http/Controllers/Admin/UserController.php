<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Models\Views\User as ViewsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Image;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Usuários');

        if (Auth::user()->hasRole('Programador')) {
            $users = ViewsUser::all('id', 'name', 'email', 'type');
        } elseif (Auth::user()->hasRole('Administrador')) {
            $users = ViewsUser::whereIn('type', ['Administrador', 'Usuário'])->get();
        } else {
            $users = null;
        }

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="users/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' . '<form method="POST" action="users/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste usuário?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Usuários');

        if (Auth::user()->hasRole('Programador')) {
            $roles = Role::all(['id', 'name']);
        } else {
            $roles = Role::where('name', '!=', 'Programador')->get(['id', 'name']);
        }
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        CheckPermission::checkAuth('Criar Usuários');

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)) . time();
            $extension = $request->photo->extension();
            $nameFile = "{$name}.{$extension}";

            $data['photo'] = $nameFile;

            $destinationPath = storage_path() . '/app/public/users';

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            $img = Image::make($request->photo);
            $img->resize(null, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(100, 100)->save($destinationPath . '/' . $nameFile);

            if (!$img) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
            }
        }

        $user = User::create($data);

        if ($user->save()) {
            if (!empty($request->role)) {
                $user->syncRoles($request->role);
                $user->save();
            }
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {
        if ($id) {
            CheckPermission::checkAuth('Editar Usuários');
        } else {
            CheckPermission::checkAuth('Editar Usuário');
            $id = Auth::user()->id;
        }

        $user = User::find($id);
        if (!$user) {
            abort(403, 'Acesso não autorizado');
        }

        if (Auth::user()->hasRole('Programador')) {
            $roles = Role::all(['id', 'name']);
        } else {
            $roles = Role::where('name', '!=', 'Programador')->get(['id', 'name']);
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        CheckPermission::checkManyAuth(['Editar Usuários', 'Editar Usuário']);

        $data = $request->all();

        if (!Auth::user()->hasPermissionTo('Editar Usuários') && Auth::user()->hasPermissionTo('Editar Usuário')) {
            $user = User::where('id', Auth::user()->id)->first();
        } else {
            $user = User::find($id);
        }

        if (!$user) {
            abort(403, 'Acesso não autorizado');
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password;
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 200)) . "-" . time();
            $imagePath = storage_path() . '/app/public/users/' . $user->photo;

            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            $extenstion = $request->photo->extension();
            $nameFile = "{$name}.{$extenstion}";

            $data['photo'] = $nameFile;

            $destinationPath = storage_path() . '/app/public/users';

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            $img = Image::make($request->photo);
            $img->resize(null, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->crop(100, 100)->save($destinationPath . '/' . $nameFile);

            if (!$img)
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Falha ao fazer o upload da imagem');
        }

        if ($user->update($data)) {
            if (!empty($request->role)) {
                $user->syncRoles($request->role);
                $user->save();
            }

            if (Auth::user()->hasPermissionTo('Editar Usuários')) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('success', 'Atualização realizada!');
            } else {
                return redirect()
                    ->route('admin.user.edit')
                    ->with('success', 'Atualização realizada!');
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CheckPermission::checkAuth('Excluir Usuários');

        $user = User::find($id);

        if (!$user) {
            abort(403, 'Acesso não autorizado');
        }

        $imagePath = storage_path() . '/app/public/users/' . $user->photo;
        if ($user->delete()) {
            if (File::isFile($imagePath)) {
                unlink($imagePath);
                $user->photo = null;
                $user->update();
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
