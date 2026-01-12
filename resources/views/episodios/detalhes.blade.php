@extends('layouts.app')

@section('title', 'Atendimento: ' . $episodio->codigo_atendimento)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded border-start border-primary border-3">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-hospital-line me-1"></i> Episódio: {{ $episodio->codigo_atendimento }}
            </h4>
            <div class="page-title-right d-flex gap-2">
                <a href="{{ route('episodios.index') }}" class="btn btn-light btn-label shadow-sm">
                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Voltar
                </a>

                @if($episodio->situacao == 'Aberto')
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
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-white text-primary rounded-circle fs-24 shadow-sm border border-2 border-primary">
                        {{ strtoupper(substr($episodio->paciente->nome_completo, 0, 1)) }}
                    </div>
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
                <div class="row text-center">
                    <div class="col-6 border-end text-truncate">
                        <p class="text-muted mb-1 fs-11 text-uppercase">Idade</p>
                        <h6 class="mb-0 fw-bold fs-13">{{ $episodio->paciente->data_nascimento->age }} anos</h6>
                    </div>
                    <div class="col-6 text-truncate">
                        <p class="text-muted mb-1 fs-11 text-uppercase">G. Sanguíneo</p>
                        <h6 class="mb-0 fw-bold text-danger fs-13">
                            <i class="ri-drop-fill"></i> {{ $episodio->paciente->grupo_sanguineo ?? 'N/D' }}
                        </h6>
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

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex align-items-center py-2 px-3">
                <div class="flex-grow-1">
                    <span class="badge bg-primary-subtle text-primary text-uppercase">Linha do Tempo de Evolução</span>
                </div>
                <div class="flex-shrink-0">
                    @if($episodio->situacao == 'Aberto')
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
                                            @if($episodio->situacao == 'Aberto')
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
                        @if($episodio->situacao == 'Aberto')
                            <button type="button" class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalNotaClinica">
                                <i class="ri-add-line align-middle me-1"></i> Adicionar Nota Inicial
                            </button>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAIS --}}

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
</script>
@endpush
