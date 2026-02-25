@extends('layouts.app')
@section('title', 'Tipos de Atendimento')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-heart-pulse-line me-1"></i> Tipos de Atendimento / Especialidades
            </h4>
            <button type="button" class="btn btn-success btn-label" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Novo Tipo
            </button>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">

            <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
                <form action="{{ route('tipos_atendimentos.index') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-12">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control search bg-white border-light"
                                    placeholder="Buscar por nome ou código/sigla...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <select class="form-select bg-white border-light" name="especialidade">
                                <option value="">Categoria (Todas)</option>
                                <option value="1" {{ request('especialidade') == '1' ? 'selected' : '' }}>🩺 Especialidade Médica</option>
                                <option value="0" {{ request('especialidade') == '0' ? 'selected' : '' }}>🏥 Atendimento Geral</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-6">
                            <select class="form-select bg-white border-light" name="status">
                                <option value="">Status (Todos)</option>
                                <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-6 d-flex gap-2 justify-content-end align-items-end">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm flex-grow-1">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i>Filtrar
                            </button>
                            <a href="{{ route('tipos_atendimentos.index') }}" class="btn btn-soft-danger px-4 shadow-sm">
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
                                <th scope="col" class="ps-4">Código</th>
                                <th scope="col">Especialidade / Tipo</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dados as $item)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-primary border border-primary-subtle">{{ $item->codigo }}</span>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="fs-14 mb-1 text-dark fw-bold">{{ $item->nome }}</h6>
                                            @if($item->especialidade)
                                                <span class="text-info small fw-medium">
                                                    <i class="ri-medal-fill align-bottom"></i> Especialidade Médica
                                                </span>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="ri- hospital-line align-bottom"></i> Atendimento Geral
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($item->status == 'activo')
                                        <span class="badge bg-success-subtle text-success text-uppercase fw-bold">
                                            <i class="ri-checkbox-circle-line align-middle me-1"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger text-uppercase fw-bold">
                                            <i class="ri-close-circle-line align-middle me-1"></i> Inactivo
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="hstack gap-2 justify-content-center">
                                        <button class="btn btn-sm btn-soft-info btn-edit"
                                            data-id="{{ $item->id }}"
                                            data-nome="{{ $item->nome }}"
                                            data-codigo="{{ $item->codigo }}"
                                            data-status="{{ $item->status }}"
                                            data-especialidade="{{ $item->especialidade }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit"
                                            title="Editar Registro">
                                            <i class="ri-pencil-fill fs-16"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-light text-primary rounded-circle fs-24">
                                            <i class="ri-search-2-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-muted">Nenhum registo encontrado.</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 p-3">
                    <p class="text-muted mb-0">Mostrando {{ $dados->count() }} de {{ $dados->total() }}</p>
                    <div>{{ $dados->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL REGISTAR TIPO DE ATENDIMENTO --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white fw-bold" id="modalCreateLabel">
                    <i class="ri-add-line me-1"></i> Registar Tipo de Atendimento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-tipo-atendimento-create" action="{{ route('tipos_atendimentos.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-muted small">NOME DO ATENDIMENTO <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control border-light bg-light" placeholder="Ex: Consulta Geral" required>
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">CÓDIGO/SIGLA <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" class="form-control border-light bg-light" placeholder="Ex: CONS-GER" required>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label fw-semibold text-muted small">É UMA ESPECIALIDADE?</label>
                            <div class="form-check form-switch form-switch-md mt-1">
                                <input class="form-check-input" type="checkbox" name="especialidade" value="1" id="checkEspecialidade">
                                <label class="form-check-label text-muted" for="checkEspecialidade">Sim (Aparecerá no cadastro de Médicos)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-link link-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-label shadow-sm">
                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Atendimento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR TIPO DE ATENDIMENTO --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info p-3">
                <h5 class="modal-title text-white fw-bold">
                    <i class="ri-edit-2-line me-1"></i> Editar Tipo de Atendimento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label class="form-label fw-semibold text-muted small">NOME DO ATENDIMENTO</label>
                            <input type="text" id="edit_nome" name="nome" class="form-control border-light bg-light" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">CÓDIGO/SIGLA</label>
                            <input type="text" id="edit_codigo" name="codigo" class="form-control border-light bg-light" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">STATUS</label>
                            <select name="status" id="edit_status" class="form-select border-light bg-light fw-bold">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" name="especialidade" value="1" id="edit_especialidade">
                                <label class="form-check-label fw-semibold text-muted" for="edit_especialidade">Marcar como Especialidade Médica</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info btn-label shadow-sm">
                        <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Guardar Alterações
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
    // ---------------------------------------------------------
    // 1. LÓGICA DE REGISTO (STORE)
    // ---------------------------------------------------------
    $('#form-tipo-atendimento-create').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processando...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(), // Como não há arquivos, serialize é mais simples que FormData
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Excelente!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Atendimento');
                if (xhr.status === 422) {
                    let erros = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Erro de Validação', html: erros });
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro!', text: 'Erro ao tentar registar.' });
                }
            }
        });
    });

    // ---------------------------------------------------------
    // 2. LÓGICA DE ABERTURA DO MODAL DE EDIÇÃO
    // ---------------------------------------------------------
    $(document).on('click', '.btn-edit', function() {
    const btn = $(this);
    const id = btn.data('id');

    // Define a URL
    $('#form-edit').attr('action', `/tipos-atendimentos/${id}/atualizar`);

        // Preenche os inputs de texto e select
        $('#edit_nome').val(btn.data('nome'));
        $('#edit_codigo').val(btn.data('codigo'));
        $('#edit_status').val(btn.data('status'));

        // Correção da Especialidade:
        // Captura o valor (pode vir como 1, "1", true ou "true")
        const isEspecialidade = btn.data('especialidade');

        if (isEspecialidade == 1 || isEspecialidade == true) {
            $('#edit_especialidade').prop('checked', true);
        } else {
            $('#edit_especialidade').prop('checked', false);
        }
    });

    // ---------------------------------------------------------
    // 3. SUBMIT DA EDIÇÃO (UPDATE)
    // ---------------------------------------------------------
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> A Guardar...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST', // Use POST e adicione o _method PUT se preferir RESTful
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Atualizado!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Guardar Alterações');
                if (xhr.status === 422) {
                    let erros = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Erro de Validação', html: erros });
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro!', text: 'Erro ao tentar atualizar.' });
                }
            }
        });
    });
});
</script>
@endpush
