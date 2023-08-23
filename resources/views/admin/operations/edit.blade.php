@extends('adminlte::page')
@section('plugins.Summernote', true)
@section('plugins.select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSelect', true)

@section('title', '- Edição de Operação')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-bullseye"></i> Editar Operação #{{ $operation->id }} - {{ $operation->title }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Usuários')
                            <li class="breadcrumb-item"><a href="{{ route('admin.operations.index') }}">Operações</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Editar Operação</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @include('components.alert')

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dados Cadastrais da Operação</h3>
                        </div>

                        <form method="POST"
                            action="{{ route('admin.operations.update', ['operation' => $operation->id]) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-7 form-group px-0 pr-md-2">
                                        <label for="title">Título da Operação</label>
                                        <input type="text" class="form-control" id="title" placeholder="Título"
                                            name="title" value="{{ old('title') ?? $operation->title }}" required>
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                        <label for="reference">Referência</label>
                                        <input type="text" class="form-control" id="reference"
                                            placeholder="Doc. Referência" name="reference"
                                            value="{{ old('reference') ?? $operation->reference }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label for="code">Código</label>
                                        <input type="text" class="form-control" id="code" placeholder="Código"
                                            name="code" value="{{ old('code') ?? $operation->code }}">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2 mb-0">
                                        <label for="type">Tipo da Operação</label>
                                        <x-adminlte-select2 name="type">
                                            <option
                                                {{ old('type') == '' ? 'selected' : ($operation->type == '' ? 'selected' : '') }}
                                                value="">Não informado
                                            </option>
                                            <option
                                                {{ old('type') == 'Exploratória' ? 'selected' : ($operation->type == 'Exploratória' ? 'selected' : '') }}
                                                value="Exploratória">
                                                Exploratória
                                            </option>
                                            <option
                                                {{ old('type') == 'Sistemática' ? 'selected' : ($operation->type == 'Sistemática' ? 'selected' : '') }}
                                                value="Sistemática">
                                                Sistemática</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 px-md-2 mb-0">
                                        <label for="classification">Classificação</label>
                                        <x-adminlte-select2 name="classification">
                                            <option
                                                {{ old('classification') == '' ? 'selected' : ($operation->classification == '' ? 'selected' : '') }}
                                                value="">Não
                                                informado
                                            </option>
                                            <option
                                                {{ old('classification') == 'Proteção' ? 'selected' : ($operation->classification == 'Proteção' ? 'selected' : '') }}
                                                value="Proteção">
                                                Proteção
                                            </option>
                                            <option
                                                {{ old('classification') == 'Exploração' ? 'selected' : ($operation->classification == 'Exploração' ? 'selected' : '') }}
                                                value="Exploração">
                                                Exploração</option>
                                            <option
                                                {{ old('classification') == 'Ataque' ? 'selected' : ($operation->classification == 'Ataque' ? 'selected' : '') }}
                                                value="Ataque">
                                                Ataque</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="start">Início</label>
                                        <input type="datetime-local" class="form-control date" id="start" placeholder=""
                                            name="start" value="{{ old('start') ?? $operation->start }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="end">Fim</label>
                                        <input type="datetime-local" class="form-control date" id="end" placeholder=""
                                            name="end" value="{{ old('end') ?? $operation->end }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2 mb-0">
                                        <label for="spindle">Fuso</label>
                                        <x-adminlte-select2 name="spindle" required>
                                            @php
                                                $spindles = ['Y', 'X', 'W', 'V', 'U', 'T', 'S', 'R', 'Q', 'P', 'O', 'N', 'Z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'K', 'L', 'M'];
                                            @endphp
                                            @foreach ($spindles as $spindle)
                                                <option
                                                    {{ old('spindle') == $spindle ? 'selected' : ($operation->spindle == $spindle ? 'selected' : (old('spindle') == '' && $spindle == 'P' ? 'selected' : '')) }}
                                                    value="{{ $spindle }}">{{ $spindle }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-end">
                                    <div class="col-12 col-md-3 form-group px-0 pl-md-2 mb-0">
                                        <label for="step_id">Fase Atual</label>
                                        <x-adminlte-select2 name="step_id">
                                            @foreach ($operation->operationSteps as $item)
                                                <option
                                                    {{ old('step_id') == $item->step_id ? 'selected' : ($operation->step_id == $item->step_id ? 'selected' : '') }}
                                                    value="{{ $item->step_id }}">
                                                    {{ $item->step->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>
                                </div>

                                @php
                                    $config = [
                                        'height' => '100',
                                        'toolbar' => [
                                            // [groupName, [list of button]]
                                            ['style', ['style']],
                                            ['font', ['bold', 'underline', 'clear']],
                                            ['fontsize', ['fontsize']],
                                            ['fontname', ['fontname']],
                                            ['color', ['color']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['height', ['height']],
                                            ['table', ['table']],
                                            ['insert', ['link', 'picture', 'video']],
                                            ['view', ['fullscreen', 'codeview', 'help']],
                                        ],
                                    ];
                                @endphp
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="situation" label="Situação" label-class="text-black"
                                            igroup-size="md" placeholder="Situação da operação..." :config="$config">
                                            {!! old('situation') ?? $operation->situation !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="mission" label="Missão" label-class="text-black"
                                            igroup-size="md" placeholder="Descrição da Missão..." :config="$config">
                                            {!! old('mission') ?? $operation->mission !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="execution" label="Execução"
                                            label-class="text-black" igroup-size="md"
                                            placeholder="Instruçẽos de execução..." :config="$config">
                                            {!! old('execution') ?? $operation->execution !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="logistics" label="Administração e Logísitica"
                                            label-class="text-black" igroup-size="md"
                                            placeholder="Dados de administração e logísitica..." :config="$config">
                                            {!! old('logistics') ?? $operation->logistics !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="instructions" label="Comando e Controle"
                                            label-class="text-black" igroup-size="md" placeholder="Dados de C2.."
                                            :config="$config">
                                            {!! old('instructions') ?? $operation->instructions !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">

                                    @php
                                        $config = [
                                            'title' => 'Selecione múltiplas opções',
                                            'showTick' => true,
                                            'actionsBox' => true,
                                        ];
                                    @endphp
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2 mb-0">
                                        <x-adminlte-select-bs id="relatedSteps" name="relatedSteps[]"
                                            label="Fases Relacionadas" igroup-size="md" :config="$config" multiple>
                                            @foreach ($steps as $step)
                                                <option value="{{ $step->id }}"
                                                    {{ in_array($step->id, $operation->operationSteps->pluck('step_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $step->name }}</option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                        <x-adminlte-select-bs id="teams" name="teams[]" label="Times Participantes"
                                            igroup-size="md" :config="$config" multiple>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}"
                                                    {{ in_array($team->id, $operation->operationTeams->pluck('team_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $team->name }}</option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2 mb-0">
                                        <x-adminlte-input-file name="file" label="Anexo"
                                            placeholder="Selecione um arquivo..." />
                                    </div>

                                    @if ($operation->file)
                                        <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                            @if (substr($operation->file, -3) == 'pdf')
                                                <div class="col-12 p-2 card" data-file={{ $operation->id }}>
                                                    <div class="card-body">
                                                        <embed src="{{ url('storage/' . $operation->file) }}"
                                                            type="application/pdf" width="100%" height="500px">
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-center">
                                                        <button class="btn btn-sm btn-danger file-delete"
                                                            data-id={{ $operation->id }}
                                                            data-action="{{ route('admin.operations-file-delete', ['id' => $operation->id]) }}"><i
                                                                class="fa fa-trash"></i>Excluir</button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-12 col-md-2 p-2 card border border-secondary"
                                                    data-file={{ $operation->id }}>
                                                    <div class="card-header text-center">{{ $operation->title }}</div>
                                                    <div class="card-body">
                                                        <a class="btn w-100 btn-secondary mx-1 shadow" title="Anexo"
                                                            download="{{ $operation->file }}"
                                                            href="{{ url('storage/' . $operation->file) }}"><i
                                                                class="fa fa-lg fa-fw fa-download"></i></a>
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-center">
                                                        <button class="btn btn-sm btn-danger file-delete"
                                                            data-id={{ $operation->id }}
                                                            data-action="{{ route('admin.operations-file-delete', ['id' => $operation->id]) }}"><i
                                                                class="fa fa-trash"></i>Excluir</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script src={{ asset('js/operations-files.js') }}></script>
@endsection
