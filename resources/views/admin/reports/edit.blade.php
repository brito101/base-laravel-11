@extends('adminlte::page')
@section('plugins.Summernote', true)
@section('plugins.select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSelect', true)

@section('title', '- Edição de Relatório')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-file"></i> Editar Relatório #{{ $report->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Relatórios</a></li>
                        <li class="breadcrumb-item active">Editar Relatório</li>
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
                            <h3 class="card-title">Dados Cadastrais do Relatório</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.reports.update', ['report' => $report->id]) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="id" value="{{ $report->id }}">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="name">Nome da Máquina</label>
                                        <input type="text" class="form-control" id="name" placeholder="Nome"
                                            name="name" value="{{ old('name') ?? $report->name }}" required>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                        <label for="ip">IP</label>
                                        <input type="text" class="form-control" id="ip" placeholder="IP"
                                            name="ip" value="{{ old('ip') ?? $report->ip }}">
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2">
                                        <label for="system">Sistema Operacional</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Sistema Operacional" name="system"
                                            value="{{ old('system') ?? $report->system }}">
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="platform">Plataforma</label>
                                        <input type="text" class="form-control" id="platform"
                                            placeholder="HTB, TryHackMe etc" name="platform"
                                            value="{{ old('platform') ?? $report->platform }}">
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Status</label>
                                        <x-adminlte-select2 name="status">
                                            <option
                                                {{ old('status') == 'Rascunho' ? 'selected' : ($report->status == 'Rascunho' ? 'selected' : '') }}
                                                value="Rascunho">
                                                Rascunho</option>
                                            <option
                                                {{ old('status') == 'Publicado' ? 'selected' : ($report->status == 'Publicado' ? 'selected' : '') }}
                                                value="Publicado">
                                                Publicado</option>
                                        </x-adminlte-select2>
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 pl-md-2">
                                        <label for="keys">Palavras chave</label>
                                        <input type="text" class="form-control" id="keys"
                                            placeholder="Samba, RFI etc" name="keys"
                                            value="{{ old('keys') ?? $report->keys }}">
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
                                        <x-adminlte-text-editor name="description" label="Descrição"
                                            label-class="text-black" igroup-size="md" placeholder="Texto descritivo..."
                                            :config="$config">
                                            {!! old('description') ?? $report->description !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="col-12 form-group px-0 mt-2">
                                    <x-adminlte-input-file id="file" name="file" label="Relatório em PDF"
                                        placeholder="Selecione de no máximo 1GB total" igroup-size="md" legend="Selecionar">
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text text-primary">
                                                <i class="fas fa-file-upload"></i>
                                            </div>
                                        </x-slot>
                                    </x-adminlte-input-file>
                                </div>

                                @if ($report->file)
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                        <div class="col-12 p-2 card" data-file={{ $report->id }}>
                                            <div class="card-body">
                                                <embed src="{{ url('storage/' . $report->file) }}" type="application/pdf"
                                                    width="100%" height="800px">
                                            </div>
                                            <div class="card-footer d-flex justify-content-center">
                                                <button class="btn btn-sm btn-danger file-delete"
                                                    data-id={{ $report->id }}
                                                    data-action="{{ route('admin.reports-file-delete', ['id' => $report->id]) }}">
                                                    <i class="fa fa-trash"></i>Excluir</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
    <script src={{ asset('js/tools-files.js') }}></script>
@endsection
