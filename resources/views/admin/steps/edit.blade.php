@extends('adminlte::page')

@section('title', '- Edição de Fase')
@section('plugins.BootstrapColorpicker', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-shoe-prints"></i> Editar Fase</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Usuários')
                            <li class="breadcrumb-item"><a href="{{ route('admin.steps.index') }}">Fases</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Editar Fase</li>
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
                            <h3 class="card-title">Dados Cadastrais da Fase</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.steps.update', ['step' => $step->id]) }}">
                            @method('PUT')
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nome Completo" name="name"
                                            value="{{ old('name') ?? $step->name }}" required>
                                    </div>
                                </div>

                                <div class="col-12 form-group px-0">
                                    <label for="description">Descrição</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start"
                                        id="description">
                                        <div class="col-12 px-0">
                                            <textarea type="text" class="form-control" id="description" placeholder="Descrição" name="description"
                                                value="{{ old('description') ?? $step->description }}">{{ old('description') ?? $step->description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $config = [
                                        'extensions' => [['name' => 'debugger']],
                                    ];
                                @endphp

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                        <x-adminlte-input-color name="color" placeholder="Escolha uma cor..."
                                            value="{{ $step->color }}" label="Cor do cartão" igroup-size="md"
                                            :config="$config" enable-old-support required>
                                            <x-slot name="appendSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-lg fa-brush"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input-color>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 pl-md-2">
                                        <label for="sequence">Sequência</label>
                                        <input type="number" min="1" max="100" step="1"
                                            class="form-control" id="sequence" placeholder="Ordem da fase" name="sequence"
                                            value="{{ old('sequence') ?? $step->sequence }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>

                    </div>

                    <h4>Fases existentes</h4>
                    <div class="d-flex flex-wrap justify-content-between">
                        @foreach ($steps as $item)
                            <a href="{{ route('admin.steps.edit', ['step' => $item->id]) }}" target="_blank"
                                title="Editar {{ $item->name }}" class="btn my-2"
                                style="background-color: {{ $item->color }}">#{{ $item->sequence }}:
                                {{ $item->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
