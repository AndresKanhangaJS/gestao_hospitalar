@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white-50 rounded-circle fs-3 text-white">
                                <i class="ri-flask-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-white-50 mb-1 fw-medium">Total Hoje</p>
                            <h4 class="mb-0 text-white">{{ $requisicoes->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning text-warning rounded-circle fs-3">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Pendentes</p>
                            <h4 class="mb-0">{{ $requisicoes->where('status', 'pendente')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger text-danger rounded-circle fs-3">
                                <i class="ri-alert-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Urgências</p>
                            <h4 class="mb-0">{{ $requisicoes->where('prioridade', 'urgente')->where('status', 'pendente')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success text-success rounded-circle fs-3">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fw-medium">Concluídos</p>
                            <h4 class="mb-0">{{ $requisicoes->where('status', 'concluido')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-bold text-dark">
                <i class="ri-list-check-2 me-2 text-primary"></i>Requisições de Laboratório
            </h5>
        </div>

        <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
            <form action="{{ route('laboratorio.index') }}" method="GET" id="filter-form">
                <div class="row g-3">
                    <div class="col-xxl-2 col-sm-12">
                        <div class="search-box">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control search bg-white border-light"
                                placeholder="Buscar Paciente, REQ ou Médico...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <div class="col-xxl-2 col-sm-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted">De:</span>
                            <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                                class="form-control bg-white border-light">
                        </div>
                    </div>

                    <div class="col-xxl-2 col-sm-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted">Até:</span>
                            <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                                class="form-control bg-white border-light">
                        </div>
                    </div>

                    <div class="col-xxl-2 col-sm-6">
                        <select class="form-select bg-white border-light" name="prioridade">
                            <option value="">Prioridade (Todas)</option>
                            <option value="urgente" {{ request('prioridade') == 'urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                            <option value="normal" {{ request('prioridade') == 'normal' ? 'selected' : '' }}>🔵 Normal</option>
                        </select>
                    </div>

                    <div class="col-xxl-2 col-sm-6">
                        <select class="form-select bg-white border-light" name="status">
                            <option value="">Status</option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>🟡 Pendente</option>
                            <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>🟢 Concluído</option>
                        </select>
                    </div>

                    <div class="col-xxl-2 col-sm-6 d-flex gap-2 justify-content-end align-items-end">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm flex-grow-1">
                            <i class="ri-equalizer-fill me-1 align-bottom"></i>Filtrar
                        </button>
                        <a href="{{ route('laboratorio.index') }}" class="btn btn-soft-danger px-4 shadow-sm">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-nowrap mb-0" id="laboratorioTable">
                    <thead class="table-light text-muted">
                        <tr>
                            <th scope="col" class="ps-4">Paciente</th>
                            <th scope="col">Código e Origem da Requisição</th>
                            <th scope="col">Perfil / Contacto</th>
                            <th scope="col">Prioridade</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requisicoes as $req)
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
                                        <small class="text-muted">#{{ $req->episodio->paciente->codigo_paciente }}</small>
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
                                <div class="d-flex flex-column">
                                    <span class="text-dark fs-13">
                                        {{ $req->episodio->paciente->data_nascimento ? \Carbon\Carbon::parse($req->episodio->paciente->data_nascimento)->age . ' Anos' : '---' }}
                                        <span class="text-muted fs-11">({{ $req->episodio->paciente->genero }})</span>
                                    </span>
                                    <small class="text-muted"><i class="ri-phone-line me-1"></i>{{ $req->episodio->paciente->telefone ?? 'Sem contacto' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($req->prioridade == 'urgente')
                                    <span class="badge bg-danger-subtle text-danger text-uppercase">
                                        <i class="ri-flashlight-fill"></i> Urgente
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info text-uppercase">Normal</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $req->status == 'concluido' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} text-uppercase">
                                    {{ $req->status }}
                                </span>
                                @if($req->status == 'concluido')
                                <div class="fs-11 text-muted mt-1">
                                    <i class="ri-time-fill"></i> Concluído em: {{ $req->updated_at->format('d/m/Y H:i') }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="hstack gap-2 justify-content-center">
                                    @if($req->status == 'concluido')
                                        <a href="{{ route('laboratorio.imprimir', $req) }}" target="_blank" class="btn btn-sm btn-soft-info" title="Imprimir Laudo">
                                            <i class="ri-printer-fill"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('laboratorio.lancar', $req) }}" class="btn btn-sm btn-soft-primary" title="Editar Resultados">
                                            <i class="ri-edit-2-fill"></i>
                                        </a>
                                        <a href="{{ route('laboratorio.lancar', $req) }}" class="btn btn-sm btn-success shadow-sm px-3 fw-medium">
                                            <i class="ri-flask-fill me-1"></i> Lançar Resultados
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2 text-muted">Nenhuma requisição pendente.</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
