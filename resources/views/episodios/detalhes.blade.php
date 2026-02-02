@extends('layouts.app')

@section('title', 'Atendimento: ' . $episodio->codigo_atendimento)

@section('content')
@php
    $isMedicoResponsavel = (auth()->user()->hasRole('Médico') && auth()->user()->medico && auth()->user()->medico->id == $episodio->medico_id);
    $podeEditar = ($episodio->situacao == 'Aberto' && $isMedicoResponsavel);
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded border-start border-primary border-3">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary d-flex align-items-center">
                <i class="ri-hospital-line me-1"></i> Episódio: {{ $episodio->codigo_atendimento }}

                @php
                    $corPrioridade = [
                        'Emergente'      => 'danger',
                        'Muito Urgente'  => 'warning',
                        'Urgente'        => 'info', // ou uma cor customizada para Amarelo
                        'Pouco Urgente'  => 'success',
                        'Não Urgente'    => 'primary'
                    ];
                    $classePrioridade = $corPrioridade[$episodio->prioridade] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $classePrioridade }} ms-3 fs-12 shadow-sm pulse-{{ $classePrioridade }}">
                    <i class="ri-alert-fill me-1"></i> PRIORIDADE: {{ strtoupper($episodio->prioridade) }}
                </span>
            </h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('episodios.index') }}" class="btn btn-light btn-label shadow-sm">
                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Voltar
                </a>

                @if($podeEditar)
                    <button type="button" class="btn btn-primary btn-label shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRequisitarExame">
                        <i class="ri-flask-line label-icon align-middle fs-16 me-2"></i> Requisitar Exames
                    </button>
                    <button type="button" class="btn btn-info btn-label shadow-sm" data-bs-toggle="modal" data-bs-target="#modalReceita">
                        <i class="ri-file-list-3-line label-icon align-middle fs-16 me-2"></i> Emitir Receita
                    </button>
                    <button type="button" class="btn btn-danger btn-label shadow-sm" onclick="finalizarAtendimento()">
                        <i class="ri-door-lock-line label-icon align-middle fs-16 me-2"></i> Finalizar Atendimento
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    {{-- Lado Esquerdo: Informações do Paciente e Atendimento --}}
    <div class="col-xxl-3 col-lg-4">
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="bg-primary-subtle" style="height: 60px;"></div>
            <div class="card-body text-center" style="margin-top: -30px;">
                <div class="avatar-md mx-auto mb-3 position-relative">
                    <div class="avatar-title bg-white text-primary rounded-circle fs-24 shadow-sm border border-2 border-{{ $classePrioridade }}">
                        {{ strtoupper(substr($episodio->paciente->nome_completo, 0, 1)) }}
                    </div>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-{{ $classePrioridade }} border border-white">
                        <i class="ri-error-warning-line"></i>
                    </span>
                </div>
                <h6 class="mb-1 fw-bold text-dark">{{ $episodio->paciente->nome_completo }}</h6>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-light text-muted border border-light-subtle fw-medium">
                        <i class="ri-fingerprint-line me-1"></i> {{ $episodio->paciente->numero_documento }}
                    </span>
                    <span class="badge {{ $episodio->paciente->genero == 'Masculino' ? 'bg-soft-info text-info' : 'bg-soft-danger text-danger' }}">
                        <i class="ri-{{ $episodio->paciente->genero == 'Masculino' ? 'men' : 'women' }}-line"></i>
                    </span>
                </div>
                <a href="{{ route('pacientes.show', $episodio->paciente) }}" class="btn btn-primary btn-sm w-100 shadow-sm">
                    <i class="ri-folder-user-line me-1"></i> Ver Histórico Clínico
                </a>
            </div>
            <div class="card-footer py-3 bg-light-subtle border-top-0">
                <div class="row justify-content-center text-center">
                    <div class="col-6 border-end">
                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">Idade Atual</p>
                        <h6 class="mb-0 fw-bold text-dark">{{ $episodio->paciente->data_nascimento->age }} Anos</h6>
                    </div>
                    @can('pacientes.informacoes_medicas')
                    <div class="col-6">
                        <p class="text-muted mb-1 fs-11 text-uppercase fw-bold">G. Sanguíneo</p>
                        <h6 class="mb-0 fw-bold text-danger">
                            <i class="ri-drop-fill me-1"></i>{{ $episodio->paciente->grupo_sanguineo ?? 'N/D' }}
                        </h6>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 border-{{ $classePrioridade }} mt-4">
            <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0 fw-bold"><i class="ri-pulse-line me-2 text-danger"></i>Sinais Vitais</h6>
                <span class="badge bg-{{ $classePrioridade }} text-uppercase">{{ $episodio->prioridade }}</span>
                <span class="badge bg-primary-subtle text-primary">Triagem</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Pressão Arterial</label>
                        <div class="d-flex align-items-center">
                            <i class="ri-heart-3-fill text-danger me-2 fs-18"></i>
                            <span class="fw-bold text-dark fs-15">
                                {{ $episodio->pa_sistolica ?? '--' }} / {{ $episodio->pa_diastolica ?? '--' }}
                                <small class="text-muted fw-normal">mmHg</small>
                            </span>
                        </div>
                    </div>

                    <div class="col-6 border-end">
                        <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Temperatura</label>
                        <div class="d-flex align-items-center">
                            <i class="ri-temp-hot-line text-warning me-2 fs-18"></i>
                            <span class="fw-bold text-dark">{{ $episodio->temperatura ?? '--' }}°C</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Frequência</label>
                        <div class="d-flex align-items-center">
                            <i class="ri-rest-time-line text-info me-2 fs-18"></i>
                            <span class="fw-bold text-dark">{{ $episodio->frequencia_cardiaca ?? '--' }} <small>BPM</small></span>
                        </div>
                    </div>

                    <div class="col-6 border-end border-top pt-2">
                        <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Peso</label>
                        <span class="fw-bold text-dark">{{ $episodio->peso ?? '--' }} kg</span>
                    </div>
                    <div class="col-6 border-top pt-2">
                        <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Altura</label>
                        <span class="fw-bold text-dark">{{ $episodio->altura ?? '--' }} m</span>
                    </div>

                    <div class="col-12 border-top pt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Saturação (SpO2)</label>
                                <span class="badge {{ ($episodio->saturacao < 95 && $episodio->saturacao != null) ? 'bg-danger' : 'bg-info' }} fs-12">
                                    {{ $episodio->saturacao ?? '--' }}%
                                </span>
                            </div>
                            <div class="text-end">
                                <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">IMC</label>
                                @if($episodio->peso && $episodio->altura)
                                    @php
                                        $imc = $episodio->peso / ($episodio->altura * $episodio->altura);
                                        $corImc = $imc > 25 ? 'text-danger' : ($imc < 18.5 ? 'text-warning' : 'text-success');
                                    @endphp
                                    <span class="fw-bold {{ $corImc }}">{{ number_format($imc, 1) }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light-subtle">
                <h6 class="card-title mb-0 fw-bold"><i class="ri-information-line me-2"></i>Info do Atendimento</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Médico Responsável</label>
                    <div class="d-flex align-items-center">
                        <i class="ri-user-star-fill text-warning me-2 fs-16"></i>
                        <span class="fw-medium text-dark">{{ $episodio->medico->genero == 'Masculino' ? 'Dr.' : 'Dra.' }} {{ $episodio->medico->nome_completo }}</span>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Estado | Situação</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge {{ $episodio->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-2 py-1">
                            {{ strtoupper($episodio->status) }}
                        </span>
                        <span class="badge {{ $episodio->situacao == 'Aberto' ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                            {{ strtoupper($episodio->situacao) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lado Direito: Evoluções e Alertas --}}
    <div class="col-xxl-9 col-lg-8">

        {{-- Alerta de Alergias --}}
        @if($episodio->paciente->alergias)
        <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm d-flex align-items-center mb-4">
            <i class="ri-error-warning-fill fs-24 me-3"></i>
            <div>
                <strong class="text-uppercase text-danger">Atenção - Alergias:</strong>
                <span class="ms-1 fw-medium text-dark">{{ $episodio->paciente->alergias }}</span>
            </div>
        </div>
        @endif

        {{-- ALERTA DE FECHAMENTO --}}
        @if($episodio->situacao == 'Fechado')
        <div class="alert alert-info border-0 border-start border-4 border-info shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="ri-lock-2-fill fs-24 me-3 text-info"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1 text-info text-uppercase fs-13">Atendimento Encerrado</h6>
                    <p class="mb-0 fs-12 text-dark">
                        Finalizado em: <b>{{ $episodio->data_fecho ? $episodio->data_fecho->format('d/m/Y H:i') : 'Data não registrada' }}</b>
                        por <b>{{ $episodio->usuarioFechamento->name ?? 'Sistema' }}</b>.
                    </p>
                    @if($episodio->observacoes_fechamento)
                        <hr class="my-2 opacity-25">
                        <p class="mb-0 fs-12 text-muted italic">Obs.: "{{ $episodio->observacoes_fechamento }}"</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Card da Linha do Tempo de Evoluções --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex align-items-center py-2 px-3">
                <div class="flex-grow-1">
                    <span class="badge bg-primary-subtle text-primary text-uppercase">Linha do Tempo de Evolução</span>
                </div>
                <div class="flex-shrink-0">
                    @if($podeEditar)
                        <button type="button" class="btn btn-primary btn-sm btn-label shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNotaClinica">
                            <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Nova Evolução
                        </button>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">
                <div class="timeline-2">
                    @forelse($episodio->notasClinicas->sortByDesc('created_at') as $nota)
                    <div class="timeline-continue">
                        <div class="row timeline-right">
                            <div class="col-12">
                                <div class="timeline-icon" style="z-index: 2;">
                                    <i class="ri-heart-pulse-line text-primary"></i>
                                </div>

                                <div class="card shadow-none border border-light-subtle mb-4 overflow-hidden" style="margin-left: 20px;">
                                    <div class="card-header bg-light d-flex align-items-center py-2 px-3 border-bottom-0">
                                        <div class="flex-grow-1">
                                            <span class="badge bg-primary-subtle text-primary text-uppercase">Nota Médica</span>
                                        </div>
                                        <div class="flex-shrink-0 d-flex align-items-center gap-2">
                                            @if($podeEditar && $nota->user_id == auth()->id())
                                                <button type="button" class="btn btn-sm btn-soft-info" onclick="abrirModalEdicao({{ json_encode($nota) }})">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                            @endif
                                            <small class="text-muted fw-bold">
                                                <i class="ri-calendar-event-line me-1"></i>{{ $nota->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="row g-4 mb-4">
                                            <div class="col-md-6 border-end border-light">
                                                <h6 class="text-primary fs-12 text-uppercase fw-bold mb-2">Queixa & História</h6>
                                                <p class="mb-1 text-dark fw-medium">{{ $nota->queixa_principal }}</p>
                                                <p class="text-muted fs-13 mb-0" style="white-space: pre-wrap;">{{ $nota->historia_doenca }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary fs-12 text-uppercase fw-bold mb-2">Exame Físico</h6>
                                                <p class="text-muted fs-13 mb-0">{{ $nota->exame_fisico ?? 'Nenhum achado registrado.' }}</p>
                                            </div>
                                        </div>

                                        <div class="row g-0 rounded-3 border border-primary-subtle overflow-hidden shadow-sm">
                                            <div class="col-md-5 bg-primary p-3 text-white">
                                                <label class="fs-11 text-uppercase fw-bold opacity-75 d-block mb-1">Hipótese Diagnóstica</label>
                                                <h5 class="text-white mb-0 fw-bold">{{ $nota->diagnostico_hipotese }}</h5>
                                            </div>
                                            <div class="col-md-7 bg-white p-3 border-start border-primary-subtle">
                                                <label class="fs-11 text-uppercase fw-bold text-primary d-block mb-1">Conduta / Plano</label>
                                                <p class="text-dark fs-13 mb-0 fw-medium">{{ $nota->plano_tratamento ?? 'Nenhuma conduta registrada.' }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-end">
                                            <small class="text-muted">Registado por: <b>{{ $nota->autor->name }}</b></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    {{-- MENSAGEM QUANDO NÃO HÁ NOTAS --}}
                    <div class="text-center py-5">
                        <h5 class="mt-4 fw-bold text-muted">Ainda não existem evoluções médicas para este episódio.</h5>
                        @if($podeEditar)
                            <button type="button" class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalNotaClinica">
                                <i class="ri-add-line align-middle me-1"></i> Adicionar Nota Inicial
                            </button>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Card de Exames Requisitados --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light d-flex align-items-center py-2 px-3">
                <h6 class="card-title mb-0 flex-grow-1 fw-bold text-primary">
                    <i class="ri-flask-line me-2"></i>Exames Requisitados neste Episódio
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-12">
                                <th>Código REQ</th>
                                <th>Prioridade</th>
                                <th>Status</th>
                                <th>Exames Selecionados</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($episodio->requisicoesExames->sortByDesc('created_at') as $requisicao)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $requisicao->codigo_requisicao }}</span>
                                    <div class="text-muted fs-11">{{ $requisicao->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    @if($requisicao->prioridade == 'urgente')
                                        <span class="badge bg-danger">URGENTE (STAT)</span>
                                    @else
                                        <span class="badge bg-info">Rotina</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pendente' => 'bg-warning-subtle text-warning',
                                            'em_coleta' => 'bg-info-subtle text-info',
                                            'laboratorio' => 'bg-primary-subtle text-primary',
                                            'concluido' => 'bg-success-subtle text-success',
                                            'cancelado' => 'bg-danger-subtle text-danger'
                                        ][$requisicao->status] ?? 'bg-light text-muted';
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-uppercase">
                                        {{ str_replace('_', ' ', $requisicao->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($requisicao->itens as $item)
                                            <span class="badge border border-light-subtle text-muted fw-normal">
                                                {{ $item->exame->nome }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="ri-printer-line me-2 align-bottom text-muted"></i> Imprimir Guia</a></li>
                                            @if($requisicao->status == 'concluido')
                                                <li><a class="dropdown-item" href="#"><i class="ri-file-list-3-line me-2 align-bottom text-muted"></i> Ver Resultados</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Nenhuma requisição de exame enviada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card de Receitas Emitidas --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light d-flex align-items-center py-2 px-3">
                <h6 class="card-title mb-0 flex-grow-1 fw-bold text-info">
                    <i class="ri-file-paper-2-line me-2"></i>Receitas Emitidas neste Episódio
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-12">
                                <th>Código</th>
                                <th>Data/Hora</th>
                                <th>Medicamentos</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($episodio->receitas->sortByDesc('created_at') as $receita)
                            <tr>
                                <td class="fw-bold text-primary">{{ $receita->codigo_receita }}</td>
                                <td>{{ $receita->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $receita->itens->count() }} item(ns)
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('receitas.imprimir', codificar($receita->id)) }}" target="_blank" class="btn btn-sm btn-soft-dark">
                                        <i class="ri-printer-line me-1"></i> Imprimir
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Nenhuma receita emitida.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAIS --}}
@if ($podeEditar)
{{-- Modal Novo Registro --}}
<div class="modal fade" id="modalNotaClinica" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary py-3">
                <h5 class="modal-title text-white fw-bold">Registar Nota Clínica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-nota">
                @csrf
                <input type="hidden" name="episodio_id" value="{{ $episodio->id }}">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Queixa Principal *</label>
                            <input type="text" class="form-control bg-light" name="queixa_principal" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">História da Doença Atual *</label>
                            <textarea class="form-control bg-light" rows="3" name="historia_doenca" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Exame Físico</label>
                            <textarea class="form-control bg-light" rows="3" name="exame_fisico"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hipótese de Diagnóstico *</label>
                            <textarea class="form-control bg-light" rows="3" name="diagnostico_hipotese" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Plano de Tratamento</label>
                            <textarea class="form-control bg-light" rows="2" name="plano_tratamento"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-ghost-danger shadow-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Registar Evolução</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edição --}}
<div class="modal fade" id="modalEditarNota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info py-3 text-white">
                <h5 class="modal-title fw-bold">Editar Nota Clínica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-editar-nota">
                @csrf
                @method('PUT')
                <input type="hidden" name="nota_id" id="edit_nota_id">
                <div class="modal-body p-4">
                    <div class="row g-3" id="edit-fields">
                        <div class="col-12">
                            <label class="form-label fw-bold">Queixa Principal *</label>
                            <input type="text" class="form-control" name="queixa_principal" id="edit_queixa_principal" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">História da Doença *</label>
                            <textarea class="form-control" rows="3" name="historia_doenca" id="edit_historia_doenca" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Exame Físico</label>
                            <textarea class="form-control" rows="3" name="exame_fisico" id="edit_exame_fisico"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hipótese Diagnóstica *</label>
                            <textarea class="form-control" rows="3" name="diagnostico_hipotese" id="edit_diagnostico_hipotese" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Conduta</label>
                            <textarea class="form-control" rows="2" name="plano_tratamento" id="edit_plano_tratamento"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-ghost-danger shadow-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4">Atualizar Evolução</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Finalizar Atendimento --}}
<div class="modal fade flip" id="finalizarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning-subtle p-3">
                <h5 class="modal-title text-warning fw-bold"><i class="ri-error-warning-line me-1"></i> Confirmar Encerramento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="finalizarForm">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-center">
                    <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#405189,secondary:#f7b84b" style="width:80px;height:80px"></lord-icon>
                    <div class="mt-4">
                        <h4 class="fw-bold">Deseja fechar este atendimento?</h4>
                        <p class="text-muted">A situação será mudada para <b>Fechado</b> e a data de encerramento será registrada agora.</p>
                        <div class="mb-3 text-start mt-4">
                            <label class="form-label fw-bold text-dark">Observações de Alta</label>
                            <textarea class="form-control bg-light" name="nota_final" rows="3" placeholder="Opcional..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-label shadow-sm">
                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Confirmar e Fechar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Receita --}}
<div class="modal fade" id="modalReceita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info py-3">
                <h5 class="modal-title text-white fw-bold"><i class="ri-capsule-line me-2"></i>Prescrever Medicamentos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-receita">
                @csrf
                <input type="hidden" name="episodio_id" value="{{ $episodio->id }}">

                <div class="modal-body p-4">
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle" id="tabela-itens-receita">
                            <thead class="table-light">
                                <tr class="text-uppercase fs-11">
                                    <th style="width: 35%;">Medicamento *</th>
                                    <th style="width: 15%;">Dosagem *</th>
                                    <th style="width: 20%;">Frequência *</th>
                                    <th style="width: 15%;">Duração</th>
                                    <th style="width: 10%;">Qtd *</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="itens[0][medicamento]" class="form-control form-control-sm" required placeholder="Medicamento"></td>
                                    <td><input type="text" name="itens[0][dosagem]" class="form-control form-control-sm" required placeholder="Ex: 500mg"></td>
                                    <td><input type="text" name="itens[0][frequencia]" class="form-control form-control-sm" required placeholder="Ex: 8/8h"></td>
                                    <td><input type="text" name="itens[0][duracao]" class="form-control form-control-sm" placeholder="Ex: 7 dias"></td>
                                    <td><input type="text" name="itens[0][quantidade]" class="form-control form-control-sm" required placeholder="Ex: 10 Compridos"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-start mt-2">
                        <button type="button" class="btn btn-soft-secondary btn-sm" onclick="addLinhaMedicamento()">
                            <i class="ri-add-line me-1"></i> Adicionar outro medicamento
                        </button>
                    </div>

                    <div class="mt-4">
                        <label class="form-label fw-bold">Observações Gerais (Instruções Adicionais)</label>
                        <textarea class="form-control bg-light" name="observacoes_gerais" rows="3" placeholder="Ex: Tomar em jejum, evitar bebidas alcoólicas, etc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info btn-label shadow-sm">
                        <i class="ri-save-3-line label-icon align-middle fs-16 me-2"></i> Guardar Receita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Requisitar Exame --}}
<div class="modal fade" id="modalRequisitarExame" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary py-3">
                <h5 class="modal-title text-white fw-bold"><i class="ri-flask-line me-2"></i>Nova Requisição de Exames</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-requisicao-exame">
                @csrf
                <input type="hidden" name="episodio_id" value="{{ $episodio->id }}">

                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4 bg-light border-end">
                            <div class="p-3">
                                <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 11px; letter-spacing: 1px;">Categorias</h6>
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    @foreach($categoriasExames as $index => $categoria)
                                        <button class="nav-link {{ $index == 0 ? 'active' : '' }} d-flex justify-content-between align-items-center py-2 px-3 mb-2 shadow-sm"
                                                id="tab-{{ $categoria->id }}" data-bs-toggle="pill" data-bs-target="#content-{{ $categoria->id }}" type="button" role="tab">
                                            <span><i class="ri-flask-line me-2"></i> {{ $categoria->nome }}</span>
                                            <span class="badge rounded-pill bg-primary-subtle text-primary">{{ $categoria->exames->count() }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="tab-content" id="v-pills-tabContent" style="min-height: 300px; max-height: 400px; overflow-y: auto;">
                                    @foreach($categoriasExames as $index => $categoria)
                                        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $categoria->id }}" role="tabpanel">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-grow-1 border-top"></div>
                                                <span class="mx-3 text-muted fw-semibold small text-uppercase">{{ $categoria->nome }}</span>
                                                <div class="flex-grow-1 border-top"></div>
                                            </div>

                                            <div class="row g-2">
                                                @foreach($categoria->exames as $exame)
                                                    <div class="col-sm-6">
                                                        <div class="exame-card">
                                                            <input class="form-check-input d-none" type="checkbox" name="exames_ids[]"
                                                                value="{{ $exame->id }}" id="exame_{{ $exame->id }}">
                                                            <label class="exame-label" for="exame_{{ $exame->id }}">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="avatar-xs flex-shrink-0 me-2">
                                                                        <div class="avatar-title bg-light rounded text-primary">
                                                                            {{ substr($exame->codigo, 0, 2) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <h6 class="text-truncate mb-1 fs-13">{{ $exame->nome }}</h6>
                                                                        <p class="text-muted text-truncate mb-0 small">{{ $exame->codigo }}</p>
                                                                    </div>
                                                                    @if($exame->requer_jejum)
                                                                        <i class="ri-rest-time-line text-warning" title="Requer Jejum"></i>
                                                                    @endif
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top p-4 bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><i class="ri-alarm-warning-line me-1 text-danger"></i> Prioridade</label>
                                <select class="form-select border-primary-subtle" name="prioridade">
                                    <option value="normal">Normal (Rotina)</option>
                                    <option value="urgente">Urgente (STAT)</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold"><i class="ri-chat-quote-line me-1 text-primary"></i> Justificação Clínica</label>
                                <textarea class="form-control" name="observacoes_clinicas" rows="2" placeholder="Ex: Paciente com febre há 3 dias..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white shadow-lg">
                    <div class="me-auto text-muted small">
                        <span id="contador-selecionados">0</span> exames selecionados
                    </div>
                    <button type="button" class="btn btn-link link-danger fw-medium" data-bs-dismiss="modal">Descartar</button>
                    <button type="submit" class="btn btn-primary btn-label shadow-sm">
                        <i class="ri-send-plane-2-fill label-icon align-middle fs-16 me-2"></i> Solicitar Agora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. REGISTAR NOVA NOTA
    $('#form-nota').on('submit', function(e) {
        e.preventDefault();
        enviarAjax($(this), "{{ route('notas_clinicas.store') }}", "Registro salvo!");
    });

    // 2. ATUALIZAR NOTA
    $('#form-editar-nota').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_nota_id').val();
        enviarAjax($(this), `/notas-clinicas/${id}/actualizar`, "Nota atualizada!");
    });

    // 3. FINALIZAR ATENDIMENTO
    $('#finalizarForm').on('submit', function(e) {
        e.preventDefault();
        enviarAjax($(this), "{{ route('episodios.finalizar', $episodio->id) }}", "Atendimento Encerrado!");
    });
});

// FUNÇÃO AJAX REUTILIZÁVEL
function enviarAjax(form, url, successTitle) {
    const btn = form.find('button[type="submit"]');
    const oldHtml = btn.html();

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>');

    $.ajax({
        url: url,
        method: 'POST',
        data: new FormData(form[0]),
        processData: false,
        contentType: false,
        success: function(res) {
            Swal.fire({ icon: 'success', title: successTitle, text: res.message, confirmButtonColor: '#0ab39c' })
                .then(() => location.reload());
        },
        error: function(xhr) {
            btn.prop('disabled', false).html(oldHtml);
            Swal.fire({ icon: 'error', title: 'Erro!', text: xhr.responseJSON?.message || 'Falha na operação.' });
        }
    });
}

function abrirModalEdicao(nota) {
    $('#edit_nota_id').val(nota.id);
    $('#edit_queixa_principal').val(nota.queixa_principal);
    $('#edit_historia_doenca').val(nota.historia_doenca);
    $('#edit_exame_fisico').val(nota.exame_fisico);
    $('#edit_diagnostico_hipotese').val(nota.diagnostico_hipotese);
    $('#edit_plano_tratamento').val(nota.plano_tratamento);
    $('#modalEditarNota').modal('show');
}

function finalizarAtendimento() {
    $('#finalizarModal').modal('show');
}

// 4. REGISTAR RECEITA
$('#form-receita').on('submit', function(e) {
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    const oldHtml = btn.html();

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>');

    $.ajax({
        url: "{{ route('receitas.store') }}",
        method: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(res) {
            $('#modalReceita').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Receita Gerada!',
                text: 'Deseja imprimir a receita agora?',
                showCancelButton: true,
                confirmButtonText: '<i class="ri-printer-line me-1"></i> Sim, Imprimir',
                cancelButtonText: 'Não, fechar',
                confirmButtonColor: '#0ab39c',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Abre a rota de impressão em nova aba (ajuste o nome da rota conforme seu web.php)
                    window.open(`/receitas/${res.id_receita}/imprimir`, '_blank');
                }
                location.reload();
            });
        },
        error: function(xhr) {
            btn.prop('disabled', false).html(oldHtml);
            let erroMsg = xhr.responseJSON?.message || 'Falha ao salvar receita.';
            Swal.fire({ icon: 'error', title: 'Erro!', text: erroMsg });
        }
    });
});

// Contador para índices únicos nos nomes dos inputs (ex: itens[1][medicamento])
let itemIndex = 1;

function addLinhaMedicamento() {
    const html = `
        <tr>
            <td>
                <input type="text" name="itens[${itemIndex}][medicamento]" class="form-control form-control-sm" required placeholder="Medicamento">
            </td>
            <td>
                <input type="text" name="itens[${itemIndex}][dosagem]" class="form-control form-control-sm" required placeholder="Ex: 500mg">
            </td>
            <td>
                <input type="text" name="itens[${itemIndex}][frequencia]" class="form-control form-control-sm" required placeholder="Ex: 8/8h">
            </td>
            <td>
                <input type="text" name="itens[${itemIndex}][duracao]" class="form-control form-control-sm" placeholder="Ex: 7 dias">
            </td>
            <td>
                <input type="text" name="itens[${itemIndex}][quantidade]" class="form-control form-control-sm" required placeholder="Ex: 10 Compridos">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-soft-danger" onclick="$(this).closest('tr').remove()">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>
    `;
    $('#tabela-itens-receita tbody').append(html);
    itemIndex++;
}

$(document).ready(function() {
    // Atualiza o contador de exames selecionados
    $(document).on('change', 'input[name="exames_ids[]"]', function() {
        let count = $('input[name="exames_ids[]"]:checked').length;
        $('#contador-selecionados').text(count).addClass('text-primary fw-bold');
        if(count > 0) {
            $('#contador-selecionados').parent().removeClass('text-muted').addClass('text-primary');
        } else {
            $('#contador-selecionados').parent().addClass('text-muted').removeClass('text-primary');
        }
    });

    // Submissão do Formulário
    $('#form-requisicao-exame').on('submit', function(e) {
        e.preventDefault();

        if ($('input[name="exames_ids[]"]:checked').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Nenhum exame selecionado',
                text: 'Por favor, escolha ao menos um exame para prosseguir.',
                confirmButtonColor: '#405189'
            });
            return;
        }

        enviarAjax($(this), "{{ route('requisicoes_exames.store') }}", "A requisição foi enviada ao laboratório!");
    });
});
</script>
@endpush

@push('styles')
<style>
    /* Estilo dos Cards de Exame */
.exame-card {
    position: relative;
}

.exame-label {
    display: block;
    cursor: pointer;
    background-color: #fff;
    border: 1px solid #e9ebec;
    border-radius: 8px;
    padding: 12px;
    transition: all 0.2s ease;
    height: 100%;
}

.exame-label:hover {
    border-color: #405189;
    background-color: #f8f9fa;
}

/* Quando o Checkbox estiver marcado */
.exame-card input:checked + .exame-label {
    background-color: #405189;
    border-color: #405189;
}

.exame-card input:checked + .exame-label h6,
.exame-card input:checked + .exame-label p,
.exame-card input:checked + .exame-label i {
    color: #fff !important;
}

.exame-card input:checked + .exame-label .avatar-title {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
}

/* Scrollbar fina para a lista */
.tab-content::-webkit-scrollbar { width: 4px; }
.tab-content::-webkit-scrollbar-thumb { background: #ced4da; border-radius: 10px; }
.pulse-danger {
    animation: pulse-red 2s infinite;
    box-shadow: 0 0 0 0 rgba(240, 101, 72, 0.7);
}

@keyframes pulse-red {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(240, 101, 72, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(240, 101, 72, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(240, 101, 72, 0); }
}
</style>
@endpush
