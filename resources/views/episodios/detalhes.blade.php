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

                <div class="btn-group">
                    <button type="button" class="btn btn-info btn-label shadow-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ri-printer-line label-icon align-middle fs-16 me-2"></i> Imprimir
                    </button>
                    <div class="dropdown-menu shadow-lg">
                        <a class="dropdown-item" href="#"><i class="ri-file-list-3-line me-2 align-middle text-muted"></i>Resumo do Episódio</a>
                        <a class="dropdown-item" href="#"><i class="ri-capsule-line me-2 align-middle text-muted"></i>Receituário</a>
                        <a class="dropdown-item" href="#"><i class="ri-test-tube-line me-2 align-middle text-muted"></i>Requisição de Exames</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
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

                <a href="{{ route('pacientes.show', $episodio->paciente->id) }}" class="btn btn-primary btn-sm w-100 shadow-sm">
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
                        <span class="fw-medium text-dark">Dr. {{ $episodio->medico->name }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted fs-11 text-uppercase fw-bold mb-1 d-block">Estado e Abertura</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge {{ $episodio->situacao == 'Aberto' ? 'bg-success' : 'bg-secondary' }} px-3">
                            {{ strtoupper($episodio->situacao) }}
                        </span>
                        <small class="text-muted fs-11 fw-medium">{{ $episodio->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-9 col-lg-8">
        @if($episodio->paciente->alergias)
        <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="ri-error-warning-fill fs-24 me-3"></i>
            <div>
                <strong class="text-uppercase text-danger">Atenção - Alergias:</strong>
                <span class="ms-1 fw-medium text-dark">{{ $episodio->paciente->alergias }}</span>
            </div>
        </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex align-items-center py-2 px-3 border-bottom-0">
                <div class="flex-grow-1">
                    <span class="badge bg-primary-subtle text-primary text-uppercase">Evolução Médica</span>
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
                                            <span class="badge bg-primary-subtle text-primary text-uppercase">Evolução Médica</span>
                                        </div>
                                        <div class="flex-shrink-0 d-flex align-items-center gap-2">
                                            @if($episodio->situacao == 'Aberto')
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-info"
                                                    onclick="abrirModalEdicao({{ json_encode($nota) }})"
                                                    title="Editar Nota">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                            @endif
                                            <small class="text-muted fw-bold">
                                                <i class="ri-calendar-event-line me-1"></i>{{ $nota->created_at->format('d/m/Y') }}
                                                <i class="ri-time-line ms-2 me-1"></i>{{ $nota->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="row g-4 mb-4">
                                            <div class="col-md-6 border-end border-light">
                                                <h6 class="text-primary fs-12 text-uppercase fw-bold mb-2">
                                                    <i class="ri-chat-voice-line me-1"></i> Queixa & História
                                                </h6>
                                                <p class="mb-1 text-dark fw-medium">{{ $nota->queixa_principal }}</p>
                                                <p class="text-muted fs-13 mb-0" style="white-space: pre-wrap;">{{ $nota->historia_doenca }}</p>
                                            </div>

                                            <div class="col-md-6">
                                                <h6 class="text-primary fs-12 text-uppercase fw-bold mb-2">
                                                    <i class="ri-body-scan-line me-1"></i> Exame Físico
                                                </h6>
                                                <p class="text-muted fs-13 mb-0">
                                                    {{ $nota->exame_fisico ?? 'Nenhum achado registrado.' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row g-0 rounded-3 border border-primary-subtle overflow-hidden shadow-sm">
                                            <div class="col-md-5 bg-primary p-3">
                                                <label class="fs-11 text-uppercase fw-bold text-white-50 d-block mb-1">Hipótese de Diagnóstico</label>
                                                <h5 class="text-white mb-0 fw-bold">
                                                    <i class="ri-focus-3-line me-2"></i>{{ $nota->diagnostico_hipotese }}
                                                </h5>
                                            </div>
                                            <div class="col-md-7 bg-white p-3 border-start border-primary-subtle">
                                                <label class="fs-11 text-uppercase fw-bold text-primary d-block mb-1">Conduta / Plano de Tratamento</label>
                                                <p class="text-dark fs-13 mb-0 fw-medium">
                                                    <i class="ri-capsule-fill text-primary me-2"></i>{{ $nota->plano_tratamento ?? 'Nenhuma conduta registada.' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-2 border-top border-dashed d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center text-muted fs-11">
                                                <i class="ri-shield-check-line me-1"></i> Documento Autenticado via Sistema
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="text-end me-3">
                                                    <small class="text-muted d-block fs-11">Registado por:</small>
                                                    <span class="text-dark fw-bold fs-13">Dr. {{ $nota->autor->name }}</span>
                                                </div>
                                                <div class="avatar-xs">
                                                    <div class="avatar-title rounded-circle bg-primary text-white shadow-sm">
                                                        {{ strtoupper(substr($nota->autor->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Registar Nota Clínica --}}
<div class="modal fade" id="modalNotaClinica" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary py-3">
                <h5 class="modal-title text-white fw-bold"><i class="ri-add-circle-line me-2"></i>Registar Nota Clínica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-nota">
                @csrf
                <input type="hidden" name="episodio_id" value="{{ $episodio->id }}">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Queixa Principal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light border-light" name="queixa_principal" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">História da Doença Atual <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-light" rows="3" name="historia_doenca" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Exame Físico</label>
                            <textarea class="form-control bg-light border-light" rows="3" name="exame_fisico"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hipótese de Diagnóstico <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-light" rows="3" name="diagnostico_hipotese" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Plano de Tratamento/Conduta</label>
                            <textarea class="form-control bg-light border-light" rows="2" name="plano_tratamento"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light-subtle">
                    <button type="button" class="btn btn-ghost-danger shadow-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="ri-save-line align-bottom me-1"></i> Registar Evolução
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Editar Nota Clínica --}}
<div class="modal fade" id="modalEditarNota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info py-3">
                <h5 class="modal-title text-white fw-bold"><i class="ri-edit-2-line me-2"></i>Editar Nota Clínica</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-editar-nota">
                @csrf
                @method('PUT')
                <input type="hidden" name="nota_id" id="edit_nota_id">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Queixa Principal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light border-light" name="queixa_principal" id="edit_queixa_principal" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">História da Doença Atual <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-light" rows="3" name="historia_doenca" id="edit_historia_doenca" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Exame Físico</label>
                            <textarea class="form-control bg-light border-light" rows="3" name="exame_fisico" id="edit_exame_fisico"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hipótese de Diagnóstico <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-light" rows="3" name="diagnostico_hipotese" id="edit_diagnostico_hipotese" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Plano de Tratamento/Conduta</label>
                            <textarea class="form-control bg-light border-light" rows="2" name="plano_tratamento" id="edit_plano_tratamento"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light-subtle">
                    <button type="button" class="btn btn-ghost-danger shadow-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 shadow-sm">
                        <i class="ri-refresh-line align-bottom me-1"></i> Atualizar Evolução
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// $(document).ready(function() {
//     $('#form-nota').on('submit', function(e) {
//         e.preventDefault();
//         const form = $(this);
//         const btnSubmit = form.find('button[type="submit"]');
//         const formData = new FormData(this);

//         // Limpar erros anteriores
//         $('.form-control').removeClass('is-invalid');
//         $('.invalid-feedback, .text-danger.small').remove();

//         // Feedback visual
//         btnSubmit.prop('disabled', true)
//                  .html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

//         $.ajax({
//             url: "{{ route('notas_clinicas.store') }}", // Ajuste para sua rota
//             method: 'POST',
//             data: formData,
//             processData: false,
//             contentType: false,
//             dataType: 'json',
//             success: function(response) {
//                 $('#modalNotaClinica').modal('hide');

//                 Swal.fire({
//                     icon: 'success',
//                     title: 'Registro Concluído!',
//                     text: response.message,
//                     confirmButtonColor: '#0ab39c',
//                     confirmButtonText: 'Excelente'
//                 }).then(() => {
//                     location.reload(); // Recarrega para mostrar a nova nota na timeline
//                 });
//             },
//             error: function(xhr) {
//                 btnSubmit.prop('disabled', false).html('Gravar Evolução');

//                 if (xhr.status === 422) {
//                     const errors = xhr.responseJSON.errors;
//                     Object.keys(errors).forEach(key => {
//                         const input = $(`[name="${key}"]`);
//                         input.addClass('is-invalid');
//                         input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
//                     });
//                 } else {
//                     Swal.fire({
//                         icon: 'error',
//                         title: 'Falha no Registro',
//                         text: xhr.responseJSON.message || 'Erro interno no servidor.'
//                     });
//                 }
//             }
//         });
//     });
// });
// --- FUNÇÃO PARA ABRIR E PREENCHER O MODAL DE EDIÇÃO ---
function abrirModalEdicao(nota) {
    $('#edit_nota_id').val(nota.id);
    $('#edit_queixa_principal').val(nota.queixa_principal);
    $('#edit_historia_doenca').val(nota.historia_doenca);
    $('#edit_exame_fisico').val(nota.exame_fisico);
    $('#edit_diagnostico_hipotese').val(nota.diagnostico_hipotese);
    $('#edit_plano_tratamento').val(nota.plano_tratamento);

    $('#modalEditarNota').modal('show');
}

$(document).ready(function() {
    // --- LÓGICA DE REGISTO (MANUTENÇÃO DO QUE JÁ EXISTE) ---
    $('#form-nota').on('submit', function(e) {
        e.preventDefault();
        enviarFormulario($(this), "{{ route('notas_clinicas.store') }}", 'POST');
    });

    // --- LÓGICA DE EDIÇÃO (TOTALMENTE SEPARADA) ---
    $('#form-editar-nota').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_nota_id').val();
        const urlUpdate = `/notas-clinicas/${id}/actualizar`;
        enviarFormulario($(this), urlUpdate, 'POST'); // POST com @method('PUT') no HTML
    });

    // Função auxiliar para evitar repetição de código AJAX
    function enviarFormulario(form, url, method) {
        const btnSubmit = form.find('button[type="submit"]');
        const formData = new FormData(form[0]);

        $('.form-control').removeClass('is-invalid');
        btnSubmit.prop('disabled', true).prepend('<span class="spinner-border spinner-border-sm me-1"></span> ');

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    confirmButtonColor: '#0ab39c'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                btnSubmit.prop('disabled', false).find('.spinner-border').remove();
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        form.find(`[name="${key}"]`).addClass('is-invalid');
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro', text: 'Falha na operação.' });
                }
            }
        });
    }
});
</script>
@endpush
