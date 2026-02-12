@extends('layouts.app')

@section('title', 'Perfil do Paciente: ' . $paciente->nome_completo)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded border-start border-info border-3">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-file-user-line me-1"></i> Prontuário Digital
            </h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('pacientes.index') }}" class="btn btn-light btn-label shadow-sm">
                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Voltar
                </a>
                @can('pacientes.editar')
                <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-primary btn-label shadow-sm">
                    <i class="ri-pencil-fill label-icon align-middle fs-16 me-2"></i> Editar Dados
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xxl-3 col-lg-4">
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="bg-primary-subtle" style="height: 70px;"></div>

            <div class="card-body text-center" style="margin-top: -40px;">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-white text-primary rounded-circle fs-32 shadow-sm border border-3 border-primary">
                        {{ strtoupper(substr($paciente->nome_completo, 0, 1)) }}
                    </div>
                </div>

                <h5 class="mb-1 fw-bold text-dark">{{ $paciente->nome_completo }}</h5>
                <span class="badge bg-secondary fs-11 align-middle ms-1">#{{ $paciente->codigo_paciente }}</span>
                <p class="text-muted fs-12 mb-3 text-uppercase">
                    <i class="ri-calendar-check-line me-1"></i> Paciente desde {{ $paciente->created_at->format('M, Y') }}
                </p>

                <div class="d-flex justify-content-center gap-2 mb-0">
                    <span class="badge {{ $paciente->status == 'activo' ? 'bg-success' : 'bg-danger' }} px-3 py-2 shadow-sm">
                        {{ strtoupper($paciente->status) }}
                    </span>
                </div>
            </div>

            <div class="card-footer py-3 bg-light-subtle border-top-0">
                <div class="row justify-content-center text-center">
                    <div class="col-6 border-end">
                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Idade Actual</p>
                        <h6 class="mb-0 fw-bold text-dark">
                            @if($paciente->data_nascimento)
                                {{ $paciente->data_nascimento->age }} Anos
                            @else
                                <span class="text-warning">Não informado</span>
                            @endif
                        </h6>
                    </div>
                    @can('pacientes.informacoes_medicas')
                    <div class="col-6">
                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">G. Sanguíneo</p>
                        <h6 class="mb-0 fw-bold text-danger">
                            <i class="ri-drop-fill me-1"></i>{{ $paciente->grupo_sanguineo ?? 'N/D' }}
                        </h6>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light-subtle py-3">
                <h6 class="card-title mb-0 fw-bold text-uppercase fs-12">
                    <i class="ri-contacts-book-line me-2 align-middle text-primary"></i>Informações de Contacto
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="p-3 bg-light-subtle border-bottom border-top">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-soft-info text-info rounded-circle fs-20 shadow-sm">
                                    <i class="ri-shield-cross-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Convênio / Seguradora</p>
                            <h6 class="fs-14 mb-0 fw-bold text-primary">{{ $paciente->seguradora->nome ?? 'Particular' }}</h6>
                            @if($paciente->numero_cartao_seguro)
                                <div class="d-flex align-items-center mt-1">
                                    <span class="badge bg-white text-dark border shadow-sm fs-10">
                                        <i class="ri-id-card-line me-1 text-muted"></i>{{ $paciente->numero_cartao_seguro }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-soft-primary rounded-circle fs-18 shadow-sm">
                                <i class="ri-phone-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-1 fw-bold text-dark">{{ $paciente->telefone ?? 'Não informado' }}</h6>
                        <p class="text-muted mb-0 fs-12">Telefone Principal</p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-soft-primary rounded-circle fs-18 shadow-sm">
                                <i class="ri-mail-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-15 mb-1 fw-bold text-dark text-break">{{ $paciente->email }}</h6>
                        <p class="text-muted mb-0 fs-12">E-mail</p>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-soft-primary rounded-circle fs-18 shadow-sm">
                                <i class="ri-map-pin-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 fw-bold text-dark lh-base">{{ $paciente->morada }}</h6>
                        <p class="text-muted mb-0 fs-12">Morada completa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-9 col-lg-8">

        @can('pacientes.informacoes_medicas')
        <div class="alert {{ $paciente->alergias ? 'alert-danger' : 'alert-info' }} border-0 d-flex align-items-center mb-4 shadow-sm p-3" role="alert">
            <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#ffffff" style="width:45px;height:45px" class="me-3"></lord-icon>
            <div>
                <h5 class="{{ $paciente->alergias ? 'text-danger' : 'text-info' }} fw-bold mb-1">ALERGIAS E RESTRIÇÕES MÉDICAS</h5>
                <p class="mb-0 fs-14">{{ $paciente->alergias ?? 'Nenhuma alergia ou restrição registada até o momento para este paciente.' }}</p>
            </div>
        </div>
        @endcan


        <div class="card shadow-sm border-0">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs-custom nav-success border-bottom-0 ms-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-3" data-bs-toggle="tab" href="#pessoais" role="tab">
                            <i class="ri-information-line me-1 align-bottom"></i> Informações Pessoais
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3" data-bs-toggle="tab" href="#historico" role="tab">
                            <i class="ri-history-line me-1 align-bottom"></i> Histórico Clínico
                            <span class="badge bg-danger-subtle text-danger ms-1 rounded-pill">{{ $paciente->episodios->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="pessoais" role="tabpanel">
                        <div class="row g-4"> <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <div class="avatar-title bg-soft-primary rounded-circle fs-16">
                                            <i class="ri-fingerprint-2-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Identificação</p>
                                        <h6 class="fs-14 mb-0"><span class="fw-bold">{{ $paciente->tipo_documento }}</span>: {{ $paciente->numero_documento }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <div class="avatar-title bg-soft-primary rounded-circle fs-16">
                                            <i class="ri-calendar-event-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Nascimento (Idade)</p>
                                        @if($paciente->data_nascimento)
                                            {{ $paciente->data_nascimento->format('d/m/Y') }} ({{ $paciente->data_nascimento->age }} anos)
                                        @else
                                            <span class="text-warning">Não informado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <div class="avatar-title rounded-circle fs-16">
                                            <i class="{{ $paciente->genero == 'Masculino' ? 'ri-men-line' : 'ri-women-line' }}"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Sexo / Gênero</p>
                                        <h6 class="fs-14 mb-0 text-dark fw-bold">{{ $paciente->genero }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h6 class="fs-12 text-muted mb-3 text-uppercase fw-bold tracking-wider">
                                <i class="ri-shield-check-line me-1 align-middle text-success"></i> Auditoria de Sistema
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6 col-xxl-4">
                                    <div class="p-3 border border-light rounded bg-light-subtle d-flex align-items-center">
                                        <i class="ri-user-add-line fs-20 text-muted me-3"></i>
                                        <div>
                                            <p class="text-muted mb-0 fs-11">Registado por <b>{{ $paciente->criador->name ?? 'Sistema' }}</b></p>
                                            <p class="text-muted mb-0 fs-11">{{ $paciente->created_at->format('d/m/Y \à\s H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($paciente->user_id_atualizacao)
                                <div class="col-md-6 col-xxl-4">
                                    <div class="p-3 border border-light rounded bg-light-subtle d-flex align-items-center">
                                        <i class="ri-edit-circle-line fs-20 text-muted me-3"></i>
                                        <div>
                                            <p class="text-muted mb-0 fs-11">Última edição por <b>{{ $paciente->atualizador->name ?? 'N/D' }}</b></p>
                                            <p class="text-muted mb-0 fs-11">{{ $paciente->updated_at->format('d/m/Y \à\s H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="historico" role="tabpanel">
                        <div class="table-responsive mt-2">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="bg-light-subtle border-bottom">
                                    <tr class="text-muted fs-11 text-uppercase">
                                        <th class="ps-3" style="width: 180px;">Data e Hora</th>
                                        <th>Especialidade/Tipo</th>
                                        <th>Médico Assistente</th>
                                        <th>Situação / Estado</th>
                                        <th class="text-end pe-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paciente->episodios as $episodio)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2 text-primary">
                                                    <i class="ri-calendar-todo-fill fs-16"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fs-13 mb-0 fw-bold">{{ $episodio->created_at->format('d/m/Y') }}</h6>
                                                    <small class="text-muted">{{ $episodio->created_at->format('H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium text-dark">{{ $episodio->tipoAtendimento->nome }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xxs me-2">
                                                    <div class="avatar-title bg-soft-primary rounded-circle fs-10">
                                                        {{ strtoupper(substr($episodio->medico->nome_completo, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <span class="fs-13 text-muted">Dr. {{ $episodio->medico->nome_completo }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $episodio->situacao == 'Aguardando Atendimento' ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                                                {{ strtoupper($episodio->situacao) }}
                                            </span> /
                                            <span class="badge {{ $episodio->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-light text-muted' }} px-3 py-1">
                                                <i class="ri-checkbox-blank-circle-fill fs-8 me-1"></i> {{ strtoupper($episodio->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('episodios.show', $episodio) }}" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light shadow-none">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
