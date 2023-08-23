<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperationActionRequest;
use App\Models\Operation;
use App\Models\OperationAction;
use App\Models\OperationTeam;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Tool;
use App\Models\Views\Tool as ViewsTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use DataTables;
use Illuminate\Support\Str;

class KanbanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        CheckPermission::checkAuth('Acessar Operações');

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

        return view('admin.kanban.index', compact('operation'));
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
        CheckPermission::checkAuth('Acessar Operações');

        if (!$id && !$request->area) {
            abort(403, 'Acesso não autorizado');
        }

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

        if (in_array($request->area, $operation->operationSteps->pluck('step_id')->toArray())) {
            $operation->step_id = $request->area;
            if ($operation->update()) {
                return response()->json(['message' => 'success']);
            } else {
                return response()->json(['message' => 'fail']);
            }
        } else {
            return response()->json(['message' => 'fail']);
        }
    }

    // Actions

    public function storeAction(OperationActionRequest $request, $id)
    {

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

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['operation_id'] = $id;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $name = time();
            $extension = $request->image->extension();
            $nameFile = "{$name}.{$extension}";

            $data['image'] = $nameFile;

            $destinationPath = storage_path() . '/app/public/operations/actions';

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 755, true);
            }

            $img = Image::make($request->image);
            $img->save($destinationPath . '/' . $nameFile);
        }

        $operationAction = OperationAction::create($data);

        if ($operationAction->save()) {
            return response()->json(['message' => 'Ação enviada com sucesso!']);
        } else {
            return response()->json(['message' => 'Falha ao enviar a ação! Por favor tente novamente.']);
        }
    }

    public function updateActions($id)
    {

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

        if ($operation) {
            return response()->json(['actions' => $operation->operationActions]);
        }
    }

    public function deleteAction($id)
    {

        $operationAction = OperationAction::find($id);

        if (!$operationAction) {
            abort(403, 'Acesso não autorizado');
        }

        if ($operationAction->delete()) {
            return response()->json(['message' => 'Exclusão realizada com sucesso!']);
        } else {
            return response()->json(['message' => 'Falha ao excluir! Por favor tente novamente.']);
        }
    }
}
