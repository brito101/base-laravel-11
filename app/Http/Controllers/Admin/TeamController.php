<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamRequest;
use App\Models\ComponentOrganization;
use App\Models\Organization;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Views\Team as ViewsTeam;
use App\Models\Views\User as ViewsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Equipes');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $teams = ViewsTeam::get();
        } else {
            $componentOrganizations = ComponentOrganization::where('organization_id', Auth::user()->organization_id)->pluck('team_id');
            $teams = ViewsTeam::whereIn('id', $componentOrganizations)->orWhere('creator', Auth::user()->id)->get();
        }

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($teams)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="teams/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="teams/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste time?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    return $btn;
                })
                ->addColumn('description', function ($row) {
                    $text = Str::limit($row->description, 100, '...');
                    return $text;
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }

        return view('admin.teams.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Equipes');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $users = User::select('id', 'name', 'organization_id')
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->orderBy('alias_name')->get();
        } else {
            $users = User::select('id', 'name', 'organization_id')
                ->where('organization_id', Auth::user()->organization_id)
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->where('id', Auth::user()->organization_id)
                ->orderBy('alias_name')->get();
        }

        return view('admin.teams.create', compact('users', 'organizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        CheckPermission::checkAuth('Criar Equipes');

        $data = $request->all();

        $user_id = Auth::user()->id;
        $data['creator'] = $user_id;
        $data['editor'] = $user_id;

        $team = Team::create($data);

        if ($team->save()) {
            //users
            if ($request->users) {
                foreach ($request->users as $item) {
                    if (!Auth::user()->hasRole('Programador|Administrador')) {
                        $user = User::where('id', $item)->where('organization_id', Auth::user()->organization_id)->first();
                        if ($user) {
                            TeamMember::create([
                                'team_id' => $team->id,
                                'user_id' => $item,
                            ]);
                        }
                    } else {
                        TeamMember::create([
                            'team_id' => $team->id,
                            'user_id' => $item,
                        ]);
                    }
                }
            }
            //organizations
            if ($request->organizations) {
                foreach ($request->organizations as $item) {
                    if (!Auth::user()->hasRole('Programador|Administrador')) {
                        $organization = Organization::where('id', Auth::user()->organization_id)->first();
                        if ($organization) {
                            ComponentOrganization::create([
                                'team_id' => $team->id,
                                'organization_id' => $item,
                            ]);
                        }
                    } else {
                        ComponentOrganization::create([
                            'team_id' => $team->id,
                            'organization_id' => $item,
                        ]);
                    }
                }
            }
            return redirect()
                ->route('admin.teams.index')
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
        CheckPermission::checkAuth('Listar Equipes');
        return redirect()->route('admin.teams.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Equipes');

        $team = Team::find($id);

        if (!$team) {
            abort(403, 'Acesso não autorizado');
        }

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $users = User::select('id', 'name', 'organization_id')
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->orderBy('alias_name')->get();
        } else {
            $users = User::select('id', 'name', 'organization_id')
                ->where('organization_id', Auth::user()->organization_id)
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->where('id', Auth::user()->organization_id)
                ->orderBy('alias_name')->get();
        }

        return view('admin.teams.edit', compact('team', 'users', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamRequest $request, $id)
    {
        CheckPermission::checkAuth('Editar Equipes');

        $team = Team::find($id);

        if (!$team) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        $user_id = Auth::user()->id;
        $data['editor'] = $user_id;


        if ($team->update($data)) {
            //users
            if ($request->users) {
                TeamMember::where('team_id', $team->id)->delete();
                foreach ($request->users as $item) {
                    if (!Auth::user()->hasRole('Programador|Administrador')) {
                        $user = User::where('id', $item)->where('organization_id', Auth::user()->organization_id)->first();
                        if ($user) {
                            TeamMember::create([
                                'team_id' => $team->id,
                                'user_id' => $item,
                            ]);
                        }
                    } else {
                        TeamMember::create([
                            'team_id' => $team->id,
                            'user_id' => $item,
                        ]);
                    }
                }
            } else {
                TeamMember::where('team_id', $team->id)->delete();
            }
            //organizations
            if ($request->organizations) {
                ComponentOrganization::where('team_id', $team->id)->delete();
                foreach ($request->organizations as $item) {
                    if (!Auth::user()->hasRole('Programador|Administrador')) {
                        $organization = Organization::where('id', Auth::user()->organization_id)->first();
                        if ($organization) {
                            ComponentOrganization::create([
                                'team_id' => $team->id,
                                'organization_id' => $item,
                            ]);
                        }
                    } else {
                        ComponentOrganization::create([
                            'team_id' => $team->id,
                            'organization_id' => $item,
                        ]);
                    }
                }
            } else {
                ComponentOrganization::where('team_id', $team->id)->delete();
            }
            return redirect()
                ->route('admin.teams.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
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
        CheckPermission::checkAuth('Excluir Equipes');
        $team = Team::find($id);

        if (!$team) {
            abort(403, 'Acesso não autorizado');
        }

        if ($team->delete()) {
            $team->editor = Auth::user()->id;
            $team->update();

            return redirect()
                ->route('admin.teams.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
