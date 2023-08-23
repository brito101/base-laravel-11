@extends('adminlte::master')
@section('title', '- 404')

@section('body')
    <div class="content">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>404</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ env('APP_URL') }}">Home</a></li>
                            <li class="breadcrumb-item active">404 Error Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-warning"> 404</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> Ops! Página não encontrada.</h3>
                    <p>
                        Não foi possível encontrar a página que você estava procurando.
                        Enquanto isso, você pode <a href="{{ env('APP_URL') }}">retornar para a página principal</a>.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
