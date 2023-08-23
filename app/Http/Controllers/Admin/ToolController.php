<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ToolRequest;
use App\Models\RelatedStep;
use App\Models\Step;
use App\Models\Tool;
use App\Models\ToolFile;
use App\Models\ToolImage;
use App\Models\ToolObservation;
use App\Models\ToolTag;
use App\Models\Views\Step as ViewsStep;
use App\Models\Views\Tool as ViewsTool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Ferramentas');

        $tools = ViewsTool::get();

        if ($request->ajax()) {

            if ($request->server()['HTTP_REFERER'] == route('admin.tools.index')) {
                $token = csrf_token();

                return Datatables::of($tools)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) use ($token) {
                        $btn = '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="tools/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                            '<form method="POST" action="tools/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão desta ferramenta?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                        return $btn;
                    })
                    ->addColumn('description', function ($row) {
                        $text = Str::limit($row->description, 100, '...');
                        return $text;
                    })
                    ->rawColumns(['description', 'action'])
                    ->make(true);
            } else {
                return Datatables::of($tools)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a target="_blank" class="btn btn-xs btn-success mx-1 shadow" title="Visualizar" href="' . route('admin.tools.show', ['tool' => $row->id]) . '"><i class="fa fa-lg fa-fw fa-eye"></i></a>';
                        return $btn;
                    })
                    ->addColumn('description', function ($row) {
                        $text = Str::limit($row->description, 100, '...');
                        return $text;
                    })
                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
        }

        return view('admin.tools.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Ferramentas');

        $steps = ViewsStep::select('id', 'name', 'sequence')->orderBy('sequence')->get();

        return view('admin.tools.create', compact('steps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ToolRequest $request)
    {
        CheckPermission::checkAuth('Criar Ferramentas');
        $data = $request->all();

        if ($request->observations) {
            $observations = $request->observations;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($observations), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';

                    $destinationPath = storage_path() . '/app/public/observations';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;
                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/observations/' . $image_name));
                }
            }

            $observations = $dom->saveHTML();
            $data['observations'] = $observations;
        }

        $user_id = Auth::user()->id;
        $data['creator'] = $user_id;
        $data['editor'] = $user_id;

        if ($request->file('images') != null) {
            $validator = Validator::make($request->only('images'), ['images.*' => 'image']);
            if ($validator->fails() === true) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Todas as imagens devem ser do tipo jpg, jpeg ou png.');
            }
        }

        if ($request->file('files') != null) {
            $validator = Validator::make($request->only('files'), ['files.*' => 'file|max:1048576']);
            if ($validator->fails() === true) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Todos os arquivos devem ser pdf com no máximo 1GB total');
            }
        }


        $tool = Tool::create($data);

        if ($tool->save()) {
            //Images
            if ($request->file('images') != null) {
                foreach ($request->images as $image) {
                    $toolImage = new ToolImage();
                    $toolImage->tool_id = $tool->id;

                    $name = time();
                    $extension = $image->extension();
                    $nameFile = "{$name}.{$extension}";

                    $destinationPath = storage_path() . '/app/public/tools/photos/';

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $img = Image::make($image);
                    $img->save($destinationPath . '/' . $nameFile);

                    $toolImage->image = 'tools/photos/' . $nameFile;
                    $toolImage->name = Str::limit($image->getClientOriginalName(), 100);
                    $toolImage->user_id = $user_id;
                    $toolImage->save();
                    unset($toolImage);
                }
            }
            //files
            if ($request->file('files') != null) {
                $files = $request->file('files');

                foreach ($files as $file) {
                    $toolFile = new ToolFile();
                    $toolFile->tool_id = $tool->id;
                    $path = Storage::putFile('tools/files', $file);
                    $toolFile->file = $path;
                    $toolFile->name = Str::limit($file->getClientOriginalName(), 100);
                    $toolFile->user_id = $user_id;
                    $toolFile->save();
                    unset($toolFile);
                }
            }
            //observations
            $observations = [];
            $oi = 0;
            foreach ($data as $key => $value) {
                if (preg_match("/observation_(\d)$/", $key)) {
                    if (strlen($value) > 0) {
                        $observations[$oi]['text'] = Str::limit($value, 400000);
                        $oi++;
                    }
                }
            }

            foreach ($observations as $observation) {
                $item = new ToolObservation();
                $item->observation = $observation['text'];
                $item->user_id = $user_id;
                $item->tool_id = $tool->id;
                $item->save();
            }

            //related steps
            if ($request->relatedSteps) {
                foreach ($request->relatedSteps as $item) {
                    RelatedStep::create([
                        'tool_id' => $tool->id,
                        'step_id' => $item,
                    ]);
                }
            }

            //tags
            if ($request->tags) {
                $tags = explode(", ", $request->tags);
                foreach ($tags as $tag) {
                    ToolTag::create([
                        'tool_id' => $tool->id,
                        'text' => $tag,
                        'user_id' => $user_id
                    ]);
                }
            }

            return redirect()
                ->route('admin.tools.index')
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
        CheckPermission::checkAuth('Acessar Ferramentas');

        $tool = Tool::find($id);

        if (!$tool) {
            abort(403, 'Acesso não autorizado');
        }

        $steps = ViewsStep::select('id', 'name', 'sequence')->orderBy('sequence')->get();

        return view('admin.tools.show', compact('tool', 'steps'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Ferramentas');
        $tool = Tool::find($id);

        if (!$tool) {
            abort(403, 'Acesso não autorizado');
        }

        $steps = ViewsStep::select('id', 'name', 'sequence')->orderBy('sequence')->get();

        return view('admin.tools.edit', compact('tool', 'steps'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ToolRequest $request, $id)
    {
        CheckPermission::checkAuth('Editar Ferramentas');
        $tool = Tool::find($id);

        if (!$tool) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();
        if ($request->observations) {
            $observations = $request->observations;
            $dom = new \DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->loadHTML(utf8_decode($observations), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
            $imageFile = $dom->getElementsByTagName('img');

            foreach ($imageFile as $item => $image) {
                $img = $image->getAttribute('src');
                if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                    list($type, $img) = explode(';', $img);
                    list(, $img) = explode(',', $img);
                    $imageData = base64_decode($img);
                    $image_name =  Str::slug($request->title) . '-' . time() . $item . '.png';

                    $destinationPath = storage_path() . '/app/public/observations';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $path = $destinationPath . '/' . $image_name;

                    file_put_contents($path, $imageData);
                    $image->removeAttribute('src');
                    $image->removeAttribute('data-filename');
                    $image->setAttribute('alt', $request->title);
                    $image->setAttribute('style', 'max-width: 100%;');
                    $image->setAttribute('src', url('storage/observations/' . $image_name));
                }
            }
            $observations = $dom->saveHTML();
            $data['observations'] = $observations;
        }

        $user_id = Auth::user()->id;
        $data['editor'] = $user_id;

        if ($request->file('images') != null) {
            $validator = Validator::make($request->only('images'), ['images.*' => 'image']);
            if ($validator->fails() === true) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Todas as imagens devem ser do tipo jpg, jpeg ou png.');
            }
        }

        if ($request->file('files') != null) {
            $validator = Validator::make($request->only('files'), ['files.*' => 'file|max:1048576']);
            if ($validator->fails() === true) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Todos os arquivos devem ser pdf com no máximo 1GB total');
            }
        }

        if ($tool->update($data)) {
            //Images
            if ($request->file('images') != null) {
                foreach ($request->images as $image) {
                    $toolImage = new ToolImage();
                    $toolImage->tool_id = $tool->id;

                    $name = time();
                    $extension = $image->extension();
                    $nameFile = "{$name}.{$extension}";

                    $destinationPath = storage_path() . '/app/public/tools/photos/';

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 755, true);
                    }

                    $img = Image::make($image);
                    $img->save($destinationPath . '/' . $nameFile);

                    $toolImage->image = 'tools/photos/' . $nameFile;
                    $toolImage->name = Str::limit($image->getClientOriginalName(), 100);
                    $toolImage->user_id = $user_id;
                    $toolImage->save();
                    unset($toolImage);
                }
            }
            //files
            if ($request->file('files') != null) {
                $files = $request->file('files');

                foreach ($files as $file) {
                    $toolFile = new ToolFile();
                    $toolFile->tool_id = $tool->id;
                    $path = Storage::putFile('tools/files', $file);
                    $toolFile->file = $path;
                    $toolFile->name = Str::limit($file->getClientOriginalName(), 100);
                    $toolFile->user_id = $user_id;
                    $toolFile->save();
                    unset($toolFile);
                }
            }
            //observations
            $observations = [];
            $oi = 0;
            foreach ($data as $key => $value) {
                if (preg_match("/observation_(\d)$/", $key)) {
                    if (strlen($value) > 0) {
                        $observations[$oi]['text'] = Str::limit($value, 400000);
                        $oi++;
                    }
                }
            }

            $tool->observations->each->delete();

            foreach ($observations as $observation) {
                $item = new ToolObservation();
                $item->observation = $observation['text'];
                $item->user_id = $user_id;
                $item->tool_id = $tool->id;
                $item->save();
            }

            //related steps
            if ($request->relatedSteps) {
                RelatedStep::where('tool_id', $tool->id)->delete();
                foreach ($request->relatedSteps as $item) {
                    RelatedStep::create([
                        'tool_id' => $tool->id,
                        'step_id' => $item,
                    ]);
                }
            } else {
                RelatedStep::where('tool_id', $tool->id)->delete();
            }

            //tags
            if ($request->tags) {
                ToolTag::where('tool_id', $tool->id)->delete();
                $tags = explode(", ", $request->tags);
                foreach ($tags as $tag) {
                    ToolTag::create([
                        'tool_id' => $tool->id,
                        'text' => $tag,
                        'user_id' => $user_id
                    ]);
                }
            } else {
                ToolTag::where('tool_id', $tool->id)->delete();
            }

            return redirect()
                ->route('admin.tools.index')
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
        CheckPermission::checkAuth('Excluir Ferramentas');
        $tool = Tool::find($id);

        if (!$tool) {
            abort(403, 'Acesso não autorizado');
        }

        if ($tool->delete()) {
            $tool->editor = Auth::user()->id;
            $tool->update();

            return redirect()
                ->route('admin.tools.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function imageDelete(Request $request)
    {
        CheckPermission::checkAuth('Editar Ferramentas');

        $image = ToolImage::find($request->id);
        if ($image) {
            $image->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'fail']);
        }
    }

    public function fileDelete(Request $request)
    {
        CheckPermission::checkAuth('Editar Ferramentas');

        $file = ToolFile::find($request->id);
        if ($file) {
            $file->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'fail']);
        }
    }
}
