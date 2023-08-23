@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.BsCustomFileInput', true)

@section('title', '- Kanban')
@section('meta_tags')
    <meta name="ajaxCall" content="{{ route('admin.kanban.update.actions', ['id' => $operation->id]) }}">
@endsection

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-square"></i> Kanban - {{ $operation->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.operations.index') }}">Operações</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.operations.show', ['operation' => $operation->id]) }}">Visualizar
                                Operação</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.operations.timeline', ['id' => $operation->id]) }}">Timeline</a>
                        </li>
                        <li class="breadcrumb-item active">Kanban</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-0 px-md-2">
        @include('components.alert')
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="row d-flex flex-nowrap px-2 h-100 pt-2 w-100" style="overflow-x: auto" id="kanban"
                    data-action={{ route('admin.kanban.update', ['id' => $operation->id]) }}>

                    @foreach ($operation->operationSteps as $item)
                        <div class="col-12 col-md-3 p-2">
                            <div class="card card-row" style="background-color: {{ $item->step->color }}">
                                <div class="card-header bg-dark" style="border: 5px solid {{ $item->step->color }}">
                                    <h3 class="card-title">{{ $item->step->name }}</h3>
                                </div>
                                <div class="card-body draggable-area" data-area="{{ $item->step->id }}">
                                    @if ($operation->step->name == $item->step->name)
                                        @include('admin.kanban.components.card', [
                                            'kanban' => $operation,
                                        ])
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="col-12 my-5">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#actions" data-toggle="tab">Ações
                                Realizadas</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#activities" data-toggle="tab">Nova Ação</a></li>
                    </ul>
                </div>
                <div class="card-body" style="max-height:80vh; overflow-y: auto">
                    <div class="tab-content">
                        <div class="tab-pane active" id="actions">

                            @foreach ($operation->operationActions as $action)
                                <div class="post">
                                    <div class="user-block">
                                        <img src="{{ $action->user->photo ? url('storage/users/' . $action->user->photo) : asset('vendor/adminlte/dist/img/avatar.png') }}"
                                            class="img-circle img-bordered-sm"
                                            style="width: 33.6px; height: 33.6px; object-fit: cover;"
                                            alt="{{ $action->user->name }}">
                                        <span class="float-right btn btn-danger actionTrash"
                                            data-action="{{ route('admin.kanban.delete.actions', ['id' => $action->id]) }}">
                                            <i class="fa fa-trash"></i></span>
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
                                        <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                            <div class="col-12 p-2 card">
                                                <div class="card-body d-flex justify-content-center align-items-center">
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

                        <div class="tab-pane" id="activities">
                            <form class="form-horizontal" method="POST"
                                action="{{ route('admin.kanban.store.action', ['id' => $operation->id]) }}"
                                enctype="multipart/form-data" id="actionPost">
                                @csrf
                                <div class="form-group row">
                                    <label for="text" class="col-sm-2 col-form-label">Ação:</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="text" placeholder="Escravo o texto relativo a ação..."
                                            name="text"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="text" class="col-sm-2 col-form-label">Imagem:</label>
                                    <div class="col-sm-10">
                                        <x-adminlte-input-file id="images" name="image"
                                            placeholder="Escolha uma imagem" igroup-size="md" legend="Selecione" multiple>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text text-primary">
                                                    <i class="fas fa-file-upload"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input-file>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>

                </div>
            </div>

        </div>

        <div class="card mx-2">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                    <h3 class="card-title align-self-center">Ferramentas Cadastradas</h3>
                    @can('Criar Ferramentas')
                        <a href="{{ route('admin.tools.create') }}" target="_blank" title="Nova Ferramenta"
                            class="btn btn-success"><i class="fas fa-fw fa-plus"></i>Nova Ferramenta</a>
                    @endcan
                </div>
            </div>

            @php
                $heads = [['label' => 'ID', 'width' => 5], 'Nome', 'Descrição', 'Tags', ['label' => 'Ações', 'no-export' => true, 'width' => 10]];
                $config = [
                    'order' => [[1, 'asc']],
                    'ajax' => url('/admin/tools'),
                    'columns' => [['data' => 'id', 'name' => 'id'], ['data' => 'name', 'name' => 'name'], ['data' => 'description', 'name' => 'description'], ['data' => 'tags', 'name' => 'tags'], ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]],
                    'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                    'autoFill' => true,
                    'processing' => true,
                    'serverSide' => true,
                    'responsive' => true,
                    'pageLength' => 50,
                    'lengthMenu' => [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, 'Tudo']],
                    'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                    'buttons' => [
                        ['extend' => 'pageLength', 'className' => 'btn-default'],
                        ['extend' => 'copy', 'className' => 'btn-default', 'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>', 'titleAttr' => 'Copiar', 'exportOptions' => ['columns' => ':not([dt-no-export])']],
                        ['extend' => 'print', 'className' => 'btn-default', 'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>', 'titleAttr' => 'Imprimir', 'exportOptions' => ['columns' => ':not([dt-no-export])']],
                        ['extend' => 'csv', 'className' => 'btn-default', 'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>', 'titleAttr' => 'Exportar para CSV', 'exportOptions' => ['columns' => ':not([dt-no-export])']],
                        ['extend' => 'excel', 'className' => 'btn-default', 'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>', 'titleAttr' => 'Exportar para Excel', 'exportOptions' => ['columns' => ':not([dt-no-export])']],
                        ['extend' => 'pdf', 'className' => 'btn-default', 'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>', 'titleAttr' => 'Exportar para PDF', 'exportOptions' => ['columns' => ':not([dt-no-export])']],
                    ],
                ];
            @endphp

            <div class="card-body">
                <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config" striped
                    hoverable beautify />
            </div>
    </section>

@endsection

@section('custom_js')
    <script src={{ asset('js/kanban.js') }}></script>
    <script src={{ asset('js/kanbanActions.js') }}></script>
@endsection
