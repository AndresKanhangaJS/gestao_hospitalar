@extends('layouts.app')

@section('title', 'Perfil do Médico: ' . $medico->nome_completo)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded border-start border-primary border-3">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-nurse-line me-1"></i> Perfil do Profissional
            </h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('medicos.index') }}" class="btn btn-light btn-label shadow-sm">
                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Voltar
                </a>
                @can('medicos.editar')
                <a href="{{ route('medicos.edit', $medico) }}" class="btn btn-primary btn-label shadow-sm">
                    <i class="ri-pencil-fill label-icon align-middle fs-16 me-2"></i> Editar Dados
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xxl-3 col-lg-4">
        {{-- Card Principal de Identificação --}}
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="bg-primary" style="height: 70px;"></div>
            <div class="card-body text-center" style="margin-top: -40px;">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-white text-primary rounded-circle fs-32 shadow-sm border border-3 border-primary">
                        {{ strtoupper(substr($medico->nome_completo, 0, 1)) }}
                    </div>
                </div>
                <h5 class="mb-1 fw-bold text-dark">{{ $medico->nome_completo }}</h5>
                <p class="text-primary fw-medium mb-1">{{ $medico->especialidade ?? 'Clínico Geral' }}</p>
                <p class="text-muted fs-12 mb-3">Nº de Ordem: <span class="fw-bold">{{ $medico->numero_ordem }}</span></p>

                <div class="d-flex justify-content-center gap-2 mb-0">
                    <span class="badge {{ $medico->status == 'activo' ? 'bg-success' : 'bg-danger' }} px-3 py-2 shadow-sm text-uppercase">
                        {{ $medico->status }}
                    </span>
                </div>
            </div>
            <div class="card-footer py-3 bg-light-subtle border-top-0">
                <div class="row text-center">
                    <div class="col-12">
                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Atendimentos Realizados</p>
                        <h6 class="mb-0 fw-bold text-dark fs-16">{{ $medico->episodios_count ?? 0 }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informações de Contacto --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light-subtle py-3">
                <h6 class="card-title mb-0 fw-bold text-uppercase fs-12">
                    <i class="ri-contacts-book-line me-2 align-middle text-primary"></i>Contactos e Endereço
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 avatar-sm">
                        <div class="avatar-title bg-soft-primary rounded-circle fs-18">
                            <i class="ri-phone-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 fw-bold text-dark">{{ $medico->telefone ?? 'Não informado' }}</h6>
                        <p class="text-muted mb-0 fs-12">Telefone</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 avatar-sm">
                        <div class="avatar-title bg-soft-primary rounded-circle fs-18">
                            <i class="ri-mail-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-14 mb-1 fw-bold text-dark text-break">{{ $medico->email ?? 'N/D' }}</h6>
                        <p class="text-muted mb-0 fs-12">E-mail</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 avatar-sm">
                        <div class="avatar-title bg-soft-primary rounded-circle fs-18">
                            <i class="ri-map-pin-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-13 mb-1 fw-bold text-dark lh-base">{{ $medico->morada ?? 'Endereço não registado' }}</h6>
                        <p class="text-muted mb-0 fs-12">Morada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-9 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs-custom nav-success border-bottom-0 ms-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-3" data-bs-toggle="tab" href="#dados-detalhados" role="tab">
                            <i class="ri-user-settings-line me-1 align-bottom"></i> Informações Pessoais e Profissionais
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3" data-bs-toggle="tab" href="#atendimentos" role="tab">
                            <i class="ri-history-line me-1 align-bottom"></i> Histórico de Atendimentos
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="dados-detalhados" role="tabpanel">
                        <div class="row g-4">
                            {{-- Dados de Identificação extraídos da Migration --}}
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <div class="avatar-title bg-soft-info rounded-circle fs-16">
                                            <i class="ri-fingerprint-2-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Identificação Civil</p>
                                        <h6 class="fs-14 mb-0"><span class="fw-bold">{{ $medico->tipo_documento }}</span>: {{ $medico->numero_documento }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <div class="avatar-title bg-soft-info rounded-circle fs-16">
                                            <i class="ri-calendar-event-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Data de Nascimento</p>
                                        <h6 class="fs-14 mb-0 text-dark fw-bold">
                                            @if($medico->data_nascimento instanceof \Carbon\Carbon)
                                                {{ $medico->data_nascimento->format('d/m/Y') }} ({{ $medico->data_nascimento->age }} anos)
                                            @else
                                                {{ \Carbon\Carbon::parse($medico->data_nascimento)->format('d/m/Y') }}
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs flex-shrink-0 me-3">
                                        <div class="avatar-title rounded-circle fs-16">
                                            <i class="{{ $medico->genero == 'Masculino' ? 'ri-men-line' : 'ri-women-line' }}"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Género / Sexo</p>
                                        <h6 class="fs-14 mb-0 text-dark fw-bold text-capitalize">{{ $medico->genero }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h6 class="fs-12 text-muted mb-3 text-uppercase fw-bold">
                                <i class="ri-shield-check-line me-1 align-middle text-success"></i> Auditoria de Sistema
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6 col-xxl-4">
                                    <div class="p-3 border border-light rounded bg-light-subtle d-flex align-items-center">
                                        <i class="ri-user-add-line fs-20 text-muted me-3"></i>
                                        <div>
                                            <p class="text-muted mb-0 fs-11">Registado por: <b>{{ $medico->criador->name ?? 'Administrador' }}</b></p>
                                            <p class="text-muted mb-0 fs-11">Data: {{ $medico->created_at->format('d/m/Y \à\s H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if($medico->user_id_atualizacao)
                                <div class="col-md-6 col-xxl-4">
                                    <div class="p-3 border border-light rounded bg-light-subtle d-flex align-items-center">
                                        <i class="ri-edit-2-line fs-20 text-muted me-3"></i>
                                        <div>
                                            <p class="text-muted mb-0 fs-11">Última atualização por:</p>
                                            <p class="text-muted mb-0 fs-11 fw-bold">{{ $medico->atualizador->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="atendimentos" role="tabpanel">
                        <div class="table-responsive mt-2">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="bg-light-subtle border-bottom">
                                    <tr class="text-muted fs-11 text-uppercase">
                                        <th class="ps-3">Data/Hora</th>
                                        <th>Paciente</th>
                                        <th>Tipo de Episódio</th>
                                        <th class="text-end pe-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($medico->episodios as $episodio)
                                    <tr>
                                        <td class="ps-3">
                                            <h6 class="fs-13 mb-0 fw-bold">{{ $episodio->created_at->format('d/m/Y') }}</h6>
                                            <small class="text-muted">{{ $episodio->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-medium text-dark">{{ $episodio->paciente->nome_completo }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-primary">{{ $episodio->tipo }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('episodios.show', $episodio->id) }}" class="btn btn-sm btn-outline-primary btn-icon">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Nenhum histórico de atendimento encontrado para este médico.</td>
                                    </tr>
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
