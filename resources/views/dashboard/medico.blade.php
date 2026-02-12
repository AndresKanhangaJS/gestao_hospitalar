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
                @can('episodios.listar')
                <a href="{{ route('episodios.index') }}" class="btn btn-sm btn-soft-secondary">Listar Histórico de Atendimentos</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Paciente</th>
                                <th>Prioridade</th> <th>Tipo</th>
                                <th>Hora Entrada</th>
                                <th>Estado</th>
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
                                        <div>
                                            <h6 class="mb-0 fs-14 fw-bold text-dark">{{ $ep->paciente->nome_completo }}</h6>
                                            <small class="text-muted">{{ $ep->paciente->numero_documento }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @php
                                        $corBadge = [
                                            'Emergente' => 'bg-danger',
                                            'Muito Urgente' => 'bg-warning text-dark',
                                            'Urgente' => 'bg-warning text-dark',
                                            'Pouco Urgente' => 'bg-success',
                                            'Não Urgente' => 'bg-info',
                                        ][$ep->prioridade] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $corBadge }} shadow-sm">
                                        <i class="ri-checkbox-blank-circle-fill me-1 fs-10"></i> {{ $ep->prioridade }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-1">
                                        {{ $ep->tipoAtendimento->nome ?? $ep->tipo }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $ep->created_at->format('d/m/Y') }}</span>
                                        <small class="text-muted"><i class="ri-time-line me-1 fs-11"></i>{{ $ep->created_at->format('H:i') }}</small>
                                        <small class="text-muted fs-11">Entrada</small>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge {{ $ep->situacao == 'Aguardando Triagem' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }} border border-{{ $ep->situacao == 'Aguardando Triagem' ? 'warning' : 'success' }}-subtle px-2">
                                            @if($ep->situacao == 'Aguardando Triagem')
                                                <i class="ri-loader-2-line ri-spin me-1"></i>
                                            @endif
                                            {{ $ep->situacao }}
                                        </span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('episodios.show', $ep) }}" class="btn btn-primary btn-sm btn-label waves-effect waves-light shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-external-link-line label-icon align-middle fs-16 me-2"></i>
                                            <span>Abrir Ficha</span>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <lord-icon src="https://cdn.lordicon.com/vlynuwvu.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:50px;height:50px"></lord-icon>
                                    <p class="mt-2">Sem pacientes em espera para hoje.</p>
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
