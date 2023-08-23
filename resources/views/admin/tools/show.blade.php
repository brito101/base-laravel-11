@extends('adminlte::page')
@section('plugins.Summernote', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSelect', true)

@section('title', '- Ferramenta')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-virus"></i> Ferramenta #{{ $tool->id }}: {{ $tool->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tools.index') }}">Ferramentas</a></li>
                        <li class="breadcrumb-item active">Ferramenta {{ $tool->name }}</li>
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


                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                    <label for="name">Nome da Ferramenta</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $tool->name }}" disabled>
                                </div>

                                <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                    <label for="name">Fases Relacionadas</label>

                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $tool->relatedStepsName() }}" disabled>
                                </div>
                            </div>
                            <div class="col-12 form-group px-0" id="tags">
                                <label for="tags">Tags</label>
                                <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start"
                                    id="container_observation_${item}">
                                    <div class="col-12 px-0">
                                        <textarea type="text" class="form-control" id="tags" name="tags" disabled>{{ $tool->tags->pluck('text')->implode(', ') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <h4 class="h6 font-weight-bold">Descrição da Ferramenta</h4>
                            <div class="d-flex flex-wrap justify-content-center mb-3">
                                <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                    <p>{!! $tool->description !!}</p>
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
                                                    rows="2" disabled>{{ $item->observation }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($tool->images->count() > 0)
                                <label>Imagens</label>
                                <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                    @foreach ($tool->images as $image)
                                        <div class="col-12 col-md-3 p-2 card" data-image="{{ $image->id }}">
                                            <div class="card-body d-flex justify-content-center align-items-center">
                                                <img class="img-fluid" src="{{ asset('storage/' . $image->image) }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($tool->files->count() > 0)
                                <label>Arquivos</label>
                                <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                    @foreach ($tool->files as $file)
                                        @if (substr($file->file, -3) == 'pdf')
                                            <div class="col-12 p-2 card" data-file={{ $file->id }}>
                                                <div class="card-body">
                                                    <embed src="{{ url('storage/' . $file->file) }}" type="application/pdf"
                                                        width="100%" height="500px">
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
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
