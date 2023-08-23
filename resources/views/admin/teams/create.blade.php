@extends('adminlte::page')

@section('title', '- Cadastro de Equipe')
@section('plugins.select2', true)
@section('plugins.BootstrapSelect', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-users"></i> Nova Equipe</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Equipes')
                            <li class="breadcrumb-item"><a href="{{ route('admin.teams.index') }}">Equipes</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Nova Equipe</li>
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
                            <h3 class="card-title">Dados Cadastrais da Equipe</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.teams.store') }}">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Nome da Equipe</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nome da Equipe" name="name" value="{{ old('name') }}"
                                            required>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                        <label for="role">Capacidade</label>
                                        <x-adminlte-select2 name="capacity">
                                            <option {{ old('capacity') == '' ? 'selected' : '' }} value=""></option>
                                            <option {{ old('capacity') == 'Proteção Cibernética' ? 'selected' : '' }}
                                                value="Proteção Cibernética">Proteção Cibernética</option>
                                            <option {{ old('capacity') == 'Ataque Cibernético' ? 'selected' : '' }}
                                                value="Ataque Cibernético">Ataque Cibernético</option>
                                            <option {{ old('capacity') == 'Exploração Cibernética' ? 'selected' : '' }}
                                                value="Exploração Cibernética">Exploração Cibernética</option>
                                        </x-adminlte-select2>
                                    </div>
                                </div>

                                <div class="col-12 form-group px-0">
                                    <label for="description">Descrição</label>
                                    <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start"
                                        id="description">
                                        <div class="col-12 px-0">
                                            <textarea type="text" class="form-control" id="description" placeholder="Descrição" name="description"
                                                value="{{ old('description') }}"></textarea>
                                        </div>
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
                                        <x-adminlte-select-bs id="users" name="users[]" label="Componentes"
                                            igroup-size="md" :config="$config" multiple>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                    {{ $user->organization->alias_name ? '- ' . $user->organization->alias_name : '' }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2 mb-0">
                                        <x-adminlte-select-bs id="organizations" name="organizations[]"
                                            label="Organizações Participantes" igroup-size="md" :config="$config" multiple>
                                            @foreach ($organizations as $organization)
                                                <option value="{{ $organization->id }}">
                                                    {{ $organization->code ? $organization->code . ' - ' : '' }}{{ $organization->alias_name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select-bs>
                                    </div>
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
