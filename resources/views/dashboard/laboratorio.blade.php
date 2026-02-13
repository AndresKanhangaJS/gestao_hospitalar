@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-animate border-0 shadow-sm bg-danger text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded-circle fs-3">
                            <i class="ri-flask-line"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-bold text-white-50 mb-1 fs-12">Novas Requisições</p>
                        <h4 class="mb-0 text-white counter-value">{{ $stats['requisicoes_pendentes'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-animate border-0 shadow-sm bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded-circle fs-3">
                            <i class="ri-microscope-line"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-bold text-white-50 mb-1 fs-12">Em Análise</p>
                        <h4 class="mb-0 text-white counter-value">{{ $stats['em_processamento'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-animate border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded-circle fs-3">
                            <i class="ri-check-double-line"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-bold text-white-50 mb-1 fs-12">Liberados Hoje</p>
                        <h4 class="mb-0 text-white counter-value">{{ $stats['concluidos_hoje'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="ri-list-check-2 me-2 text-primary"></i>Fila de Trabalho do Laboratório
                </h5>
                <span class="badge bg-soft-info text-info px-3">Total na fila: {{ $fila_trabalho->count() }}</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th scope="col" class="ps-4">Paciente</th>
                                <th scope="col">Código e Origem da Requisição</th>
                                <th scope="col">Prioridade</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fila_trabalho as $req)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-soft-primary fw-bold">
                                                    {{ substr($req->episodio->paciente->nome_completo, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium text-dark">{{ $req->episodio->paciente->nome_completo }}</div>
                                            <small class="text-muted">#{{ $req->episodio->paciente->codigo_paciente ?? '---' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-body border-light">#{{ $req->codigo_requisicao }}</span>
                                    <div class="fs-11 text-muted mt-1">
                                        <i class="ri-calendar-event-line"></i> {{ $req->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="fs-11 text-primary mt-1">
                                        <i class="ri-user-follow-line"></i>{{ $req->medico->genero == 'Masculino' ? 'Dr.' : 'Dra.' }} {{ $req->medico->nome_completo ?? 'Não informado' }}
                                    </div>
                                </td>
                                <td>
                                    @if($req->prioridade == 'urgente')
                                        <span class="badge bg-danger-subtle text-danger text-uppercase animate__animated animate__flash animate__infinite">
                                            <i class="ri-flashlight-fill"></i> Urgente
                                        </span>
                                    @else
                                        <span class="badge bg-info-subtle text-info text-uppercase">Rotina</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pendente' => 'bg-warning-subtle text-warning',
                                            'em_coleta' => 'bg-info-subtle text-info',
                                            'laboratorio' => 'bg-primary-subtle text-primary',
                                        ][$req->status] ?? 'bg-light text-muted';
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-uppercase">
                                        <i class="ri-time-line me-1"></i> {{ str_replace('_', ' ', $req->status) }}
                                    </span>
                                    <div class="fs-11 text-muted mt-1">
                                        {{ $req->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('laboratorio.lancar', codificar($req->id)) }}"
                                       class="btn btn-sm btn-success shadow-sm px-3 fw-medium">
                                        <i class="ri-flask-fill me-1"></i> Lançar Resultados
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 text-muted">Fila de trabalho vazia.</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
