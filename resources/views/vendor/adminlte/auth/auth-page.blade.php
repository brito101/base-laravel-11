@extends('adminlte::master')

@php($dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home'))

@if (config('adminlte.use_route_url', false))
    @php($dashboard_url = $dashboard_url ? route($dashboard_url) : '')
@else
    @php($dashboard_url = $dashboard_url ? url($dashboard_url) : '')
@endif

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    @stack('css')
    @yield('css')
@stop
@section('meta_tags')
<meta name="description" content="SIOC - Sistema de Informações de Operações Cibernéticas">@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')
    <canvas class="snow"></canvas>
    <div class="{{ $auth_type ?? 'login' }}-box">

        {{-- Logo --}}
        <div class="{{ $auth_type ?? 'login' }}-logo">
            <a href="{{ $dashboard_url }}" aria-label="Sistema de Informações de Operações Cibernéticas - Login">
                <img src="{{ asset('img/brand-transparent.png') }}" height="120" width="333"
                    alt="SIOC - Sistema de Informações de Operações Cibernéticas">
            </a>
            <h1 class="h5 text-white">Sistema de Informações de Operações Cibernéticas</h1>
        </div>

        {{-- Card Box --}}
        <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

            {{-- Card Header --}}
            @hasSection('auth_header')
                <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
                    <h2 class="card-title float-none text-center">
                        @yield('auth_header')
                    </h2>
                </div>
            @endif

            {{-- Card Body --}}
            <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                @yield('auth_body')
            </div>

            {{-- Card Footer --}}
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif

        </div>

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')

    <script src="{{ asset('js/snow.js') }}"></script>
@stop
