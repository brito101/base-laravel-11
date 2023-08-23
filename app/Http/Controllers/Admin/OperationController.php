<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperationRequest;
use App\Models\ComponentOrganization;
use App\Models\Operation;
use App\Models\OperationHistory;
use App\Models\OperationStep;
use App\Models\OperationTeam;
use App\Models\Organization;
use App\Models\Step;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Views\Operation as ViewsOperation;
use App\Models\Views\Team as ViewsTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Auth;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Operações');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $operations = ViewsOperation::get();
        } else {
            $teamsUser = TeamMember::where('user_id', Auth::user()->id)->pluck('team_id');
            $operationTeams = OperationTeam::whereIn('team_id', $teamsUser)->pluck('operation_id');
            $operations = ViewsOperation::whereIn('id', $operationTeams)->get();
        }

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($operations)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-warning mx-1 shadow" title="Kanban" href="operations/kanban/' . $row->id . '"><i class="fa fa-lg fa-fw fa-square"></i></a>' . '<a class="btn btn-xs btn-dark mx-1 shadow" title="Timeline" href="operations/timeline/' . $row->id . '"><i class="fa fa-lg fa-fw fa-clock"></i></a>' . '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="operations/' . $row->id . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>' .
                        (Auth::user()->hasPermissionTo('Editar Operações') ? '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="operations/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' : '') .
                        (Auth::user()->hasPermissionTo('Excluir Operações') ? '<form method="POST" action="operations/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta operação?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>' : '');
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.operations.index');
    }

    public function ongoing(Request $request)
    {
        CheckPermission::checkAuth('Listar Operações');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $operations = ViewsOperation::where('end', '>=', date('Y-m-d H:i:s'))->orWhere('end', NULL)->get();
        } else {
            $teamsUser = TeamMember::where('user_id', Auth::user()->id)->pluck('team_id');
            $operationTeams = OperationTeam::whereIn('team_id', $teamsUser)->pluck('operation_id');

            $operations = ViewsOperation::whereIn('id', $operationTeams)->where(function ($query) {
                $query->where('end', '>=', date('Y-m-d H:i:s'))->orWhere('end', NULL);
            })->get();
        }

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($operations)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-warning mx-1 shadow" title="Kanban" href="kanban/' . $row->id . '"><i class="fa fa-lg fa-fw fa-square"></i></a>' . '<a class="btn btn-xs btn-dark mx-1 shadow" title="Timeline" href="timeline/' . $row->id . '"><i class="fa fa-lg fa-fw fa-clock"></i></a>' . '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="' . $row->id . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>' .
                        (Auth::user()->hasPermissionTo('Editar Operações') ? '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' : '') .
                        (Auth::user()->hasPermissionTo('Excluir Operações') ? '<form method="POST" action="' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta operação?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>' : '');
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.operations.ongoing');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Operações');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $users = User::select('id', 'name', 'organization_id')
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->orderBy('alias_name')->get();
            $teams = ViewsTeam::get();
        } else {
            $users = User::select('id', 'name', 'organization_id')
                ->where('organization_id', Auth::user()->organization_id)
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->where('id', Auth::user()->organization_id)
                ->orderBy('alias_name')->get();

            $componentOrganizations = ComponentOrganization::where('organization_id', Auth::user()->organization_id)->pluck('team_id');
            $teams = ViewsTeam::whereIn('id', $componentOrganizations)->orWhere('creator', Auth::user()->id)->get();
        }

        $steps = Step::select('id', 'name')->orderBy('sequence')->get();

        return view('admin.operations.create', compact('steps', 'teams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OperationRequest $request)
    {
        CheckPermission::checkAuth('Criar Operações');

        $data = $request->all();

        // dd($request->all());

        if ($request->situation) {
            $situation = $request->situation;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($situation), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/situation';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/situation/' . $image_name));
                }
            }

            $situation = $dom->saveHTML();
            $data['situation'] = $situation;
        }

        if ($request->mission) {
            $mission = $request->mission;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($mission), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/mission';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/mission/' . $image_name));
                }
            }

            $mission = $dom->saveHTML();
            $data['mission'] = $mission;
        }

        if ($request->execution) {
            $execution = $request->execution;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($execution), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/execution';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/execution/' . $image_name));
                }
            }

            $execution = $dom->saveHTML();
            $data['execution'] = $execution;
        }

        if ($request->logistics) {
            $logistics = $request->logistics;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($logistics), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/logistics';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/logistics/' . $image_name));
                }
            }

            $logistics = $dom->saveHTML();
            $data['logistics'] = $logistics;
        }

        if ($request->instructions) {
            $instructions = $request->instructions;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($instructions), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/instructions';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/instructions/' . $image_name));
                }
            }

            $instructions = $dom->saveHTML();
            $data['instructions'] = $instructions;
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('/operations/files');
            $data['file'] = $path;
        }

        $user_id = Auth::user()->id;
        $data['creator'] = $user_id;
        $data['editor'] = $user_id;

        $operation = Operation::create($data);

        if ($operation->save()) {

            //operation steps
            if ($request->relatedSteps) {
                foreach ($request->relatedSteps as $item) {
                    OperationStep::create([
                        'operation_id' => $operation->id,
                        'step_id' => $item,
                    ]);
                }
            } else {
                OperationStep::create([
                    'operation_id' => $operation->id,
                    'step_id' => Step::orderBy('sequence')->first()->id,
                ]);
            }

            //operation teams
            if ($request->teams) {
                if (!Auth::user()->hasRole('Programador|Administrador')) {
                    $componentOrganizations = ComponentOrganization::where('organization_id', Auth::user()->organization_id)->pluck('team_id');
                    $teams = ViewsTeam::whereIn('id', $componentOrganizations)->pluck('id')->toArray();

                    foreach ($request->teams as $item) {
                        if (in_array($item, $teams)) {
                            OperationTeam::create([
                                'operation_id' => $operation->id,
                                'team_id' => $item,
                            ]);
                        }
                    }
                } else {
                    foreach ($request->teams as $item) {
                        OperationTeam::create([
                            'operation_id' => $operation->id,
                            'team_id' => $item,
                        ]);
                    }
                }
            }

            return redirect()
                ->route('admin.operations.index')
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
        CheckPermission::checkAuth('Acessar Operações');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $operation = Operation::with(['operationActions', 'operationTeams'])->find($id);
        } else {
            $teamsUser = TeamMember::where('user_id', Auth::user()->id)->pluck('team_id');
            $operationTeams = OperationTeam::whereIn('team_id', $teamsUser)->pluck('operation_id');
            $operation = Operation::whereIn('id', $operationTeams)->where('id', $id)->first();
        }

        if (!$operation) {
            abort(403, 'Acesso não autorizado');
        }

        $teams = Team::select('id', 'name')->get();

        $operationTeams = [];
        foreach ($teams as $team) {
            if (in_array($team->id, $operation->operationTeams->pluck('team_id')->toArray())) {
                $operationTeams[] = $team->name;
            }
        }

        $histories = OperationHistory::where('operation_id', $id)->get();
        return view('admin.operations.show', compact('operation', 'histories', 'operationTeams'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Operações');

        $operation = Operation::find($id);

        if (!$operation) {
            abort(403, 'Acesso não autorizado');
        }

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $users = User::select('id', 'name', 'organization_id')
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->orderBy('alias_name')->get();
            $teams = ViewsTeam::get();
        } else {
            $users = User::select('id', 'name', 'organization_id')
                ->where('organization_id', Auth::user()->organization_id)
                ->orderBy('name')->get();
            $organizations = Organization::select('id', 'alias_name', 'code')
                ->where('id', Auth::user()->organization_id)
                ->orderBy('alias_name')->get();

            $componentOrganizations = ComponentOrganization::where('organization_id', Auth::user()->organization_id)->pluck('team_id');
            $teams = ViewsTeam::whereIn('id', $componentOrganizations)->orWhere('creator', Auth::user()->id)->get();
        }

        $steps = Step::select('id', 'name')->orderBy('sequence')->get();

        return view('admin.operations.edit', compact('operation', 'steps', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OperationRequest $request, $id)
    {
        CheckPermission::checkAuth('Editar Operações');
        $operation = Operation::find($id);

        if (!$operation) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->situation) {
            $situation = $request->situation;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($situation), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/situation';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/situation/' . $image_name));
                }
            }

            $situation = $dom->saveHTML();
            $data['situation'] = $situation;
        } else {
            $data['situation'] = null;
        }

        if ($request->mission) {
            $mission = $request->mission;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($mission), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/mission';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/mission/' . $image_name));
                }
            }

            $mission = $dom->saveHTML();
            $data['mission'] = $mission;
        } else {
            $data['mission'] = null;
        }

        if ($request->execution) {
            $execution = $request->execution;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($execution), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/execution';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/execution/' . $image_name));
                }
            }

            $execution = $dom->saveHTML();
            $data['execution'] = $execution;
        } else {
            $data['execution'] = null;
        }

        if ($request->logistics) {
            $logistics = $request->logistics;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($logistics), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/logistics';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/logistics/' . $image_name));
                }
            }

            $logistics = $dom->saveHTML();
            $data['logistics'] = $logistics;
        } else {
            $data['logistics'] = null;
        }

        if ($request->instructions) {
            $instructions = $request->instructions;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($instructions), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';
                    $destinationPath = storage_path() . '/app/public/operations/instructions';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/operations/instructions/' . $image_name));
                }
            }

            $instructions = $dom->saveHTML();
            $data['instructions'] = $instructions;
        } else {
            $data['instructions'] = null;
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file('file')->store('/operations/files');
            $data['file'] = $path;
        }

        $user_id = Auth::user()->id;
        $data['editor'] = $user_id;

        if ($operation->update($data)) {

            //operation steps
            if ($request->relatedSteps) {
                $operation->operationSteps()->delete();
                foreach ($request->relatedSteps as $item) {
                    OperationStep::create([
                        'operation_id' => $operation->id,
                        'step_id' => $item,
                    ]);
                }
            }

            //operation teams
            if ($request->teams) {
                $operation->operationTeams()->delete();
                if (!Auth::user()->hasRole('Programador|Administrador')) {
                    $componentOrganizations = ComponentOrganization::where('organization_id', Auth::user()->organization_id)->pluck('team_id');
                    $teams = ViewsTeam::whereIn('id', $componentOrganizations)->pluck('id')->toArray();

                    foreach ($request->teams as $item) {
                        if (in_array($item, $teams)) {
                            OperationTeam::create([
                                'operation_id' => $operation->id,
                                'team_id' => $item,
                            ]);
                        }
                    }
                } else {
                    foreach ($request->teams as $item) {
                        OperationTeam::create([
                            'operation_id' => $operation->id,
                            'team_id' => $item,
                        ]);
                    }
                }
            }

            return redirect()
                ->route('admin.operations.index')
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
        CheckPermission::checkAuth('Excluir Operações');
        $operation = Operation::find($id);

        if (!$operation) {
            abort(403, 'Acesso não autorizado');
        }

        if ($operation->delete()) {
            $operation->editor = Auth::user()->id;
            $operation->update();

            return redirect()
                ->route('admin.operations.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function fileDelete(Request $request)
    {
        CheckPermission::checkAuth('Editar Operações');

        $operation = Operation::find($request->id);
        if ($operation) {
            $operation->file = null;
            $operation->update();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'fail']);
        }
    }

    public function timeline($id)
    {
        CheckPermission::checkAuth('Listar Operações');

        $role = Auth::user()->roles->first()->name;

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $operation = Operation::find($id);
        } else {
            $teamsUser = TeamMember::where('user_id', Auth::user()->id)->pluck('team_id');
            $operationTeams = OperationTeam::whereIn('team_id', $teamsUser)->pluck('operation_id');
            $operation = Operation::whereIn('id', $operationTeams)->where('id', $id)->first();
        }

        if (!$operation) {
            abort(403, 'Acesso não autorizado');
        }

        $histories = OperationHistory::where('operation_id', $operation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.operations.history', compact('histories', 'operation'));
    }
}
