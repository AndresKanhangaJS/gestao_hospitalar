@extends('layouts.app')

@section('title', 'Registar Paciente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 mb-4 bg-white rounded">
            <h4 class="mb-sm-0 text-primary fw-bold"><i class="ri-user-add-line me-2"></i>Pacientes</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pacientes</a></li>
                    <li class="breadcrumb-item active">Registar</li>
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
                    <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">Registar Novo Paciente</h5>
                    <div class="flex-shrink-0">
                        @can('pacientes.listar')
                        <a href="{{ route('pacientes.index') }}" class="btn btn-white btn-sm shadow-sm border-light">
                            <i class="ri-list-unordered me-1 align-bottom text-primary"></i> Voltar à Lista
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <form id="form-paciente" class="tablelist-form" autocomplete="true" action="{{ route('pacientes.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-user-3-line me-2 fs-20"></i> Dados Pessoais
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <label for="nome_completo" class="form-label fw-semibold text-muted small">NOME COMPLETO <span class="text-danger">*</span></label>
                                <input type="text" id="nome_completo" name="nome_completo" class="form-control border-light bg-light" placeholder="Digite o nome completo" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="data_nascimento" class="form-label fw-semibold text-muted small">DATA DE NASCIMENTO <span class="text-danger">*</span></label>
                                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control border-light bg-light" max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label d-block fw-semibold text-muted small">GÊNERO <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center mt-2 ps-1">
                                    <div class="form-check form-check-primary me-4">
                                        <input class="form-check-input" type="radio" name="genero" id="generoM" value="Masculino" checked>
                                        <label class="form-check-label" for="generoM">Masculino</label>
                                    </div>
                                    <div class="form-check form-check-primary">
                                        <input class="form-check-input" type="radio" name="genero" id="generoF" value="Feminino">
                                        <label class="form-check-label" for="generoF">Feminino</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="telefone" class="form-label fw-semibold text-muted small">TELEFONE</label>
                                <input type="tel" id="telefone" name="telefone" class="form-control border-light bg-light" placeholder="(+244) ...">
                            </div>
                            <div class="col-lg-4">
                                <label for="email" class="form-label fw-semibold text-muted small">E-MAIL</label>
                                <input type="email" id="email" name="email" class="form-control border-light bg-light" placeholder="exemplo@email.com">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-file-text-line me-2 fs-20"></i> Documentação e Localização
                        </h5>
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="tipo_documento" class="form-label fw-semibold text-muted small">TIPO DE DOCUMENTO</label>
                                <select class="form-select border-light bg-light" name="tipo_documento" id="tipo_documento" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <option value="BI">BI</option>
                                    <option value="Cedula">Cédula</option>
                                    <option value="Assento">Assento</option>
                                    <option value="Passaporte">Passaporte</option>
                                    <option value="Cartao_Residente">Cartão Residente</option>
                                </select>
                            </div>
                            <div class="col-lg-8">
                                <label for="numero_documento" class="form-label fw-semibold text-muted small">NÚMERO DO DOCUMENTO</label>
                                <input type="text" id="numero_documento" name="numero_documento" class="form-control border-light bg-light" placeholder="Número do documento" required>
                            </div>
                            <div class="col-lg-12">
                                <label for="morada" class="form-label fw-semibold text-muted small">MORADA COMPLETA</label>
                                <textarea id="morada" name="morada" class="form-control border-light bg-light" rows="2" placeholder="Rua, Bairro, Cidade..."></textarea>
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
                                    <option value="" selected disabled>Tipo...</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs)
                                        <option value="{{ $gs }}">{{ $gs }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-8">
                                <label for="alergias" class="form-label fw-semibold text-muted small">ALERGIAS / OBSERVAÇÕES</label>
                                <input type="text" id="alergias" name="alergias" class="form-control border-danger-subtle bg-light" placeholder="Descreva alergias se houver...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light-subtle hstack gap-2 justify-content-end p-4 border-top">
                    <button type="reset" class="btn btn-ghost-secondary px-4">Limpar</button>
                    <button type="submit" class="btn btn-success px-5 shadow-sm">
                        <i class="ri-save-line align-bottom me-1"></i> Registar Paciente
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
    $('#form-paciente').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        const formData = new FormData(this);

        // Limpar estados de erro anteriores
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback, .text-danger.small').remove();

        // Feedback visual de carregamento
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> A guardar...');

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
                    title: 'Registo Efectuado!',
                    text: response.message,
                    showCancelButton: true,
                    confirmButtonText: '<i class="ri-add-line me-1"></i> Registar Outro',
                    cancelButtonText: '<i class="ri-list-unordered me-1"></i> Listar Pacientes',
                    confirmButtonColor: '#0ab39c', // Verde
                    cancelButtonColor: '#3577f1',  // Azul
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Limpa o formulário e reabilita o botão para novo registo
                        form[0].reset();
                        window.scrollTo(0, 0);
                        btnSubmit.prop('disabled', false).html('<i class="ri-save-line align-bottom me-1"></i> Registar Paciente');
                        // Remove manualmente classes de sucesso se o seu layout as usar
                        $('.form-control').removeClass('is-valid');
                    } else {
                        // Redirecciona para a listagem
                        window.location.href = "{{ route('pacientes.index') }}";
                    }
                });
            },
            error: function(xhr) {
                btnSubmit.prop('disabled', false).html('<i class="ri-save-line align-bottom me-1"></i> Registar Paciente');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const input = $(`[name="${key}"]`);
                        if (input.attr('type') === 'radio') {
                            input.closest('.d-flex').after(`<div class="text-danger small mt-1">${errors[key][0]}</div>`);
                        } else {
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao tentar gravar o registo.'
                    });
                }
            }
        });
    });
});
</script>
@endpush
