@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded fs-3"><i class="ri-user-follow-line"></i></span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-white-50 mb-0">Meus Pacientes</p>
                        <h4 class="mb-0 text-white">{{ $stats['meus_pacientes'] }}</h4>
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
                        <span class="avatar-title bg-white-subtle rounded fs-3"><i class="ri-pulse-line"></i></span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-white-50 mb-0">Em Atendimento</p>
                        <h4 class="mb-0 text-white">{{ $stats['atendimentos_abertos'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-subtle rounded fs-3"><i class="ri-file-list-3-line"></i></span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-uppercase fw-medium text-white-50 mb-0">Notas de Hoje</p>
                        <h4 class="mb-0 text-white">{{ $stats['notas_hoje'] }}</h4>
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
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">Próximos Pacientes (Hoje)</h5>
                <a href="{{ route('episodios.index') }}" class="btn btn-sm btn-soft-secondary">Ver Todo Histórico</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Paciente</th>
                                <th>Prioridade</th> <th>Tipo</th>
                                <th>Hora Entrada</th>
                                <th>Status</th>
                                <th scope="col" class="text-center">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agenda_hoje as $ep)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary fw-bold">
                                                {{ substr($ep->paciente->nome_completo, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="fw-bold">{{ $ep->paciente->nome_completo }}</span>
                                    </div>
                                </td>

                                <td>
                                    @php
                                        $corPrioridade = [
                                            'Emergente'      => 'danger',  // Vermelho
                                            'Muito Urgente'  => 'warning', // Laranja
                                            'Urgente'        => 'info',    // Amarelo (ou custom)
                                            'Pouco Urgente'  => 'success', // Verde
                                            'Não Urgente'    => 'primary'  // Azul
                                        ];
                                        $classe = $corPrioridade[$ep->prioridade] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $classe }} text-uppercase">
                                        <i class="ri-checkbox-blank-circle-fill me-1 small"></i>{{ $ep->prioridade }}
                                    </span>
                                </td>

                                <td><span class="badge bg-soft-info text-info">{{ $ep->tipoAtendimento->nome }}</span></td>
                                <td>{{ $ep->created_at->format('H:i') }}</td>
                                <td><span class="badge bg-success">Aguardando</span></td>
                                <td>
                                    <div class="hstack gap-2 justify-content-center">
                                        <a href="{{ route('episodios.show', $ep) }}" class="btn btn-primary btn-sm shadow-sm">
                                            <i class="ri-external-link-line me-1"></i> Abrir Ficha
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="ri-inbox-line fs-24 d-block mb-2"></i>
                                    Sem pacientes em espera para hoje.
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
