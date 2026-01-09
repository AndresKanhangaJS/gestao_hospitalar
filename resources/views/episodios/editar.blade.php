@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 mb-4 bg-white rounded">
            <h4 class="mb-sm-0 text-primary fw-bold"><i class="ri-user-settings-line me-2"></i>Pacientes</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('pacientes.index') }}">Pacientes</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                    <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">Editar Paciente: {{ $paciente->nome_completo }}</h5>
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

            <form id="form-paciente-edit" action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-user-3-line me-2 fs-20"></i> Dados Pessoais
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <label for="nome_completo" class="form-label fw-semibold text-muted small">NOME COMPLETO <span class="text-danger">*</span></label>
                                <input type="text" id="nome_completo" name="nome_completo" class="form-control border-light bg-light" value="{{ $paciente->nome_completo }}" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="data_nascimento" class="form-label fw-semibold text-muted small">DATA DE NASCIMENTO <span class="text-danger">*</span></label>
                                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control border-light bg-light" value="{{ $paciente->data_nascimento->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label d-block fw-semibold text-muted small">GÊNERO <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center mt-2 ps-1">
                                    <div class="form-check form-check-primary me-4">
                                        <input class="form-check-input" type="radio" name="genero" id="editM" value="Masculino" {{ $paciente->genero == 'Masculino' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editM">Masculino</label>
                                    </div>
                                    <div class="form-check form-check-primary">
                                        <input class="form-check-input" type="radio" name="genero" id="editF" value="Feminino" {{ $paciente->genero == 'Feminino' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editF">Feminino</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="telefone" class="form-label fw-semibold text-muted small">TELEFONE</label>
                                <input type="tel" id="telefone" name="telefone" class="form-control border-light bg-light" value="{{ $paciente->telefone }}">
                            </div>
                            <div class="col-lg-4">
                                <label for="email" class="form-label fw-semibold text-muted small">E-MAIL</label>
                                <input type="email" id="email" name="email" class="form-control border-light bg-light" value="{{ $paciente->email }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-file-text-line me-2 fs-20"></i> Documentação e Localização
                        </h5>
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="estado_paciente" class="form-label fw-semibold text-muted small">ESTADO DO REGISTO</label>
                                <select class="form-select border-light bg-primary-subtle fw-bold" name="status" id="estado_paciente" required>
                                    <option value="activo" {{ $paciente->status == 'activo' ? 'selected' : '' }}>🟢 Activo</option>
                                    <option value="inactivo" {{ $paciente->status == 'inactivo' ? 'selected' : '' }}>🔴 Inactivo</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="tipo_documento" class="form-label fw-semibold text-muted small">TIPO DE DOCUMENTO</label>
                                <select class="form-select border-light bg-light" name="tipo_documento" id="tipo_documento" required>
                                    @foreach(['BI', 'Cedula', 'Assento', 'Passaporte', 'Cartao_Residente'] as $doc)
                                        <option value="{{ $doc }}" {{ $paciente->tipo_documento == $doc ? 'selected' : '' }}>{{ $doc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="numero_documento" class="form-label fw-semibold text-muted small">NÚMERO DO DOCUMENTO</label>
                                <input type="text" id="numero_documento" name="numero_documento" class="form-control border-light bg-light" value="{{ $paciente->numero_documento }}" required>
                            </div>
                            <div class="col-lg-12">
                                <label for="morada" class="form-label fw-semibold text-muted small">MORADA COMPLETA</label>
                                <textarea id="morada" name="morada" class="form-control border-light bg-light" rows="2">{{ $paciente->morada }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <h5 class="card-title text-danger border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-heart-pulse-line me-2 fs-20"></i> Informações Médicas
                        </h5>
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="grupo_sanguineo" class="form-label fw-semibold text-muted small">GRUPO SANGUÍNEO</label>
                                <select class="form-select border-danger-subtle bg-light" name="grupo_sanguineo" id="grupo_sanguineo">
                                    <option value="" disabled>Tipo...</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs)
                                        <option value="{{ $gs }}" {{ $paciente->grupo_sanguineo == $gs ? 'selected' : '' }}>{{ $gs }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-8">
                                <label for="alergias" class="form-label fw-semibold text-muted small">ALERGIAS / OBSERVAÇÕES</label>
                                <input type="text" id="alergias" name="alergias" class="form-control border-danger-subtle bg-light" value="{{ $paciente->alergias }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light-subtle hstack gap-2 justify-content-end p-4 border-top">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                        <i class="ri-save-3-line align-bottom me-1"></i> Guardar Alterações
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
    const form = $('#form-paciente-edit');

    // Captura o estado inicial
    let originalData = form.serialize();

    form.on('submit', function(e) {
        e.preventDefault();

        const btnSubmit = form.find('button[type="submit"]');
        const currentData = form.serialize();

        if (originalData === currentData) {
            Swal.fire({
                icon: 'info',
                title: 'Sem alterações',
                text: 'Não foram detetadas mudanças para atualizar.',
                confirmButtonColor: '#3577f1'
            });
            return false;
        }

        const formData = new FormData(this);

        // Limpar erros
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback, .text-danger.small').remove();

        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> A guardar...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'info') {
                    Swal.fire({ icon: 'info', title: 'Aviso', text: response.message, confirmButtonColor: '#3577f1' });
                    btnSubmit.prop('disabled', false).html('<i class="ri-save-3-line me-1"></i> Guardar Alterações');
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado!',
                    text: response.message,
                    showCancelButton: true,
                    confirmButtonText: '<i class="ri-eye-line me-1"></i> Ver Detalhes',
                    cancelButtonText: '<i class="ri-list-unordered me-1"></i> Voltar à Lista',
                    confirmButtonColor: '#3577f1',
                    cancelButtonColor: '#0ab39c',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // CORREÇÃO AQUI: Montamos a URL dinamicamente sem que o Blade dê erro
                        let showUrl = "{{ route('pacientes.show', ':id') }}";
                        window.location.href = showUrl.replace(':id', response.id);
                    } else {
                        window.location.href = "{{ route('pacientes.index') }}";
                    }
                });
            },
            error: function(xhr) {
                btnSubmit.prop('disabled', false).html('<i class="ri-save-3-line me-1"></i> Guardar Alterações');
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const input = $(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro!', text: 'Erro interno no servidor.', confirmButtonColor: '#f06548' });
                }
            }
        });
    });
});
</script>
@endpush
