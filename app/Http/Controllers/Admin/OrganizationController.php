<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrganizationRequest;
use App\Models\Organization;
use App\Models\Views\Organization as ViewOrganization;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Acessar Configurações');
        CheckPermission::checkAuth('Listar Organizações');

        $organizations = ViewOrganization::get();

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($organizations)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="organizations/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' . '<form method="POST" action="organizations/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta organização?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.organizations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Organizações');
        $organizations = Organization::select('id', 'alias_name')->orderBy('alias_name')->get();
        return view('admin.organizations.create', compact('organizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        CheckPermission::checkAuth('Criar Organizações');

        $data = $request->all();

        $organization = Organization::create($data);

        if ($organization->save()) {
            return redirect()
                ->route('admin.organizations.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        CheckPermission::checkAuth('Listar Organizações');
        return redirect()->route('admin.organizations.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Organizações');
        $organization = Organization::find($id);
        if (!$organization) {
            abort(403, 'Acesso não autorizado');
        }

        $organizations = Organization::select('id', 'alias_name')->where('id', '!=', $organization->id)->orderBy('alias_name')->get();

        return view('admin.organizations.edit', compact('organization', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationRequest $request, $id)
    {
        CheckPermission::checkAuth('Editar Organizações');

        $data = $request->all();

        $organization = Organization::find($id);

        if (!$organization) {
            abort(403, 'Acesso não autorizado');
        }

        if ($organization->update($data)) {
            return redirect()
                ->route('admin.organizations.index')
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
        CheckPermission::checkAuth('Excluir Organizações');

        $organization = Organization::find($id);

        if (!$organization) {
            abort(403, 'Acesso não autorizado');
        }

        if ($organization->delete()) {
            return redirect()
                ->route('admin.organizations.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
