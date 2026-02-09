@extends('layouts.app')
@section('title', 'Gestão de Convénios')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-shield-cross-line me-1"></i> Gestão de Convénios
            </h4>
            <button type="button" class="btn btn-success btn-label" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Novo Convénio
            </button>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-body border-bottom bg-light-subtle">
                <form action="{{ route('seguradoras.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-white" placeholder="Buscar por nome, código ou NIF...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-8 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                            </button>
                            <a href="{{ route('seguradoras.index') }}" class="btn btn-soft-danger w-50 shadow-sm" title="Limpar Filtros">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Cód.</th>
                                <th>Instituição</th>
                                <th>Tipo</th>
                                <th>NIF</th>
                                <th>Contacto</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($seguradoras as $item)
                            <tr>
                                <td class="fw-medium text-primary">{{ $item->codigo_seguradora }}</td>
                                <td><span class="fw-bold">{{ $item->nome }}</span></td>
                                <td>
                                    @if($item->tipo == 'seguradora')
                                        <span class="badge bg-primary-subtle text-primary text-uppercase">Seguradora</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning text-uppercase">Empresa</span>
                                    @endif
                                </td>
                                <td>{{ $item->nif ?? '---' }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span><i class="ri-phone-line me-1"></i>{{ $item->telefone ?? 'N/A' }}</span>
                                        <small class="text-muted">{{ $item->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $item->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-uppercase">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-soft-info btn-edit"
                                        data-id="{{ $item->id }}"
                                        data-nome="{{ $item->nome }}"
                                        data-tipo="{{ $item->tipo }}"
                                        data-codigo="{{ $item->codigo_seguradora }}"
                                        data-nif="{{ $item->nif }}"
                                        data-telefone="{{ $item->telefone }}"
                                        data-email="{{ $item->email }}"
                                        data-status="{{ $item->status }}"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4">Nenhum registo encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $seguradoras->links() }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL REGISTAR --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Novo Convénio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-convenio-create" action="{{ route('seguradoras.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">

                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-shield-cross-line me-2 fs-20"></i> Identificação da Instituição
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <label for="nome" class="form-label fw-semibold text-muted small">NOME DA INSTITUIÇÃO <span class="text-danger">*</span></label>
                                <input type="text" id="nome" name="nome" class="form-control border-light bg-light" placeholder="Ex: Nossa Seguros, SA" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="tipo" class="form-label fw-semibold text-muted small">TIPO DE CONVÉNIO <span class="text-danger">*</span></label>
                                <select name="tipo" id="tipo" class="form-select border-light bg-light" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <option value="seguradora">Seguradora</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="codigo_seguradora" class="form-label fw-semibold text-muted small">CÓDIGO <span class="text-danger">*</span></label>
                                <input type="text" id="codigo_seguradora" name="codigo_seguradora" class="form-control border-light bg-light" placeholder="Ex: NOS-01" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="nif" class="form-label fw-semibold text-muted small">NIF (IDENTIFICAÇÃO FISCAL)</label>
                                <input type="text" id="nif" name="nif" class="form-control border-light bg-light" placeholder="000000000">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-mail-send-line me-2 fs-20"></i> Informações de Contacto
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label for="telefone" class="form-label fw-semibold text-muted small">TELEFONE DE CONTACTO</label>
                                <input type="tel" id="telefone" name="telefone" class="form-control border-light bg-light" placeholder="(+244) ...">
                            </div>
                            <div class="col-lg-6">
                                <label for="email" class="form-label fw-semibold text-muted small">E-MAIL INSTITUCIONAL</label>
                                <input type="email" id="email" name="email" class="form-control border-light bg-light" placeholder="geral@instituicao.com">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-percent-line me-2 fs-20"></i> Cláusulas de Desconto e Co-pagamento
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tabela-regras">
                                <thead class="table-light small text-uppercase">
                                    <tr>
                                        <th>Categoria</th>
                                        <th>Abrangência</th>
                                        <th>Tipo</th>
                                        <th style="width: 150px;">Resp. Empresa</th>
                                        <th style="width: 150px;">Resp. Paciente</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="regra-row">
                                        <td>
                                            <select name="regras[0][categoria]" class="form-select form-select-sm" required>
                                                <option value="Consulta">Consulta</option>
                                                <option value="Medicamento">Medicamento</option>
                                                <option value="Laboratorio">Laboratório</option>
                                                <option value="Internamento">Internamento</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="regras[0][aplicavel_a]" class="form-select form-select-sm">
                                                <option value="todos">Todos</option>
                                                <option value="titular">Apenas Titular</option>
                                                <option value="dependente">Apenas Dependentes</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="regras[0][tipo_valor]" class="form-select form-select-sm">
                                                <option value="percentagem">Percentagem (%)</option>
                                                <option value="fixo">Valor Fixo (AKZ)</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="regras[0][empresa]" class="form-control form-control-sm" placeholder="Ex: 50" required></td>
                                        <td><input type="number" name="regras[0][paciente]" class="form-control form-control-sm" placeholder="Ex: 50" required></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-soft-danger remove-row"><i class="ri-delete-bin-line"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-soft-primary" id="add-regra">
                                <i class="ri-add-line align-middle"></i> Adicionar Cláusula
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light-subtle hstack gap-2 justify-content-end p-4 border-top">
                    <button type="reset" class="btn btn-ghost-secondary px-4">Limpar</button>
                    <button type="submit" class="btn btn-success px-5 shadow-sm">
                        <i class="ri-save-line align-bottom me-1"></i> Registar Convénio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Editar Convénio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">

                    <div class="mb-5">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-shield-cross-line me-2 fs-20"></i> Identificação da Instituição
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <label for="edit_nome" class="form-label fw-semibold text-muted small">NOME DA INSTITUIÇÃO <span class="text-danger">*</span></label>
                                <input type="text" id="edit_nome" name="nome" class="form-control border-light bg-light" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="edit_tipo" class="form-label fw-semibold text-muted small">TIPO DE CONVÉNIO <span class="text-danger">*</span></label>
                                <select name="tipo" id="edit_tipo" class="form-select border-light bg-light" required>
                                    <option value="seguradora">Seguradora</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="edit_codigo" class="form-label fw-semibold text-muted small">CÓDIGO <span class="text-danger">*</span></label>
                                <input type="text" id="edit_codigo" name="codigo_seguradora" class="form-control border-light bg-light" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="edit_nif" class="form-label fw-semibold text-muted small">NIF</label>
                                <input type="text" id="edit_nif" name="nif" class="form-control border-light bg-light">
                            </div>
                            <div class="col-lg-4">
                                <label for="edit_status" class="form-label fw-semibold text-muted small">ESTADO DO REGISTO</label>
                                <select name="status" id="edit_status" class="form-select border-light bg-primary-subtle fw-bold">
                                    <option value="activo">🟢 Activo</option>
                                    <option value="inactivo">🔴 Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center">
                            <i class="ri-mail-send-line me-2 fs-20"></i> Informações de Contacto
                        </h5>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label for="edit_telefone" class="form-label fw-semibold text-muted small">TELEFONE DE CONTACTO</label>
                                <input type="text" id="edit_telefone" name="telefone" class="form-control border-light bg-light">
                            </div>
                            <div class="col-lg-6">
                                <label for="edit_email" class="form-label fw-semibold text-muted small">E-MAIL INSTITUCIONAL</label>
                                <input type="email" id="edit_email" name="email" class="form-control border-light bg-light">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light-subtle hstack gap-2 justify-content-end p-4 border-top w-100">
                    <button type="button" class="btn btn-ghost-secondary px-4" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-info px-5 shadow-sm">
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

    // 1. CARREGAR DADOS NO MODAL EDITAR (USANDO DELEGAÇÃO)
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();

        // Captura os dados diretamente do elemento clicado
        const button = $(this);
        const id = button.attr('data-id');
        const nome = button.attr('data-nome');
        const tipo = button.attr('data-tipo');
        const codigo = button.attr('data-codigo');
        const nif = button.attr('data-nif');
        const telefone = button.attr('data-telefone');
        const email = button.attr('data-email');
        const status = button.attr('data-status');

        console.log("Dados capturados:", {id, nome, tipo, status});

        // Preenche os campos do modal
        $('#edit_nome').val(nome);
        $('#edit_tipo').val(tipo).change(); // .change() ajuda triggers de plugins se houver
        $('#edit_codigo').val(codigo);
        $('#edit_nif').val(nif);
        $('#edit_telefone').val(telefone);
        $('#edit_email').val(email);
        $('#edit_status').val(status).change();

        // Ajusta a URL do Form
        $('#form-edit').attr('action', '/seguradoras/' + id);

        // Abre o modal manualmente para garantir
        $('#modalEdit').modal('show');
    });

    // 2. AJAX CREATE
    $('#form-convenio-create').on('submit', function(e) {
        e.preventDefault();
        submitAjax($(this), 'Registar Convénio');
    });

    // 3. AJAX UPDATE
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        submitAjax($(this), 'Guardar Alterações');
    });

    function submitAjax(form, originalText) {
        const btn = form.find('button[type="submit"]');
        const originalContent = btn.html();

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: form.attr('action'),
            method: 'POST', // Laravel precisa de POST + @method('PUT')
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if(res.status === 'info') {
                    Swal.fire({ icon: 'info', title: 'Aviso', text: res.message });
                    btn.prop('disabled', false).html(originalContent);
                } else {
                    Swal.fire({ icon: 'success', title: 'Sucesso', text: res.message || 'Operação realizada!' })
                        .then(() => location.reload());
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalContent);
                let msg = "Erro ao processar a requisição.";
                if(xhr.status === 422) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                Swal.fire({ icon: 'error', title: 'Erro', text: msg });
            }
        });
    }
});
$(document).ready(function() {
    let regraIndex = 1;

    // Adicionar nova linha de regra
    $('#add-regra').on('click', function() {
        const newRow = `
        <tr class="regra-row">
            <td>
                <select name="regras[${regraIndex}][categoria]" class="form-select form-select-sm" required>
                    <option value="Consulta">Consulta</option>
                    <option value="Medicamento">Medicamento</option>
                    <option value="Laboratorio">Laboratório</option>
                    <option value="Internamento">Internamento</option>
                </select>
            </td>
            <td>
                <select name="regras[${regraIndex}][aplicavel_a]" class="form-select form-select-sm">
                    <option value="todos">Todos</option>
                    <option value="titular">Apenas Titular</option>
                    <option value="dependente">Apenas Dependentes</option>
                </select>
            </td>
            <td>
                <select name="regras[${regraIndex}][tipo_valor]" class="form-select form-select-sm">
                    <option value="percentagem">Percentagem (%)</option>
                    <option value="fixo">Valor Fixo (AKZ)</option>
                </select>
            </td>
            <td><input type="number" name="regras[${regraIndex}][empresa]" class="form-control form-control-sm" required></td>
            <td><input type="number" name="regras[${regraIndex}][paciente]" class="form-control form-control-sm" required></td>
            <td>
                <button type="button" class="btn btn-sm btn-soft-danger remove-row">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>`;

        $('#tabela-regras tbody').append(newRow);
        regraIndex++;
    });

    // Remover linha (com proteção extra via JS além do CSS)
    $(document).on('click', '.remove-row', function() {
        // Verifica se não é a primeira linha antes de remover
        if ($(this).closest('tr').is(':first-child')) {
            return false;
        }
        $(this).closest('tr').remove();
    });
});
</script>
@endpush
@push('styles')
<style>
    /* Esconde o botão remover apenas na primeira linha da tabela de regras */
    #tabela-regras tbody tr:first-child .remove-row {
        display: none !important;
    }
</style>
@endpush
