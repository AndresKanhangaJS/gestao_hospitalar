@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card bg-danger text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded fs-3"><i class="ri-flask-line"></i></span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-white-50 mb-0">Novas Requisições</p>
                        <h4 class="mb-0 text-white">{{ $stats['requisicoes_pendentes'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded fs-3"><i class="ri-microscope-line"></i></span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-white-50 mb-0">Em Análise</p>
                        <h4 class="mb-0 text-white">{{ $stats['em_processamento'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded fs-3"><i class="ri-check-double-line"></i></span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-white-50 mb-0">Liberados Hoje</p>
                        <h4 class="mb-0 text-white">{{ $stats['concluidos_hoje'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-white d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">Fila de Trabalho do Laboratório</h5>
                <span class="badge bg-soft-info text-info">Total na fila: {{ $fila_trabalho->count() }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Paciente / Médico</th>
                                <th>Prioridade</th>
                                <th>Solicitado em</th>
                                <th>Status</th>
                                <th class="text-center">Acção</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fila_trabalho as $req)
                            <tr>
                                <td class="fw-bold text-primary">{{ $req->codigo_requisicao }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 fs-14 fw-bold">{{ $req->episodio->paciente->nome_completo }}</h6>
                                        <small class="text-muted">Solicitado por: Dr(a). {{ $req->medico->name }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($req->prioridade == 'urgente')
                                        <span class="badge bg-danger animate__animated animate__flash animate__infinite">URGENTE (STAT)</span>
                                    @else
                                        <span class="badge bg-info">Rotina</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $req->created_at->format('d/m/Y') }}</span>
                                        <small class="text-muted">{{ $req->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-warning border border-warning-subtle">
                                        <i class="ri-time-line me-1"></i> {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('laboratorio.lancar', codificar($req->id)) }}" class="btn btn-primary btn-sm btn-label waves-effect waves-light">
                                        <i class="ri-edit-2-line label-icon align-middle fs-16 me-2"></i> Lançar Resultados
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">Nenhuma requisição pendente no momento.</p>
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
