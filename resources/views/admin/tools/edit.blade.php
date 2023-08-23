@extends('adminlte::page')
@section('plugins.Summernote', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSelect', true)

@section('title', '- Edição de Ferramenta')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-virus"></i> Editar Ferramenta #{{ $tool->id }}: {{ $tool->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tools.index') }}">Ferramentas</a></li>
                        <li class="breadcrumb-item active">Editar Ferramenta</li>
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

                        <form method="POST" action="{{ route('admin.tools.update', ['tool' => $tool->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Nome da Ferramenta</label>
                                        <input type="text" class="form-control" id="name" placeholder="Nome"
                                            name="name" value="{{ old('name') ?? $tool->name }}" required>
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
                                                <option value="{{ $step->id }}"
                                                    {{ in_array($step->id, $tool->relatedSteps->pluck('step_id')->toArray()) ? 'selected' : '' }}>
                                                    {{ $step->name }}</option>
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
                                                value="{{ old('tags') }}">{{ $tool->tags->pluck('text')->implode(', ') }}</textarea>
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
                                            {!! old('description') ?? $tool->description !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                @if ($tool->observations->count() > 0)
                                    <div class="d-flex flex-wrap justify-content-start" id="observation"
                                        data-observation-qtd="{{ $tool->observations->count() - 1 }}">
                                        @foreach ($tool->observations as $item)
                                            <label for="observation_{{ $loop->index }}">{{ $loop->index + 1 }} -
                                                Observações
                                                úteis</label>
                                            <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start"
                                                id="container_observation_{{ $loop->index }}">
                                                <div class="col-12 px-0">
                                                    <textarea class="form-control" id="observation_{{ $loop->index }}"
                                                        placeholder="Observação útil sobre a ferramenta ou processo de execução" name="observation_{{ $loop->index }}"
                                                        rows="2">{{ old('observation_' . $loop->index) ?? $item->observation }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
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
                                @endif

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

                                @if ($tool->images->count() > 0)
                                    <label>Imagens</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                        @foreach ($tool->images as $image)
                                            <div class="col-12 col-md-3 p-2 card" data-image="{{ $image->id }}">
                                                <div class="card-body d-flex justify-content-center align-items-center">
                                                    <img class="img-fluid" src="{{ asset('storage/' . $image->image) }}"
                                                        alt="">
                                                </div>
                                                <div class="card-footer d-flex justify-content-center">
                                                    <button class="btn btn-sm btn-danger image-delete"
                                                        data-id={{ $image->id }}
                                                        data-action="{{ route('admin.tools-image-delete', ['id' => $image->id]) }}"><i
                                                            class="fa fa-trash"></i>Excluir</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

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

                                @if ($tool->files->count() > 0)
                                    <label>Arquivos</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                        @foreach ($tool->files as $file)
                                            @if (substr($file->file, -3) == 'pdf')
                                                <div class="col-12 p-2 card" data-file={{ $file->id }}>
                                                    <div class="card-body">
                                                        <embed src="{{ url('storage/' . $file->file) }}"
                                                            type="application/pdf" width="100%" height="500px">
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-center">
                                                        <button class="btn btn-sm btn-danger file-delete"
                                                            data-id={{ $file->id }}
                                                            data-action="{{ route('admin.tools-file-delete', ['id' => $file->id]) }}"><i
                                                                class="fa fa-trash"></i>Excluir</button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-12 col-md-2 p-2 card border border-secondary"
                                                    data-file={{ $file->id }}>
                                                    <div class="card-header text-center">{{ $file->name }}</div>
                                                    <div class="card-body">
                                                        <a class="btn w-100 btn-secondary mx-1 shadow" title="Anexo"
                                                            download="{{ $file->name }}"
                                                            href="{{ url('storage/' . $file->file) }}"><i
                                                                class="fa fa-lg fa-fw fa-download"></i></a>
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-center">
                                                        <button class="btn btn-sm btn-danger file-delete"
                                                            data-id={{ $file->id }}
                                                            data-action="{{ route('admin.tools-file-delete', ['id' => $file->id]) }}"><i
                                                                class="fa fa-trash"></i>Excluir</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
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
    <script src={{ asset('js/tools-observations.js') }}></script>
    <script src={{ asset('js/tools-files.js') }}></script>
@endsection
