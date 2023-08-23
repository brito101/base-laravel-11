@extends('adminlte::page')

@section('title', '- Dashboard')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-digital-tachograph"></i> Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @if (Auth::user()->hasRole('Programador|Administrador'))
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-building"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Organizações</span>
                                <span class="info-box-number">{{ $organizations }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Equipes</span>
                                <span class="info-box-number">{{ $teams }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-code"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Programadores</span>
                                <span class="info-box-number">{{ $programmers }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user-shield"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Administradores</span>
                                <span class="info-box-number">{{ $administrators }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Coordenadores</span>
                                <span class="info-box-number">{{ $coordinators }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-user-ninja"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Guerreiros Cibernéticos</span>
                                <span class="info-box-number">{{ $warriors }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $tools }}</h3>
                            <p>Ferramentas Cadastradas</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-virus"></i>
                        </div>
                        <a href="{{ route('admin.tools.index') }}" class="small-box-footer">Ferramentas <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>{{ $operations->count() }}</h3>
                            <p>Operações Cadastradas</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bullseye"></i>
                        </div>
                        <a href="{{ route('admin.operations.index') }}" class="small-box-footer">Operações <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $operationsOngoing }}</h3>
                            <p>Operações em Andamento</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bullseye"></i>
                        </div>
                        <a href="{{ route('admin.operations.ongoing') }}" class="small-box-footer">Operações <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row px-1">
                <div class="card col-12">
                    <div class="card-header">
                        Operações
                    </div>
                    <div class="card-body px-0 pb-0 d-flex flex-wrap justify-content-center">
                        <div class="col-12 col-md-4">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Por Fase</p>
                                </div>
                                <div class="cardy-body py-2">
                                    <div class="chart-responsive">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="operation-step-chart"
                                            style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Por Classificação</p>
                                </div>
                                <div class="cardy-body py-2">
                                    <div class="chart-responsive">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="operation-classification-chart"
                                            style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Por Tipo</p>
                                </div>
                                <div class="cardy-body py-2">
                                    <div class="chart-responsive">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="operation-type-chart"
                                            style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Últimas Operações</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Operação</th>
                                            <th>Início</th>
                                            <th>Término</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($operations->take(10) as $operation)
                                            <tr>
                                                <td><a
                                                        href="{{ route('admin.operations.show', ['operation' => $operation->id]) }}">{{ $operation->id }}</a>
                                                </td>
                                                <td>{{ $operation->title }}</td>
                                                <td>{{ $operation->start ? date('d/m/Y', strtotime($operation->start)) : '' }}
                                                </td>
                                                <td>{{ $operation->end ? date('d/m/Y', strtotime($operation->end)) : '' }}
                                                </td>
                                                <td>{{ $operation->step }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">Sem Operações Cadastradas
                                                <td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            @if (Auth::user()->hasRole('Programador'))
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                            <h3 class="card-title align-self-center">Acessos Diário</h3>
                        </div>
                    </div>

                    @php
                        $heads = [['label' => 'Hora', 'width' => 10], 'Página', 'IP', 'User-Agent', 'Plataforma', 'Navegador', 'Usuário', 'Método'];
                        $config = [
                            'ajax' => url('/admin'),
                            'columns' => [['data' => 'time', 'name' => 'time'], ['data' => 'url', 'name' => 'url'], ['data' => 'ip', 'name' => 'ip'], ['data' => 'useragent', 'name' => 'useragent'], ['data' => 'platform', 'name' => 'platform'], ['data' => 'browser', 'name' => 'browser'], ['data' => 'name', 'name' => 'name'], ['data' => 'method', 'name' => 'method']],
                            'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                            'order' => [0, 'desc'],
                            'destroy' => true,
                            'autoFill' => true,
                            'processing' => true,
                            'serverSide' => true,
                            'responsive' => true,
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
                </div>
            @endif

            @if (Auth::user()->hasRole('Programador|Administrador'))
                <div class="row px-0">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Usuários Online: <span
                                            id="onlineusers">{{ $onlineUsers }}</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <p class="d-flex flex-column">
                                        <span class="text-bold text-lg" id="accessdaily">{{ $access }}</span>
                                        <span>Acessos Diários</span>
                                    </p>
                                    <p class="ml-auto d-flex flex-column text-right">
                                        <span id="percentclass"
                                            class="{{ $percent > 0 ? 'text-success' : 'text-danger' }}">
                                            <i id="percenticon"
                                                class="fas {{ $percent > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}  mr-1"></i><span
                                                id="percentvalue">{{ $percent }}</span>%
                                        </span>
                                        <span class="text-muted">em relação ao dia anterior</span>
                                    </p>
                                </div>

                                <div class="position-relative mb-4">
                                    <div class="chartjs-size-monitor" z>
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="visitors-chart"
                                        style="display: block; width: 489px; height: 200px; max-height: 450px;"
                                        class="chartjs-render-monitor" width="489" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </section>
@endsection

@section('custom_js')

    <script>
        const operationsStep = document.getElementById('operation-step-chart');
        if (operationsStep) {
            operationsStep.getContext('2d');
            const operationsStepChart = new Chart(operationsStep, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($operationsStepChart['label']) !!},
                    datasets: [{
                        label: 'Clientes',
                        data: {!! json_encode($operationsStepChart['data']) !!},
                        backgroundColor: [
                            '#dc3545',
                            '#ffc107',
                            '#28a745',
                            '#17a2b8',
                            '#6c757d',
                            '#007bff',
                            '#ff851b',
                            '#39cccc',
                            '#605ca8',
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'left',
                        labels: {
                            fontColor: "#f8f9fa",
                            fontSize: 12
                        }
                    },
                },
            });
        }

        const operationsClassification = document.getElementById('operation-classification-chart');
        if (operationsClassification) {
            operationsClassification.getContext('2d');
            const operationsClassificationChart = new Chart(operationsClassification, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($operationsClassificationChart['label']) !!},
                    datasets: [{
                        label: 'Clientes',
                        data: {!! json_encode($operationsClassificationChart['data']) !!},
                        backgroundColor: [
                            '#dc3545',
                            '#28a745',
                            '#007bff',
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'left',
                        labels: {
                            fontColor: "#f8f9fa",
                            fontSize: 12
                        }
                    },
                },
            });
        }

        const operationsType = document.getElementById('operation-type-chart');
        if (operationsType) {
            operationsType.getContext('2d');
            const operationsTypeChart = new Chart(operationsType, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($operationsTypeChart['label']) !!},
                    datasets: [{
                        label: 'Clientes',
                        data: {!! json_encode($operationsTypeChart['data']) !!},
                        backgroundColor: [
                            '#343a40',
                            '#f8f9fa',
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'left',
                        labels: {
                            fontColor: "#f8f9fa",
                            fontSize: 12
                        }
                    },
                },
            });
        }
    </script>
    @if (Auth::user()->hasRole('Programador|Administrador'))
        <script>
            const ctx = document.getElementById('visitors-chart');
            if (ctx) {
                ctx.getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ({!! json_encode($chart->labels) !!}),
                        datasets: [{
                            label: 'Acessos por horário',
                            data: {!! json_encode($chart->dataset) !!},
                            borderWidth: 1,
                            borderColor: '#007bff',
                            backgroundColor: 'transparent'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        legend: {
                            labels: {
                                boxWidth: 0,
                            }
                        },
                    },
                });

                let getData = function() {

                    $.ajax({
                        url: "{{ route('admin.home.chart') }}",
                        type: "GET",
                        success: function(data) {
                            myChart.data.labels = data.chart.labels;
                            myChart.data.datasets[0].data = data.chart.dataset;
                            myChart.update();
                            $("#onlineusers").text(data.onlineUsers);
                            $("#accessdaily").text(data.access);
                            $("#percentvalue").text(data.percent);
                            const percentclass = $("#percentclass");
                            const percenticon = $("#percenticon");
                            percentclass.removeClass('text-success');
                            percentclass.removeClass('text-danger');
                            percenticon.removeClass('fa-arrow-up');
                            percenticon.removeClass('fa-arrow-down');
                            if (parseInt(data.percent) > 0) {
                                percentclass.addClass('text-success');
                                percenticon.addClass('fa-arrow-up');
                            } else {
                                percentclass.addClass('text-danger');
                                percenticon.addClass('fa-arrow-down');
                            }
                        }
                    });
                };
                setInterval(getData, 10000);
            }
        </script>
    @endif
@endsection
