@extends('adminlte::page')
@section('plugins.Summernote', true)
@section('plugins.select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSelect', true)

@section('title', '- Cadastro de Operação')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-bullseye"></i> Nova Operação</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Usuários')
                            <li class="breadcrumb-item"><a href="{{ route('admin.operations.index') }}">Operações</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Nova Operação</li>
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

                        <form method="POST" action="{{ route('admin.operations.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-7 form-group px-0 pr-md-2">
                                        <label for="title">Título da Operação</label>
                                        <input type="text" class="form-control" id="title" placeholder="Título"
                                            name="title" value="{{ old('title') }}" required>
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                        <label for="reference">Referência</label>
                                        <input type="text" class="form-control" id="reference"
                                            placeholder="Doc. Referência" name="reference" value="{{ old('reference') }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label for="code">Código</label>
                                        <input type="text" class="form-control" id="code" placeholder="Código"
                                            name="code" value="{{ old('code') }}">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2 mb-0">
                                        <label for="type">Tipo da Operação</label>
                                        <x-adminlte-select2 name="type">
                                            <option {{ old('type') == '' ? 'selected' : '' }} value="">Não informado
                                            </option>
                                            <option {{ old('type') == 'Exploratória' ? 'selected' : '' }}
                                                value="Exploratória">
                                                Exploratória
                                            </option>
                                            <option {{ old('type') == 'Sistemática' ? 'selected' : '' }}
                                                value="Sistemática">
                                                Sistemática</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 px-md-2 mb-0">
                                        <label for="classification">Classificação</label>
                                        <x-adminlte-select2 name="classification">
                                            <option {{ old('classification') == '' ? 'selected' : '' }} value="">Não
                                                informado
                                            </option>
                                            <option {{ old('classification') == 'Proteção' ? 'selected' : '' }}
                                                value="Proteção">
                                                Proteção
                                            </option>
                                            <option {{ old('classification') == 'Exploração' ? 'selected' : '' }}
                                                value="Exploração">
                                                Exploração</option>
                                            <option {{ old('classification') == 'Ataque' ? 'selected' : '' }}
                                                value="Ataque">
                                                Ataque</option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="start">Início</label>
                                        <input type="datetime-local" class="form-control date" id="start" placeholder=""
                                            name="start" value="{{ old('start') }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="end">Fim</label>
                                        <input type="datetime-local" class="form-control date" id="end" placeholder=""
                                            name="end" value="{{ old('end') }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2 mb-0">
                                        <label for="spindle">Fuso</label>
                                        <x-adminlte-select2 name="spindle" required>
                                            @php
                                                $spindles = ['Y', 'X', 'W', 'V', 'U', 'T', 'S', 'R', 'Q', 'P', 'O', 'N', 'Z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'K', 'L', 'M'];
                                            @endphp
                                            @foreach ($spindles as $spindle)
                                                <option
                                                    {{ old('spindle') == $spindle ? 'selected' : (old('spindle') == '' && $spindle == 'P' ? 'selected' : '') }}
                                                    value="{{ $spindle }}">{{ $spindle }}
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
                                            {!! old('situation') !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="mission" label="Missão" label-class="text-black"
                                            igroup-size="md" placeholder="Descrição da Missão..." :config="$config">
                                            {!! old('mission') !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="execution" label="Execução" label-class="text-black"
                                            igroup-size="md" placeholder="Instruçẽos de execução..." :config="$config">
                                            {!! old('execution') !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="logistics" label="Administração e Logísitica"
                                            label-class="text-black" igroup-size="md"
                                            placeholder="Dados de administração e logísitica..." :config="$config">
                                            {!! old('logistics') !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="instructions" label="Comando e Controle"
                                            label-class="text-black" igroup-size="md" placeholder="Dados de C2.."
                                            :config="$config">
                                            {!! old('instructions') !!}
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
                                                <option value="{{ $step->id }}">{{ $step->name }}</option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                        <x-adminlte-select-bs id="teams" name="teams[]" label="Times Participantes"
                                            igroup-size="md" :config="$config" multiple>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 form-group px-0 pr-md-2 mb-0">
                                    <x-adminlte-input-file name="file" label="Anexo"
                                        placeholder="Selecione um arquivo..." />
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
