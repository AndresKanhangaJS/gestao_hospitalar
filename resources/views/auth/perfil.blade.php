@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="row">
    {{-- Coluna da Esquerda: Resumo --}}
    <div class="col-xxl-3 col-lg-4">
        <div class="card text-center shadow-sm">
            <div class="card-body p-4">
                <div class="avatar-xl mx-auto mb-3">
                    <div class="avatar-title bg-soft-primary fw-bold rounded-circle fs-36 shadow">
                        {{ auth()->user()->initials() }}
                    </div>
                </div>
                <h5 class="mb-1 fw-bold">{{ auth()->user()->name }}</h5>
                <p class="text-muted text-uppercase fs-12 mb-3">
                    {{ auth()->user()->roles->first()->name ?? 'Usuário' }}
                </p>
                <div class="border-top pt-3 text-start">
                    <p class="text-muted fs-12 mb-1">E-mail</p>
                    <h6 class="fs-14 mb-3">{{ auth()->user()->email }}</h6>
                    <p class="text-muted fs-12 mb-1">Última Troca de Senha</p>
                    <h6 class="fs-14 mb-0">
                        {{ auth()->user()->password_changed_at ? auth()->user()->password_changed_at->format('d/m/Y H:i') : 'Nunca' }}
                    </h6>
                </div>
            </div>
        </div>
    </div>

    {{-- Coluna da Direita: Formulários --}}
    <div class="col-xxl-9 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header border-bottom-0">
                <h5 class="card-title mb-0">Segurança da Conta</h5>
            </div>
            <div class="card-body">
                <form id="form-update-password" action="{{ route('perfil.password.update') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Senha Atual <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" placeholder="Digite a senha atual">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nova Senha <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 5 caracteres">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar Nova Senha <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a nova senha">
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary btn-label shadow-sm">
                                <i class="ri-lock-password-line label-icon align-middle fs-16 me-2"></i>
                                Alterar Senha
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#form-update-password').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const btnSubmit = form.find('button[type="submit"]');
        const formData = new FormData(this);

        // Limpar estados de erro anteriores
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Feedback visual
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
                    title: 'Sucesso!',
                    text: response.message,
                    confirmButtonColor: '#3577f1',
                }).then(() => {
                    // Limpar o formulário após sucesso
                    form[0].reset();
                    // Opcional: recarregar a página para atualizar a "Data da última troca" no card lateral
                    window.location.reload();
                });

                btnSubmit.prop('disabled', false).html('<i class="ri-lock-password-line label-icon align-middle fs-16 me-2"></i> Alterar Senha');
            },
            error: function(xhr) {
                btnSubmit.prop('disabled', false).html('<i class="ri-lock-password-line label-icon align-middle fs-16 me-2"></i> Alterar Senha');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const input = $(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: xhr.responseJSON.message || 'Ocorreu um erro inesperado ao atualizar a senha.'
                    });
                }
            }
        });
    });
});
</script>
@endpush
