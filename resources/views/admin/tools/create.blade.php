@extends('adminlte::page')
@section('plugins.Summernote', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSelect', true)

@section('title', '- Cadastro de Ferramenta')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-virus"></i> Nova Ferramenta</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tools.index') }}">Ferramentas</a></li>
                        <li class="breadcrumb-item active">Nova Ferramenta</li>
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
                            <h3 class="card-title">Dados Cadastrais da Ferramenta</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.tools.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Nome da Ferramenta</label>
                                        <input type="text" class="form-control" id="name" placeholder="Nome"
                                            name="name" value="{{ old('name') }}" required>
                                    </div>

                                    @php
                                        $config = [
                                            'title' => 'Selecione múltiplas opções',
                                            'showTick' => true,
                                            'actionsBox' => true,
                                        ];
                                    @endphp
                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                        <x-adminlte-select-bs id="relatedSteps" name="relatedSteps[]"
                                            label="Fases Relacionadas" igroup-size="md" :config="$config" multiple>
                                            @foreach ($steps as $step)
                                                <option value="{{ $step->id }}">{{ $step->name }}</option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
                                </div>

                                <div class="col-12 form-group px-0" id="tags">
                                    <label for="tags">Tags</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start"
                                        id="container_observation_${item}">
                                        <div class="col-12 px-0">
                                            <textarea type="text" class="form-control" id="tags"
                                                placeholder="Palavras chaves separadas por vírgula para busca e pesquisa da ferramenta" name="tags"
                                                value="{{ old('tags') }}"></textarea>
                                        </div>
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
                                            ['insert', ['link']],
                                            ['view', ['fullscreen', 'codeview', 'help']],
                                        ],
                                    ];
                                @endphp
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="description" label="Descrição da Ferramenta"
                                            label-class="text-black" igroup-size="md" placeholder="Texto descritivo..."
                                            :config="$config">
                                            {!! old('description') !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-start" id="observation"
                                    data-observation-qtd="0">
                                    <div class="col-12 form-group px-0" id="container_observation_0">
                                        <label for="observation_0">Observações úteis</label>
                                        <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start"
                                            id="container_observation_${item}">
                                            <div class="col-12 px-0">
                                                <textarea type="text" class="form-control" id="observation_0"
                                                    placeholder="Observação útil sobre a ferramenta ou processo de execução" name="observation_0"
                                                    value="{{ old('observation_0') }}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                        <button class="btn btn-info w-100" data-observation="open"><i
                                                class="fa fa-plus"></i>
                                            Nova Observação</button>
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 pl-md-2">
                                        <button class="btn btn-danger w-100" data-observation="close"><i
                                                class="fa fa-minus"></i>
                                            Remover Observação</button>
                                    </div>
                                </div>

                                <div class="col-12 form-group px-0 mt-2">
                                    <x-adminlte-input-file id="images" name="images[]" label="Anexar imagens"
                                        placeholder="Escolha múltiplas imagens..." igroup-size="md" legend="Selecione"
                                        multiple>
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text text-primary">
                                                <i class="fas fa-file-upload"></i>
                                            </div>
                                        </x-slot>
                                    </x-adminlte-input-file>
                                </div>

                                <div class="col-12 form-group px-0 mt-2">
                                    <x-adminlte-input-file id="files" name="files[]"
                                        label="Enviar arquivos de no máximo 1GB total"
                                        placeholder="Escolha múltiplos arquivos..." igroup-size="md" legend="Selecione"
                                        multiple>
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text text-primary">
                                                <i class="fas fa-file-upload"></i>
                                            </div>
                                        </x-slot>
                                    </x-adminlte-input-file>
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
    <script src={{ asset('js/tools-observations.js') }}></script>
@endsection
