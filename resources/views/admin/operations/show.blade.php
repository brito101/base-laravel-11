@extends('adminlte::page')

@section('title', '- Informações de Operação')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-bullseye"></i> Operação #{{ $operation->id }} - {{ $operation->title }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Operações')
                            <li class="breadcrumb-item"><a href="{{ route('admin.operations.index') }}">Operações</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Informações de Operação</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Dados Cadastrais da Operação</h3>
                        </div>

                        <div class="card-body">

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-7 form-group px-0 pr-md-2">
                                    <label for="title">Título da Operação</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ $operation->title }}" disabled>
                                </div>

                                <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                    <label for="reference">Referência</label>
                                    <input type="text" class="form-control" id="reference" name="reference"
                                        value="{{ $operation->reference }}" disabled>
                                </div>

                                <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                    <label for="code">Código</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        value="{{ $operation->code }}" disabled>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-start">
                                <div class="col-12 col-md-3 form-group px-0 pr-md-2 mb-0">
                                    <label for="type">Tipo da Operação</label>
                                    <input type="text" class="form-control" id="type" name="type"
                                        value="{{ $operation->type }}" disabled>
                                </div>

                                <div class="col-12 col-md-3 form-group px-0 px-md-2 mb-0">
                                    <label for="classification">Classificação</label>
                                    <input type="text" class="form-control" id="classification" name="classification"
                                        value="{{ $operation->classification }}" disabled>
                                </div>

                                <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                    <label for="start">Início</label>
                                    <input type="text" class="form-control" id="start" name="start"
                                        value="{{ $operation->start ? date('d/m/Y H:i', strtotime($operation->start)) : 'Indeterminado' }}"
                                        disabled>
                                </div>

                                <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                    <label for="end">Fim</label>
                                    <input type="text" class="form-control" id="end" name="end"
                                        value="{{ $operation->end ? date('d/m/Y H:i', strtotime($operation->end)) : 'Indeterminado' }}"
                                        disabled>
                                </div>

                                <div class="col-12 col-md-2 form-group px-0 pl-md-2 mb-0">
                                    <label for="spindle">Fuso</label>
                                    <input type="text" class="form-control" id="spindle" name="spindle"
                                        value="{{ $operation->spindle }}" disabled>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-end">
                                <div class="col-12 col-md-3 form-group px-0 pl-md-2 mb-0" style="position: relative">
                                    <label for="step_id">Fase Atual</label>
                                    <input type="text" class="form-control" id="step_id" name="step_id"
                                        value="{{ $operation->step->name }}" disabled>
                                    <i style="color: {{ $operation->step->color }}; position: absolute; top: 50%; right: 1.5%;"
                                        class="fa fa-square fa-2x"></i>
                                </div>
                            </div>

                            <h4 class="h6 font-weight-bold">Fases da Operação</h4>
                            <div class="d-flex flex-wrap justify-content-start px-0 mx-n2">
                                @foreach ($operation->operationSteps as $item)
                                    <div class="p-2">
                                        <span title="{{ $item->step->name }}" class="btn"
                                            style="background-color: {{ $item->step->color }}; cursor: unset;">#{{ $item->step->sequence }}:
                                            {{ $item->step->name }}
                                            {!! in_array($item->step->id, $histories->pluck('step_id')->toArray()) ? '<i class="fa fa-check"></i>' : '' !!}</span>
                                    </div>
                                @endforeach
                            </div>

                            @if ($operation->situation)
                                <div class="col-12 form-group px-0">
                                    <label for="situation">Situação</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="situation">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {!! $operation->situation !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($operation->mission)
                                <div class="col-12 form-group px-0">
                                    <label for="mission">Missão</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="situation">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {!! $operation->mission !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($operation->execution)
                                <div class="col-12 form-group px-0">
                                    <label for="execution">Execução</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="situation">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {!! $operation->execution !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($operation->logistics)
                                <div class="col-12 form-group px-0">
                                    <label for="logistics">Administração e Logísitica</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="situation">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {!! $operation->logistics !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($operation->instructions)
                                <div class="col-12 form-group px-0">
                                    <label for="instructions">Comando e Controle</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="instructions">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {!! $operation->instructions !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($operationTeams) > 0)
                                <div class="col-12 form-group px-0">
                                    <label for="operationTeams">Times Participantes</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="operationTeams">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {{ join(' e ', array_filter(array_merge([join(', ', array_slice($operationTeams, 0, -1))], array_slice($operationTeams, -1)), 'strlen')) }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($operation->operationActions) > 0)
                                <div class="col-12 form-group px-0">
                                    <label for="instructions">Ações Realizadas</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center border rounded border-dark p-2"
                                        id="operationActions">
                                        @foreach ($operation->operationActions->reverse() as $action)
                                            <div class="post col-12 py-2">
                                                <div class="user-block">
                                                    <img src="{{ $action->user->photo ? url('storage/users/' . $action->user->photo) : asset('vendor/adminlte/dist/img/avatar.png') }}"
                                                        class="img-circle img-bordered-sm"
                                                        style="width: 33.6px; height: 33.6px; object-fit: cover;"
                                                        alt="{{ $action->user->name }}">
                                                    <span class="username">
                                                        <span>{{ $action->user->name }}</span>
                                                    </span>
                                                    <span class="description">Enviado em
                                                        {{ date('d/m/Y H:i:s', strtotime($action->created_at)) }}</span>
                                                </div>

                                                <p>
                                                    {{ $action->text }}
                                                </p>

                                                @if ($action->image)
                                                    <div
                                                        class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                                        <div class="col-12 p-2 card">
                                                            <div
                                                                class="card-body d-flex justify-content-center align-items-center">
                                                                <img class="img-fluid"
                                                                    src="{{ asset('storage/operations/actions/' . $action->image) }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex flex-wrap justify-content-between">

                                @if ($operation->file)
                                    <h4 class="h6 font-weight-bold">Anexo</h4>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center">
                                        @if (substr($operation->file, -3) == 'pdf')
                                            <div class="col-12 p-2 card" data-file={{ $operation->id }}>
                                                <div class="card-body">
                                                    <embed src="{{ url('storage/' . $operation->file) }}"
                                                        type="application/pdf" width="100%" height="500px">
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
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
