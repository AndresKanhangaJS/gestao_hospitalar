@extends('layouts.app')

@section('title', 'Abrir Episódio')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 mb-4 bg-white rounded">
            <h4 class="mb-sm-0 text-primary fw-bold"><i class="ri-hospital-line me-2"></i>Atendimentos</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('episodios.index') }}">Episódios</a></li>
                    <li class="breadcrumb-item active">Abrir Atendimento</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-lg border-0">
            <div class="card-header border-0 bg-primary-subtle py-3">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">Abrir Episódio: {{ $paciente->nome_completo }}</h5>
                    <div class="flex-shrink-0 hstack gap-2">
                        <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-info btn-sm shadow-sm">
                            <i class="ri-eye-line me-1 align-bottom"></i> Ver Detalhes
                        </a>
                        <a href="{{ route('pacientes.index') }}" class="btn btn-white btn-sm shadow-sm border-light text-primary">
                            <i class="ri-list-unordered me-1 align-bottom"></i> Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>

            <form id="form-episodio" action="{{ route('episodios.store') }}" method="POST">
                @csrf
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                <div class="card-body p-4">
                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-user-search-line me-2 fs-20"></i> Identificação do Paciente
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <label class="form-label fw-semibold text-muted small">NOME COMPLETO</label>
                                <input type="text" class="form-control border-light bg-light-subtle fw-bold" value="{{ $paciente->nome_completo }}" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label fw-semibold text-muted small">NÚMERO DO DOCUMENTO ({{ $paciente->tipo_documento }})</label>
                                <input type="text" class="form-control border-light bg-light-subtle fw-bold" value="{{ $paciente->numero_documento }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-pulse-line me-2 fs-20"></i> Triagem e Sinais Vitais
                        </h5>
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold text-muted small">PRESSÃO ARTERIAL (PA)</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light"><i class="ri-heart-3-line text-danger"></i></span>
                                    <input type="text" name="pa_sistolica" class="form-control border-light bg-light" placeholder="120" style="width: 40px;">
                                    <span class="input-group-text border-light bg-light">/</span>
                                    <input type="text" name="pa_diastolica" class="form-control border-light bg-light" placeholder="80">
                                    <span class="input-group-text border-light bg-light">mmHg</span>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label class="form-label fw-semibold text-muted small">TEMPERATURA</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light"><i class="ri-temp-hot-line text-warning"></i></span>
                                    <input type="number" step="0.1" name="temperatura" class="form-control border-light bg-light" placeholder="36.5">
                                    <span class="input-group-text border-light bg-light">°C</span>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label class="form-label fw-semibold text-muted small">PESO</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="peso" class="form-control border-light bg-light" placeholder="70.0">
                                    <span class="input-group-text border-light bg-light">kg</span>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label class="form-label fw-semibold text-muted small">ALTURA</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="altura" class="form-control border-light bg-light" placeholder="1.75">
                                    <span class="input-group-text border-light bg-light">m</span>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12">
                                <label class="form-label fw-semibold text-muted small">FREQ. CARDÍACA / SATURAÇÃO</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light"><i class="ri-rest-time-line text-info"></i></span>
                                    <input type="number" name="frequencia_cardiaca" class="form-control border-light bg-light" placeholder="BPM">
                                    <input type="number" name="saturacao" class="form-control border-light bg-light" placeholder="SpO2 %">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-stethoscope-line me-2 fs-20"></i> Dados da Consulta / Atendimento
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <label class="form-label fw-semibold text-muted small">DATA/HORA DE ABERTURA</label>
                                <div class="input-group">
                                    <span class="input-group-text border-light bg-light"><i class="ri-calendar-event-line text-primary"></i></span>
                                    <input type="text" class="form-control border-light bg-light-subtle fw-bold"
                                        value="{{ now()->format('d/m/Y H:i') }}" readonly disabled>
                                    <input type="hidden" name="data_abertura" value="{{ now() }}">
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <label for="medico_id" class="form-label fw-semibold text-muted small">MÉDICO RESPONSÁVEL <span class="text-danger">*</span></label>
                                <select class="form-select border-light bg-light" name="medico_id" id="medico_id" required>
                                    <option value="" selected disabled>Selecione o médico...</option>
                                    @foreach($medicos as $medico)
                                        <option value="{{ $medico->id }}">{{ $medico->genero == 'Masculino' ? 'Dr.' : 'Dra.' }} {{ $medico->nome_completo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label for="tipo_atendimento_id" class="form-label fw-semibold text-muted small">TIPO DE ATENDIMENTO <span class="text-danger">*</span></label>
                                <select class="form-select border-light bg-light" name="tipo_atendimento_id" id="tipo_atendimento_id" required>
                                    <option value="" selected disabled>Selecione o tipo...</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light-subtle hstack gap-2 justify-content-end p-4 border-top">
                    <button type="reset" class="btn btn-ghost-secondary px-4">Limpar</button>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                        <i class="ri-check-double-line align-bottom me-1"></i> Iniciar Atendimento
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
    $('#form-episodio').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        const formData = new FormData(this);

        // Limpar estados de erro anteriores (Padrão do Sistema)
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback, .text-danger.small').remove();

        // Feedback visual de carregamento
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> A processar...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Atendimento Iniciado!',
                    text: response.message,
                    showCancelButton: true,
                    confirmButtonText: '<i class="ri-eye-line me-1"></i> Ver Episódio',
                    cancelButtonText: '<i class="ri-list-unordered me-1"></i> Ver Todos',
                    confirmButtonColor: '#3577f1', // Azul (Informação/Ação)
                    cancelButtonColor: '#0ab39c',  // Verde (Listagem/Sucesso)
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redireciona para os detalhes do episódio criado
                        let url = "{{ route('episodios.show', ':id') }}";
                        window.location.href = url.replace(':id', response.id);
                    } else {
                        // Vai para a listagem geral de episódios
                        window.location.href = "{{ route('episodios.index') }}";
                    }
                });
            },
            error: function(xhr) {
                // Restaurar botão em caso de erro
                btnSubmit.prop('disabled', false).html('<i class="ri-check-double-line align-bottom me-1"></i> Iniciar Atendimento');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const input = $(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        // Inserir mensagem de erro logo após o campo ou o input-group
                        if (input.parent().hasClass('input-group')) {
                            input.parent().after(`<div class="text-danger small mt-1">${errors[key][0]}</div>`);
                        } else {
                            input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: xhr.responseJSON.message || 'Ocorreu um erro ao tentar abrir o episódio clínico.'
                    });
                }
            }
        });
    });
});
</script>
@endpush
