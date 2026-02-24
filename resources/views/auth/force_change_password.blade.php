@extends('layouts.app')

@section('title', 'Alteração de Senha Obrigatória')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-xxl-5 col-lg-7">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ri-shield-keyhole-line fs-3 text-white-50"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="card-title mb-1 text-white">Segurança Adicional</h4>
                        <p class="mb-0 opacity-75">Este é o seu primeiro acesso. Por favor, defina uma senha segura para continuar.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-info alert-dismissible fade show border-0 mb-4" role="alert">
                    <i class="ri-information-line me-2 align-middle fs-16"></i>
                    Sua nova senha deve ter no mínimo <strong>5 caracteres</strong>.
                </div>

                <form id="form-force-update" action="{{ route('password.force_update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Senha Atual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light"><i class="ri-lock-line"></i></span>
                            <input type="password" name="current_password" class="form-control border-light bg-light" placeholder="Digite a senha atual">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nova Senha <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Crie uma senha">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmar Senha <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a senha">
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn btn-primary w-100 btn-label shadow-sm">
                            <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                            Definir Senha e Acessar Sistema
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light-subtle text-center py-3">
                <p class="mb-0 text-muted small">Logado como: <strong> {{ auth()->user()->name }} | {{ auth()->user()->email }}</strong></p>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#form-force-update').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        const formData = new FormData(this);

        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback, .text-danger.small').remove();

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
                    title: 'Senha Definida!',
                    text: 'Seu acesso foi liberado com sucesso.',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    // Redireciona para o Dashboard agora que must_change_password é false
                    window.location.href = "{{ route('dashboard') }}";
                });
            },
            error: function(xhr) {
                btnSubmit.prop('disabled', false).html('<i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Definir Senha e Acessar Sistema');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const input = $(`[name="${key}"]`);
                        input.addClass('is-invalid');

                        // Tratamento para campos dentro de input-group
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
                        text: xhr.responseJSON.message || 'Ocorreu um erro ao tentar atualizar sua senha.'
                    });
                }
            }
        });
    });
});
</script>
@endpush
