@extends('layouts.app')
@section('title', 'Gestão de Exames')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-flask-line me-1"></i> Catálogo de Exames
            </h4>
            <button type="button" class="btn btn-success btn-label" data-bs-toggle="modal" data-bs-target="#modalExameCreate">
                <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Novo Exame
            </button>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-xxl-3 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-soft-primary border-0 align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-primary">CATEGORIAS</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                    <i class="ri-add-line"></i>
                </button>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($categorias as $cat)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">{{ $cat->nome }}</h6>
                                <span class="text-muted small">{{ $cat->exames_count ?? 0 }} exames</span>
                            </div>
                            <button class="btn btn-ghost-info btn-sm btn-edit-cat" data-id="{{ $cat->id }}" data-nome="{{ $cat->nome }}">
                                <i class="ri-edit-2-line"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-9 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
                <form action="{{ route('exames.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-12">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control search bg-white border-light" placeholder="Buscar exame ou código...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <select class="form-select bg-white border-light" name="categoria_id">
                                <option value="">Todas as Categorias</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-4 col-sm-6 d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="ri-equalizer-fill me-1"></i>Filtrar</button>
                            <a href="{{ route('exames.index') }}" class="btn btn-soft-danger px-4 shadow-sm"><i class="ri-refresh-line"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Código</th>
                                <th>Exame / Categoria</th>
                                <th>Jejum</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dados as $item)
                            <tr>
                                <td class="ps-4"><span class="badge bg-light text-primary border border-primary-subtle">{{ $item->codigo }}</span></td>
                                <td>
                                    <h6 class="fs-14 mb-1 fw-bold text-dark">{{ $item->nome }}</h6>
                                    <span class="text-muted small"><i class="ri-stack-line"></i> {{ $item->categoria->nome }}</span>
                                </td>
                                <td>
                                    @if($item->requer_jejum)
                                        <span class="badge badge-soft-warning">Sim</span>
                                    @else
                                        <span class="badge badge-soft-info">Não</span>
                                    @endif
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
                                        data-codigo="{{ $item->codigo }}"
                                        data-categoria="{{ $item->exame_categoria_id }}"
                                        data-jejum="{{ $item->requer_jejum }}"
                                        data-status="{{ $item->status }}"
                                        data-descricao="{{ $item->descricao }}"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-5">Nenhum exame encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $dados->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL REGISTAR/EDITAR CATEGORIA --}}
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white fw-bold" id="modalCategoriaLabel">
                    <i class="ri-add-line me-1"></i> Nova Categoria
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-categoria-save" action="{{ route('exame_categorias.store') }}" method="POST">
                @csrf
                <input type="hidden" name="categoria_id" id="cat_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label class="form-label fw-semibold text-muted small">NOME DA CATEGORIA <span class="text-danger">*</span></label>
                            <input type="text" name="nome" id="cat_nome" class="form-control border-light bg-light" placeholder="Ex: Hematologia" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-link link-danger text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="ri-save-line align-middle me-1"></i> Registar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL REGISTAR EXAME E PARÂMETROS --}}
<div class="modal fade" id="modalExameCreate" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary p-3 text-white">
                <h5 class="modal-title fw-bold text-white">Registar Exame e Parâmetros</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-exame-create" action="{{ route('exames.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <label class="form-label fw-bold small">CATEGORIA</label>
                            <select name="exame_categoria_id" class="form-select bg-light border-light" required>
                                <option value="">Selecione...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-label fw-bold small">NOME DO EXAME</label>
                            <input type="text" name="nome" class="form-control bg-light border-light" required>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-bold small">CÓDIGO</label>
                            <input type="text" name="codigo" class="form-control bg-light border-light" required>
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label fw-bold small">DESCRIÇÃO / OBSERVAÇÕES</label>
                            <textarea name="descricao" id="create_descricao" class="form-control bg-light border-light" rows="2"></textarea>
                        </div>

                        <div class="col-12"><hr class="text-muted"></div>

                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold text-primary mb-0">PARÂMETROS DO EXAME (ITENS)</h6>
                                <button type="button" class="btn btn-sm btn-soft-primary" id="add-item-row">
                                    <i class="ri-add-circle-line me-1"></i> Adicionar Item
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle" id="table-items">
                                    <thead class="bg-light">
                                        <tr class="small text-muted text-uppercase">
                                            <th>Nome do Item (Ex: Glicose)</th>
                                            <th>Unidade (Ex: mg/dL)</th>
                                            <th>Referência (Ex: 70 - 99)</th>
                                            <th width="50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="item_nome[]" class="form-control form-control-sm" required></td>
                                            <td><input type="text" name="item_unidade[]" class="form-control form-control-sm"></td>
                                            <td><input type="text" name="item_referencia[]" class="form-control form-control-sm"></td>
                                            <td><button type="button" class="btn btn-sm btn-ghost-danger remove-row"><i class="ri-delete-bin-line"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-link link-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-label shadow-sm">
                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Exame
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- MODAL EDITAR EXAME E PARÂMETROS --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-info p-3 text-white">
                <h5 class="modal-title fw-bold text-white">Editar Exame e Parâmetros</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <label class="form-label fw-bold small">CATEGORIA</label>
                            <select name="exame_categoria_id" id="edit_categoria" class="form-select bg-light border-light" required>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-label fw-bold small">NOME DO EXAME</label>
                            <input type="text" name="nome" id="edit_nome" class="form-control bg-light border-light" required>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fw-bold small">CÓDIGO</label>
                            <input type="text" name="codigo" id="edit_codigo" class="form-control bg-light border-light" required>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input" type="checkbox" name="requer_jejum" id="edit_jejum" value="1">
                                <label class="form-check-label fw-bold" for="edit_jejum">Requer Jejum?</label>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <label class="form-label fw-bold small">DESCRIÇÃO / OBSERVAÇÕES</label>
                            <textarea name="descricao" id="edit_descricao" class="form-control bg-light border-light" rows="2"></textarea>
                        </div>
                        <div class="col-12"><hr></div>

                        <div class="col-12 text-end mb-2">
                            <button type="button" class="btn btn-sm btn-soft-primary" id="add-item-edit">
                                <i class="ri-add-circle-line me-1"></i> Adicionar Parâmetro
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle" id="table-edit-items">
                                <thead class="bg-light text-uppercase small">
                                    <tr>
                                        <th>Nome do Item</th>
                                        <th>Unidade</th>
                                        <th>Referência</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="edit-items-container">
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link link-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info shadow-sm">
                        <i class="ri-save-line me-1"></i> Guardar Alterações
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
    // Abrir modal para NOVA Categoria (Limpar campos)
    $('.btn-primary[data-bs-target="#modalCategoria"]').on('click', function() {
        $('#modalCategoriaLabel').html('<i class="ri-add-line me-1"></i> Nova Categoria');
        $('#form-categoria-save')[0].reset();
        $('#cat_id').val(''); // Garante que o ID está vazio para ser um CREATE
    });

    // Abrir modal para EDITAR Categoria
    $(document).on('click', '.btn-edit-cat', function() {
        const id = $(this).data('id');
        const nome = $(this).data('nome');

        $('#modalCategoriaLabel').html('<i class="ri-edit-2-line me-1"></i> Editar Categoria');
        $('#cat_id').val(id);
        $('#cat_nome').val(nome);
        $('#modalCategoria').modal('show');
    });

    // Submit do formulário (Já está bom, só garanta que o botão volte ao normal em caso de erro)
    $('#form-categoria-save').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processando...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="ri-save-line align-middle me-1"></i> Guardar Categoria');
                let erros = xhr.status === 422 ? Object.values(xhr.responseJSON.errors).flat().join('<br>') : 'Erro ao processar.';
                Swal.fire({ icon: 'error', title: 'Erro!', html: erros });
            }
        });
    });
});

$(document).ready(function() {

    // Função auxiliar para gerar linhas
    function generateItemRow(nome = '', unidade = '', referencia = '') {
        return `<tr>
            <td><input type="text" name="item_nome[]" value="${nome}" class="form-control form-control-sm" required></td>
            <td><input type="text" name="item_unidade[]" value="${unidade}" class="form-control form-control-sm"></td>
            <td><input type="text" name="item_referencia[]" value="${referencia}" class="form-control form-control-sm"></td>
            <td><button type="button" class="btn btn-sm btn-ghost-danger remove-row"><i class="ri-delete-bin-line"></i></button></td>
        </tr>`;
    }

    // --- SUBMIT PARA AMBOS OS FORMULÁRIOS (CREATE E EDIT) ---
    // Usamos o seletor para pegar os dois IDs de formulário
    $('#form-exame-create, #form-edit').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const originalHtml = btn.html(); // Guarda o ícone original

        // Ativar Spinner
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processando...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST', // Laravel trata o PUT via campo _method oculto
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            },
            error: function(xhr) {
                // Voltar botão ao estado normal
                btn.prop('disabled', false).html(originalHtml);

                if (xhr.status === 422) {
                    let erros = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    Swal.fire({ icon: 'error', title: 'Erro de Validação', html: erros });
                } else {
                    // Aqui captura o erro de "Mass Assignment" ou erro interno no SweetAlert
                    let msgErro = xhr.responseJSON ? xhr.responseJSON.message : 'Erro desconhecido';
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro no Servidor',
                        text: msgErro
                    });
                }
            }
        });
    });

    // --- CARREGAMENTO DO MODAL DE EDIÇÃO ---
    $(document).on('click', '.btn-edit', function() {
        const btn = $(this);
        $('#form-edit').attr('action', `/exames/${btn.data('id')}/atualizar`);

        // Se o formulário de edição não tiver o campo _method, vamos garantir que tenha
        if ($('#form-edit input[name="_method"]').length === 0) {
            $('#form-edit').append('<input type="hidden" name="_method" value="POST">');
        }

        $('#edit_nome').val(btn.data('nome'));
        $('#edit_codigo').val(btn.data('codigo'));
        $('#edit_categoria').val(btn.data('categoria'));
        $('#edit_descricao').val(btn.data('descricao'));
        $('#edit_jejum').prop('checked', btn.data('jejum') == 1);

        $('#edit-items-container').html('<tr><td colspan="4" class="text-center">Carregando...</td></tr>');

        $.get(`/exames/${btn.data('id')}/itens`, function(itens) {
            $('#edit-items-container').empty();
            if(itens.length > 0) {
                itens.forEach(item => {
                    // Aqui usamos as colunas corretas do seu banco de dados
                    $('#edit-items-container').append(generateItemRow(
                        item.descricao,
                        item.unidade_medida,
                        item.referencia_minimo
                    ));
                });
            } else {
                $('#edit-items-container').append(generateItemRow());
            }
        });
    });

    // Adicionar/Remover linhas (seus cliques já estão bons, mantenha-os)
    $('#add-item-row, #add-item-edit').click(function() {
        const target = $(this).attr('id') === 'add-item-row' ? '#table-items tbody' : '#edit-items-container';
        $(target).append(generateItemRow());
    });
});
</script>
@endpush
