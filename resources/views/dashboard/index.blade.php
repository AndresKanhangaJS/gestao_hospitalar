@extends('layouts.app')

@section('title', 'Painel de Gestão Hospitalar')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <div>
                <h4 class="mb-sm-0 fw-bold text-primary text-uppercase">Painel Operacional</h4>
                <p class="text-muted mb-0">Olá, <strong>{{ auth()->user()->name }}</strong>. Aqui está o resumo de hoje.</p>
            </div>
            <div class="page-title-right">
                <div class="badge bg-soft-info text-info p-2 fs-12">
                    <i class="ri-calendar-event-line"></i> {{ now()->translatedFormat('d \d\e M, Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Pacientes Ativos</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary mb-4">{{ number_format($stats['ativos'], 0, ',', '.') }}</h4>
                        <span class="badge bg-success-subtle text-success"><i class="ri-check-line"></i> No Sistema</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-user-heart-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Assegurados</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary mb-4">{{ $stats['assegurados'] }}</h4>
                        <span class="text-muted">Com convénio ativo</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded fs-3">
                            <i class="ri-shield-cross-line text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Particulares</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-bold ff-secondary mb-4 text-warning">{{ $stats['particulares'] }}</h4>
                        <span class="badge bg-warning-subtle text-warning">Pagamento Direto</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i class="ri-money-dollar-circle-line text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header align-items-center d-flex border-0 bg-white p-3">
                <h4 class="card-title mb-0 flex-grow-1 fw-bold text-muted">Pacientes por Género</h4>
            </div>
            <div class="card-body">
                <div id="gender_chart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 bg-white p-3">
                <h4 class="card-title mb-0 fw-bold text-muted">Distribuição por Seguradora</h4>
            </div>
            <div class="card-body">
                <div id="insurance_chart" style="height: 250px;" class="mb-4"></div>

                <ul class="list-group list-group-flush border-top">
                    @foreach($porSeguradora as $seg)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-medium">{{ $seg->nome }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $seg->pacientes_count }}</span>
                    </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-medium text-muted">Particular</span>
                        <span class="badge bg-secondary rounded-pill">{{ $stats['particulares'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // 1. GRÁFICO DE GÉNERO
        var genderOptions = {
            series: [{
                name: 'Pacientes',
                data: [
                    {{ $generos['Masculino'] ?? 0 }},
                    {{ $generos['Feminino'] ?? 0 }}
                ]
            }],
            chart: { type: 'bar', height: 350, toolbar: { show: false } },
            plotOptions: { bar: { borderRadius: 4, horizontal: false, columnWidth: '40%' } },
            colors: ['#405189'],
            xaxis: { categories: ['Masculino', 'Feminino'] }
        };
        new ApexCharts(document.querySelector("#gender_chart"), genderOptions).render();

        // 2. GRÁFICO DE SEGURADORAS
        var insuranceOptions = {
            series: [
                @foreach($porSeguradora as $seg) {{ $seg->pacientes_count }}, @endforeach
                {{ $stats['particulares'] }}
            ],
            labels: [
                @foreach($porSeguradora as $seg) "{{ $seg->nome }}", @endforeach
                "Particular"
            ],
            chart: { type: 'donut', height: 250 },
            legend: { position: 'bottom' },
            dataLabels: { enabled: false },
            colors: ['#405189', '#0ab39c', '#f7b84b', '#f06548', '#299cdb', '#6c757d']
        };
        new ApexCharts(document.querySelector("#insurance_chart"), insuranceOptions).render();
    });
</script>
@endpush
