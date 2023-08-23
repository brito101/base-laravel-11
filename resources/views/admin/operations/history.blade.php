@extends('adminlte::page')
@section('title', '- Timeline de Operação')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Timeline da Operação #{{ $operation->id }}: {{ $operation->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.operations.index') }}">Operações</a></li>
                        <li class="breadcrumb-item active">Timeline</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">

                    <div class="timeline">

                        @forelse ($histories as $history)
                            <div class="time-label">
                                <span class="bg-info">{{ $history->created_at }}</span>
                            </div>
                            <div>
                                @switch($history->action)
                                    @case('criado')
                                        <i class="fas fa-plus bg-purple"></i>
                                    @break

                                    @case('editado')
                                        <i class="fas fa-edit bg-green"></i>
                                    @break

                                    @case('deletado')
                                        <i class="fas fa-trash bg-danger"></i>
                                    @break

                                    @case('restaurado')
                                        <i class="fas fa-life-ring bg-orange"></i>
                                    @break

                                    @default
                                        <i class="fas fa-edit bg-green"></i>
                                @endswitch

                                <div class="timeline-item col-12 col-md-8" style="max-width: 80%">
                                    <h3 class="timeline-header no-border">
                                        Operação <a
                                            href="{{ route('admin.operations.show', ['operation' => $history->operation_id]) }}"
                                            target="_blank">{{ $history->operation->title }}</a> {{ $history->action }} por
                                        {{ $history->user->name }}.</h3>
                                    <div class="timeline-body">
                                        Fase nesta data: <b>{{ $history->step->name }}</b>.
                                    </div>
                                </div>
                            </div>
                            @empty
                                <div class="time-label">
                                    <span class="bg-info">{{ date('d/m/Y H:i', strtotime($operation->updated_at)) }}</span>
                                </div>
                                <div>
                                    <i class="fas fa-exclamation bg-warning"></i>

                                    <div class="timeline-item col-12 col-md-8" style="max-width: 80%">
                                        <h3 class="timeline-header no-border">
                                            Operação <a
                                                href="{{ route('admin.operations.show', ['operation' => $history->operation_id]) }}"
                                                target="_blank">{{ $history->operation->title }}</a> não possui dados
                                            históricos no momento.
                                        </h3>
                                        <div class="timeline-body">
                                            Fase nesta data: <b>{{ $history->step->name }}</b>.
                                        </div>
                                    </div>
                                </div>
                            @endforelse

                            @if ($histories->count() == 0 || !in_array('criado', $histories->pluck('action')->toArray()))
                                <div class="time-label">
                                    <span class="bg-info">{{ date('d/m/Y H:i', strtotime($operation->created_at)) }}</span>
                                </div>
                                <div>
                                    <i class="fas fa-user-plus bg-purple"></i>
                                    <div class="timeline-item col-12 col-md-8" style="max-width: 80%">
                                        <h3 class="timeline-header no-border">
                                            Operação <a
                                                href="{{ route('admin.operations.show', ['operation' => $operation->id]) }}"
                                                target="_blank">{{ $operation->title }}</a> criado.</h3>
                                    </div>
                                </div>
                            @endif
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    @endsection
