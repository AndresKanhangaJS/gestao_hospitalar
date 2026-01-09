@extends('layouts.app')

@section('title', 'Gestão de Episódios')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-hospital-line me-1"></i> Episódios Clínicos
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Sistema</a></li>
                    <li class="breadcrumb-item active">Lista de Atendimentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-muted">Histórico de Atendimentos</h5>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
                <form action="{{ route('episodios.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-xxl-3 col-sm-6">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control search bg-white border-light"
                                    placeholder="Nome do paciente ou documento...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-3">
                            <select class="form-select bg-white border-light" name="tipo_id">
                                <option value="">Tipo (Todos)</option>
                                @foreach($tiposAtendimento as $tipo)
                                    <option value="{{ $tipo->id }}" {{ request('tipo_id') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-3">
                            <select class="form-select bg-white border-light" name="medico_id">
                                <option value="">Médico Responsável</option>
                                @foreach($medicos as $medico)
                                    <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>
                                        Dr(a). {{ $medico->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select bg-white border-light" name="status">
                                <option value="">Status (Todos)</option>
                                <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        <div class="col-xxl-3 col-sm-8 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                            </button>
                            <a href="{{ route('episodios.index') }}" class="btn btn-soft-danger w-50 shadow-sm" title="Limpar Filtros">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mb-1">
                    <table class="table table-hover align-middle table-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th scope="col" class="ps-4">Paciente</th>
                                <th scope="col">Data/Hora</th>
                                <th scope="col">Tipo Atendimento</th>
                                <th scope="col">Médico</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($episodios as $episodio)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-soft-info text-info fw-bold">
                                                    {{ substr($episodio->paciente->nome_completo, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="flex-grow-1 mb-0 fs-14 fw-bold text-dark">{{ $episodio->paciente->nome_completo }}</h6>
                                            <small class="text-muted">{{ $episodio->paciente->numero_documento }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium text-dark">{{ $episodio->created_at->format('d/m/Y') }}</span>
                                        <small class="text-muted"><i class="ri-time-line me-1 fs-11"></i>{{ $episodio->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-1">
                                        {{ $episodio->tipoAtendimento->nome ?? $episodio->tipo }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-star-line me-1 text-muted fs-16"></i>
                                        <span class="text-muted fw-medium">Dr. {{ $episodio->medico->name ?? 'Não Atribuído' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $episodio->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-uppercase">
                                        {{ $episodio->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 justify-content-center">
                                        <a href="{{ route('episodios.show', $episodio->id) }}" class="btn btn-sm btn-soft-info" title="Ver detalhes">
                                            <i class="ri-eye-fill"></i>
                                        </a>
                                        <a href="" class="btn btn-sm btn-soft-primary" title="Editar">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <lord-icon src="https://cdn.lordicon.com/vlynuwvu.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 text-muted">Nenhum episódio encontrado para os filtros aplicados.</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 p-3">
                    <p class="text-muted mb-0">Mostrando {{ $episodios->count() }} de {{ $episodios->total() }} registos</p>
                    <div>
                        {{ $episodios->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
