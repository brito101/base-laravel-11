<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportRequest;
use App\Models\Report;
use App\Models\Views\Report as ViewsReport;
use Illuminate\Http\Request;
use Image;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Relatórios');

        $reports = ViewsReport::where('user_id', Auth::user()->id)->orWhere('status', 'Publicado')->get();

        if ($request->ajax()) {

            $token = csrf_token();

            return Datatables::of($reports)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    $btn = '<a class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="reports/' . $row->id . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>' .
                        ((Auth::user()->hasPermissionTo('Editar Relatórios') &&  Auth::user()->id == $row->user_id) ?
                            '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="reports/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' : '') .
                        ((Auth::user()->hasPermissionTo('Excluir Relatórios') && Auth::user()->id == $row->user_id) ?
                            '<form method="POST" action="reports/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste relatório?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>' : '');

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.reports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Listar Relatórios');

        return view('admin.reports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        CheckPermission::checkAuth('Criar Relatórios');
        $data = $request->all();

        if ($request->description) {
            $description = $request->description;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($description), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->name) . '-' . time() . $item . '.png';

                    $destinationPath = storage_path() . '/app/public/reports';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;
                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->name);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/reports/' . $image_name));
                }
            }

            $description = $dom->saveHTML();
            $data['description'] = $description;
        }

        if ($request->file) {
            $path = Storage::putFile('reports/files', $request->file);
            $data['file'] = $path;
        }

        $data['user_id'] = Auth::user()->id;

        $report = Report::create($data);

        if ($report->save()) {
            return redirect()
                ->route('admin.reports.index')
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
        CheckPermission::checkAuth('Listar Relatórios');
        $report = Report::find($id);

        if (!$report) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report->status == 'Rascunho' && $report->user_id != Auth::user()->id) {
            abort(403, 'Acesso não autorizado');
        }

        if (Auth::user()->id != $report->user_id) {
            $report->views = $report->views + 1;
            $report->update();
        }

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Relatórios');
        $report = Report::find($id);

        if (!$report) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report->user_id != Auth::user()->id) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.reports.edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request, $id)
    {
        CheckPermission::checkAuth('Criar Relatórios');

        $report = Report::find($id);

        if (!$report) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report->user_id != Auth::user()->id) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($request->description) {
            $description = $request->description;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($description), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->name) . '-' . time() . $item . '.png';

                    $destinationPath = storage_path() . '/app/public/reports';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;
                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->name);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/reports/' . $image_name));
                }
            }

            $description = $dom->saveHTML();
            $data['description'] = $description;
        }

        if ($request->file) {
            $path = Storage::putFile('reports/files', $request->file);
            $data['file'] = $path;
        }

        if ($report->update($data)) {
            return redirect()
                ->route('admin.reports.index')
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
        CheckPermission::checkAuth('Excluir Relatórios');
        $report = Report::find($id);

        if (!$report) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report->user_id != Auth::user()->id) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report->delete()) {

            return redirect()
                ->route('admin.reports.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function fileDelete(Request $request)
    {
        CheckPermission::checkAuth('Editar Relatórios');

        $report = Report::find($request->id);

        if (!$report) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report->user_id != Auth::user()->id) {
            abort(403, 'Acesso não autorizado');
        }

        if ($report) {
            $report->file = null;
            $report->update();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'fail']);
        }
    }
}
