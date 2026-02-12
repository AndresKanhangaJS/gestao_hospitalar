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
                <form action="{{ route('episodios.index') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-xxl-4 col-sm-12">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control search bg-white border-light"
                                    placeholder="Paciente, BI ou Cód. Atendimento...">
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
                            <select class="form-select bg-white border-light" name="situacao">
                                <option value="">Situação (Todas)</option>
                                <option value="Aguardando Triagem" {{ request('situacao') == 'Aguardando Triagem' ? 'selected' : '' }}>🟡 Aguardando Triagem</option>
                                <option value="Aguardando Atendimento" {{ request('situacao') == 'Aguardando Atendimento' ? 'selected' : '' }}>🔵 Aguardando Atendimento</option>
                                <option value="Fechado" {{ request('situacao') == 'Fechado' ? 'selected' : '' }}>🔴 Fechado</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-6 d-flex gap-2 justify-content-end align-items-end">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm flex-grow-1">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Filtrar
                            </button>
                            <a href="{{ route('episodios.index') }}" class="btn btn-soft-danger px-4 shadow-sm">
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
                                <th scope="col">Prioridade</th>
                                <th scope="col">Médico / Estado</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($episodios as $episodio)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-soft-primary fw-bold">
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
                                    @if($episodio->prioridade)
                                        @php
                                            $corBadge = [
                                                'Emergente' => 'bg-danger',
                                                'Muito Urgente' => 'bg-warning text-dark',
                                                'Urgente' => 'bg-warning text-dark',
                                                'Pouco Urgente' => 'bg-success',
                                                'Não Urgente' => 'bg-info',
                                            ][$episodio->prioridade] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $corBadge }} shadow-sm">
                                            <i class="ri-checkbox-blank-circle-fill me-1 fs-10"></i> {{ $episodio->prioridade }}
                                        </span>
                                    @else
                                        <span class="text-muted small italic">Não classificado</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column gap-1">
                                        @if($episodio->medico)
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fw-bold text-dark">
                                                    <i class="ri-user-star-line me-1 text-primary"></i>
                                                    {{ $episodio->medico->genero == 'Masculino' ? 'Dr.' : 'Dra.' }} {{ $episodio->medico->nome_completo }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="text-muted italic fs-13">
                                                    <i class="ri-user-search-line me-1"></i> Médico não atribuído
                                                </span>
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $episodio->situacao == 'Aguardando Triagem' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' }} border border-{{ $episodio->situacao == 'Aguardando Triagem' ? 'warning' : 'success' }}-subtle px-2">
                                                @if($episodio->situacao == 'Aguardando Triagem')
                                                    <i class="ri-loader-2-line ri-spin me-1"></i>
                                                @endif
                                                {{ $episodio->situacao }}
                                            </span>

                                            @if($episodio->profissionalTriagem)
                                                <small class="text-muted border-start ps-2">
                                                    Triado por: <span class="fw-medium">{{ $episodio->profissionalTriagem->name ?? ''}}</span>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $episodio->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-uppercase">
                                        {{ $episodio->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 justify-content-center">
                                        @can('episodios.detalhes')
                                        <a href="{{ route('episodios.show', $episodio) }}" class="btn btn-sm btn-soft-info" title="Ver detalhes">
                                            <i class="ri-eye-fill"></i>
                                        </a>
                                        @endcan
                                        @if($episodio->situacao == 'Aguardando Triagem')
                                            @can('pacientes.fazer_triagem')
                                                <button type="button" class="btn btn-sm btn-soft-success" data-bs-toggle="modal" data-bs-target="#modalTriagem{{ $episodio->id }}">
                                                    <i class="ri-heart-pulse-line me-1"></i>Fazer Triagem
                                                </button>
                                            @endcan
                                        @else
                                            @can('pacientes.triagem')
                                                <button type="button" class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#modalVerTriagem{{ $episodio->id }}">
                                                    <i class="ri-file-list-3-line me-1"></i>Dados da Triagem
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- FAZER TRIAGEM --}}
                            <div class="modal fade" id="modalTriagem{{ $episodio->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header bg-success py-3">
                                            <h5 class="modal-title text-white fw-bold">
                                                <i class="ri-pulse-line me-2"></i> Triagem Clínica
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <form action="{{ route('episodios.triagem', $episodio->id) }}" method="POST" class="form-triagem">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">

                                                <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 14px;">
                                                    <i class="ri-heart-pulse-line me-2 fs-20"></i> Sinais Vitais ({{ $episodio->paciente->nome_completo }})
                                                </h5>

                                                <div class="row g-3">
                                                    <div class="col-lg-6 col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">PRESSÃO ARTERIAL (PA)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text border-light bg-light"><i class="ri-heart-3-line text-danger"></i></span>
                                                            <input type="text" name="pa_sistolica" class="form-control border-light bg-light" placeholder="120" required>
                                                            <span class="input-group-text border-light bg-light">/</span>
                                                            <input type="text" name="pa_diastolica" class="form-control border-light bg-light" placeholder="80" required>
                                                            <span class="input-group-text border-light bg-light">mmHg</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">TEMPERATURA</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text border-light bg-light"><i class="ri-temp-hot-line text-warning"></i></span>
                                                            <input type="number" step="0.1" name="temperatura" class="form-control border-light bg-light" placeholder="36.5">
                                                            <span class="input-group-text border-light bg-light">°C</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">PESO</label>
                                                        <div class="input-group">
                                                            <input type="number" step="0.01" name="peso" class="form-control border-light bg-light" placeholder="70.0">
                                                            <span class="input-group-text border-light bg-light">kg</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">ALTURA</label>
                                                        <div class="input-group">
                                                            <input type="number" step="0.01" name="altura" class="form-control border-light bg-light" placeholder="1.75">
                                                            <span class="input-group-text border-light bg-light">m</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6">
                                                        <label class="form-label fw-semibold text-muted small">FREQ. CARDÍACA</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text border-light bg-light"><i class="ri-pulse-line text-info"></i></span>
                                                            <input type="number" name="frequencia_cardiaca" class="form-control border-light bg-light" placeholder="BPM">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-5 col-md-12">
                                                        <label class="form-label fw-semibold text-muted small">SATURAÇÃO</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text border-light bg-light"><i class="ri-rest-time-line text-info"></i></span>
                                                            <input type="number" name="saturacao" class="form-control border-light bg-light" placeholder="SpO2 %">
                                                        </div>
                                                    </div>
                                                </div>

                                                <h5 class="card-title text-primary border-bottom pb-2 mt-4 mb-3 d-flex align-items-center" style="font-size: 13px;">
                                                    <i class="ri-user-follow-line me-2"></i> DESTINO E ENCAMINHAMENTO
                                                </h5>

                                                <div class="row g-3">
                                                    <div class="col-md-5">
                                                        <label class="form-label fw-bold">Médico Responsável</label>
                                                        <input type="text" class="form-control border-light bg-light-subtle fw-bold" value="{{ $episodio->medico->nome_completo ?? 'Não atribuído'}}" readonly>

                                                        {{-- <select class="form-select border-light bg-light" name="medico_id" required>
                                                            <option value="">Selecione o médico para o atendimento...</option>
                                                            @foreach($medicos as $medico)
                                                                <option value="{{ $medico->id }}">
                                                                    {{ $medico->genero == 'Masculino' ? 'Dr.' : 'Dra.' }} {{ $medico->nome_completo }}
                                                                </option>
                                                            @endforeach
                                                        </select> --}}
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold">Tipo de Atendimento</label>
                                                        <input type="text" class="form-control border-light bg-light-subtle fw-bold" value="{{ $episodio->tipoAtendimento->nome ?? 'Não atribuído'}}" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="prioridade" class="form-label fw-semibold text-muted small">PRIORIDADE <span class="text-danger">*</span></label>
                                                        <select class="form-select border-light bg-light fw-bold" name="prioridade" id="prioridade" required onchange="updatePriorityColor(this)">
                                                            <option value="" selected disabled>Classificar...</option>
                                                            <option value="Emergente" data-color="#f06548">🔴 Emergente</option>
                                                            <option value="Muito Urgente" data-color="#ffbe0b">🟠 Muito Urgente</option>
                                                            <option value="Urgente" data-color="#f7cc53">🟡 Urgente</option>
                                                            <option value="Pouco Urgente" data-color="#45cb85">🟢 Pouco Urgente</option>
                                                            <option value="Não Urgente" data-color="#3577f1">🔵 Não Urgente</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold">Queixas / Observações de Triagem</label>
                                                        <textarea class="form-control border-light bg-light" name="observacoes_triagem" rows="3" placeholder="Descreva as queixas principais do paciente..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-link link-danger" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-success btn-label shadow-sm">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                        <span>Concluir e Encaminhar</span>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @if($episodio->situacao !== 'Aguardando Triagem')
                            {{-- DADOS DA TRIAGEM --}}
                            <div class="modal fade" id="modalVerTriagem{{ $episodio->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header py-3">
                                            <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <div class="text-center mb-4">
                                                <h5 class="fw-bold mb-1">{{ $episodio->paciente->nome_completo }}</h5>

                                                <div class="mt-2">
                                                    @php
                                                        $corPrioridade = [
                                                            'Emergente' => 'bg-danger',
                                                            'Muito Urgente' => 'bg-warning text-dark',
                                                            'Urgente' => 'bg-warning text-dark',
                                                            'Pouco Urgente' => 'bg-success',
                                                            'Não Urgente' => 'bg-info',
                                                        ][$episodio->prioridade] ?? 'bg-secondary';

                                                        $iconPrioridade = ($episodio->prioridade == 'Emergente') ? 'ri-alarm-warning-fill' : 'ri-checkbox-blank-circle-fill';
                                                    @endphp
                                                    <span class="badge {{ $corPrioridade }} p-2 fs-12 shadow-sm">
                                                        <i class="{{ $iconPrioridade }} me-1"></i> PRIORIDADE: {{ strtoupper($episodio->prioridade) }}
                                                    </span>
                                                </div>

                                                <div class="mt-2">
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill">Resumo da Triagem</span>
                                                </div>
                                            </div>

                                            <div class="row g-4 text-center mb-4">
                                                <div class="col-4">
                                                    <p class="text-muted mb-1 small text-uppercase">P. Arterial</p>
                                                    <h5 class="fw-bold mb-0 text-danger">{{ $episodio->pa_sistolica }}/{{ $episodio->pa_diastolica }}</h5>
                                                    <small class="text-muted">mmHg</small>
                                                </div>
                                                <div class="col-4 border-start border-end">
                                                    <p class="text-muted mb-1 small text-uppercase">Temperatura</p>
                                                    <h5 class="fw-bold mb-0 text-warning">{{ $episodio->temperatura }}°C</h5>
                                                </div>
                                                <div class="col-4">
                                                    <p class="text-muted mb-1 small text-uppercase">Freq. Card.</p>
                                                    <h5 class="fw-bold mb-0 text-info">{{ $episodio->frequencia_cardiaca ?? '--' }}</h5>
                                                    <small class="text-muted">BPM</small>
                                                </div>
                                            </div>

                                            <hr class="text-muted opacity-10">

                                            <div class="row g-3 mb-4">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri- droplet-fill text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Saturação</small>
                                                            <span class="fw-bold">{{ $episodio->saturacao }}%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-scales-3-line text-primary me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Peso / Altura</small>
                                                            <span class="fw-bold">{{ $episodio->peso }}kg / {{ $episodio->altura }}m</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-light rounded-3 mb-4">
                                                <small class="text-muted text-uppercase fw-bold mb-2 d-block" style="font-size: 10px;">Observações</small>
                                                <p class="mb-0 text-dark small" style="line-height: 1.4;">
                                                    {{ $episodio->observacoes_triagem }}
                                                </p>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-end border-top pt-3">
                                                <div>
                                                    <small class="text-muted d-block small">Responsável:</small>
                                                    <span class="fw-medium">{{ $episodio->profissionalTriagem->name ?? '' }}</span>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-block small">Data de Triagem:</small>
                                                    <small class=""><i class="ri-time-line me-1 fs-11"></i>{{ $episodio->data_triagem }}</small>
                                                </div>
                                            </div>

                                            <div class="border-top pt-3">
                                                <small class="text-muted d-block small">Encaminhado para:</small>
                                                <span class="fw-bold text-primary">{{ $episodio->medico->nome_completo }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <lord-icon src="https://cdn.lordicon.com/vlynuwvu.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 text-muted">Nenhum episódio encontrado.</h5>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('.form-triagem').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        const modal = form.closest('.modal');
        const formData = new FormData(this);

        // 1. Salva o conteúdo original para restaurar depois
        const originalContent = btnSubmit.html();

        // 2. Limpeza de erros prévios
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();

        // 3. ATIVAÇÃO DO SPINNER (Solução definitiva)
        // Travamos a largura do botão para ele não "encolher" ao mudar o texto
        btnSubmit.css('width', btnSubmit.outerWidth());
        btnSubmit.prop('disabled', true);

        // Injetamos um HTML limpo com o spinner do Bootstrap
        btnSubmit.html(`
            <span class="d-flex align-items-center justify-content-center">
                <span class="spinner-border spinner-border-sm flex-shrink-0 me-2" role="status"></span>
                <span>A processar...</span>
            </span>
        `);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                modal.modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Triagem Concluída!',
                    text: response.message,
                    confirmButtonColor: '#0ab39c',
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                // 4. RESTAURA O BOTÃO EM CASO DE ERRO
                btnSubmit.prop('disabled', false).html(originalContent).css('width', 'auto');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const input = form.find(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        const errorMessage = `<div class="invalid-feedback d-block">${errors[key][0]}</div>`;

                        if (input.closest('.input-group').length > 0) {
                            input.closest('.input-group').after(errorMessage);
                        } else {
                            input.after(errorMessage);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: xhr.responseJSON.message || 'Ocorreu um erro ao salvar.'
                    });
                }
            }
        });
    });
});

function updatePriorityColor(select) {
    const selectedOption = select.options[select.selectedIndex];
    const color = selectedOption.getAttribute('data-color');

    // Altera a cor do texto e borda do select para dar destaque
    if (color) {
        select.style.borderColor = color;
        select.style.color = color;
    } else {
        select.style.borderColor = "#ced4da";
        select.style.color = "#212529";
    }
}
</script>
@endpush
@push('styles')
<style>
.btn-label .spinner-border {
    width: 1rem;
    height: 1rem;
    vertical-align: middle;
}
</style>
@endpush
