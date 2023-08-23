@extends('adminlte::page')

@section('title', '- Relatório')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-file"></i> Relatório #{{ $report->id }}: {{ $report->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Relatórios</a></li>
                        <li class="breadcrumb-item active">Relatório #{{ $report->id }}</li>
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

                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                    <label for="name">Nome da Máquina</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $report->name }}" disabled>
                                </div>
                                <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                    <label for="ip">IP</label>
                                    <input type="text" class="form-control" id="ip" name="ip"
                                        value="{{ $report->ip ?? '-' }}" disabled>
                                </div>
                                <div class="col-12 col-md-4 form-group px-0 pl-md-2">
                                    <label for="system">Sistema Operacional</label>
                                    <input type="text" class="form-control" id="name" name="system"
                                        value="{{ $report->system ?? '-' }}" disabled>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                    <label for="platform">Plataforma</label>
                                    <input type="text" class="form-control" id="platform" name="platform"
                                        value="{{ $report->platform ?? '-' }}" disabled>
                                </div>

                                <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                    <label for="status">Status</label>
                                    <input type="text" class="form-control" id="status" name="status"
                                        value="{{ $report->status ?? '-' }}" disabled>
                                </div>

                                <div class="col-12 col-md-4 form-group px-0 pl-md-2">
                                    <label for="keys">Palavras chave</label>
                                    <input type="text" class="form-control" id="keys" name="keys"
                                        value="{{ $report->keys ?? '-' }}">
                                </div>
                            </div>

                            @if ($report->description)
                                <div class="col-12 form-group px-0">
                                    <label for="situation">Descrição</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-center"
                                        id="description">
                                        <div class="col-12 px-0 border border-secondary border-2 mx-1 rounded p-2">
                                            {!! $report->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($report->file)
                                <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                    <div class="col-12 p-2 card">
                                        <div class="card-body">
                                            <embed src="{{ url('storage/' . $report->file) }}" type="application/pdf"
                                                width="100%" height="800px">
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
