<?php

namespace App\Http\Controllers\Admin\ACL;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Models\Permission;
use DataTables;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Permissões');

        $permissions = Permission::all();

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($permissions)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="permission/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' . '<form method="POST" action="permission/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta permissão?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.acl.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Permissões');

        return view('admin.acl.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        CheckPermission::checkAuth('Criar Permissões');

        $check = Permission::where('name', $request->name)->first();
        if ($check) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Nome da permissão já está em uso!');
        }

        $permission = Permission::create($request->all());

        if ($permission->save()) {
            return redirect()
                ->route('admin.permission.index')
                ->with('success', 'Permissão cadastrada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Falha ao cadastrar a permissão!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Permissões');

        $permission = Permission::find($id);
        if (!$permission) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.acl.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        CheckPermission::checkAuth('Editar Permissões');

        $check = Permission::where('name', $request->name)->where('id', '!=', $id)->first();
        if ($check) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'O nome desta permissão já está em uso!');
        }

        $permission = Permission::find($id);
        if (!$permission) {
            abort(403, 'Acesso não autorizado');
        }

        if ($permission->update($request->all())) {
            return redirect()
                ->route('admin.permission.index')
                ->with('success', 'Atualização realizada!');
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
        CheckPermission::checkAuth('Excluir Permissões');

        $permission = Permission::find($id);
        if (!$permission) {
            abort(403, 'Acesso não autorizado');
        }

        if ($permission->delete()) {
            return redirect()
                ->route('admin.permission.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
