@extends('layouts.app')
@section('title', 'Gestão de Empresas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-building-4-line me-1"></i> Gestão de Empresas
            </h4>
            <button type="button" class="btn btn-success btn-label" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Nova Empresa
            </button>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-muted">Gestão de Empresas</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mb-1">
                    <table class="table table-hover align-middle table-nowrap">
                        <thead class="table-light text-muted">
                            <tr>
                                <th scope="col" class="ps-4">Instituição</th>
                                <th scope="col">NIF</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($empresas as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="flex-grow-1 mb-0 fs-14 fw-bold text-dark">{{ $item->nome }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-medium">{{ $item->nif }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-medium"><i class="ri-phone-line me-1 text-primary fs-12"></i>{{ $item->telefone }}</span>
                                        <small class="text-muted"><i class="ri-mail-line me-1 fs-12"></i>{{ $item->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $item->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-uppercase">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="hstack gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-soft-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetalhes{{ $item->id }}"
                                            title="Ver Detalhes">
                                            <i class="ri-eye-fill"></i>
                                        </button>
                                        <button class="btn btn-sm btn-soft-info btn-edit"
                                            data-id="{{ $item->id }}"
                                            data-nome="{{ $item->nome }}"
                                            data-nif="{{ $item->nif }}"
                                            data-telefone="{{ $item->telefone }}"
                                            data-telefone1="{{ $item->telefone_alternativo_a }}"
                                            data-telefone2="{{ $item->telefone_alternativo_b }}"
                                            data-email="{{ $item->email }}"
                                            data-email_alt="{{ $item->email_alternativo }}"
                                            data-localizacao="{{ $item->localizacao }}"
                                            data-status="{{ $item->status }}"
                                            data-logo="{{ $item->logo }}"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit">
                                            <i class="ri-pencil-fill"></i>
                                        </button>
                                    </div>
                                    <div class="modal fade" id="modalDetalhes{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header p-3 bg-primary">
                                                    <h5 class="modal-title text-white fw-bold">
                                                        <i class="ri-building-line me-1 align-bottom"></i>
                                                        DETALHES DA INSTITUIÇÃO
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body p-4">
                                                    <div class="row">
                                                        <div class="col-lg-4 text-center border-end border-light">
                                                            <div class="mb-4">
                                                                <label class="text-muted mb-2 fs-11 text-uppercase fw-bold d-block">Logotipo Registado</label>
                                                                <div class="mx-auto border rounded bg-light d-flex align-items-center justify-content-center p-2"
                                                                    style="width: 160px; height: 100px; border: 1px solid #e9ebec !important;">
                                                                    @if($item->logo)
                                                                        <img src="{{ asset('storage/logos_empresas/' . $item->logo) }}"
                                                                            alt="Logo {{ $item->nome }}"
                                                                            class="img-fluid"
                                                                            style="max-height: 100%; object-fit: contain;">
                                                                    @else
                                                                        <img src="{{ asset('assets/images/img-placeholder.png') }}"
                                                                            alt="Sem Logo"
                                                                            class="img-fluid opacity-50"
                                                                            style="max-height: 60px;">
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="text-muted mb-1 fs-11 text-uppercase fw-bold d-block">Status da Conta</label>
                                                                <span class="badge {{ $item->status == 'activo' ? 'bg-success' : 'bg-danger' }} fs-12 px-3">
                                                                    <i class="ri-checkbox-circle-line me-1"></i> {{ strtoupper($item->status) }}
                                                                </span>
                                                            </div>

                                                            <small class="text-muted d-block mt-4">Registado em:</small>
                                                            <span class="fw-medium text-dark">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>

                                                        <div class="col-lg-8 ps-lg-4">
                                                            <div class="row g-3">
                                                                <div class="col-12 mb-2">
                                                                    <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">Nome da Instituição</label>
                                                                    <p class="fw-bold text-primary fs-16 mb-0">{{ $item->nome }}</p>
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">NIF / Identificação</label>
                                                                    <p class="fw-medium text-dark mb-0">{{ $item->nif }}</p>
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">E-mail Principal</label>
                                                                    <p class="fw-medium text-dark mb-0">{{ $item->email ?? 'N/A' }}</p>
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">Telefone Principal</label>
                                                                    <p class="fw-medium text-dark mb-0">{{ $item->telefone ?? 'N/A' }}</p>
                                                                </div>

                                                                <div class="col-sm-6">
                                                                    <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">Telefones Alternativos</label>
                                                                    <p class="text-muted mb-0" style="font-size: 13px;">
                                                                        {{ $item->telefone_alternativo_a ?? '-' }} / {{ $item->telefone_alternativo_b ?? '-' }}
                                                                    </p>
                                                                </div>

                                                                <div class="col-12">
                                                                    <hr class="my-2 border-light">
                                                                    <label class="text-muted mb-1 fs-11 text-uppercase fw-bold"><i class="ri-map-pin-line me-1"></i>Localização</label>
                                                                    <p class="fw-medium text-dark mb-0 lh-base">
                                                                        {{ $item->localizacao ?? 'Nenhuma localização registada.' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
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
                    <p class="text-muted mb-0">Mostrando {{ $empresas->count() }} de {{ $empresas->total() }} registos</p>
                    <div>
                        {{ $empresas->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL REGISTAR --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white fw-bold" id="modalCreateLabel">
                    <i class="ri-building-4-line me-1"></i> Registar Nova Empresa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-empresa-create" action="{{ route('empresas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4" style="max-height: 70vh;">

                    <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 14px;">
                        <i class="ri-information-line me-2 fs-20"></i> DADOS DA INSTITUIÇÃO
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label fw-semibold text-muted small d-block">LOGOTIPO DA EMPRESA</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="border rounded bg-light d-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 80px; border: 2px dashed #ced4da !important;">
                                    <img id="logo-preview"
                                        src="{{ asset('assets/images/img-placeholder.png') }}"
                                        style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="logotipo" id="logotipo-input" class="form-control" accept="image/*">
                                    <small class="text-muted">Formatos suportados: PNG, JPG (Máx. 2MB)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-muted small">NOME<span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">NIF <span class="text-danger">*</span></label>
                            <input type="text" name="nif" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE PRINCIPAL</label>
                            <input type="text" name="telefone" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE ALTERNATIVO 1</label>
                            <input type="text" name="telefone_alternativo_1" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE ALTERNATIVO 2</label>
                            <input type="text" name="telefone_alternativo_2" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">E-MAIL PRINCIPAL</label>
                            <input type="email" name="email" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">E-MAIL ALTERNATIVO</label>
                            <input type="email" name="email_alternativo" class="form-control border-light bg-light">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Localização</label>
                            <textarea class="form-control bg-light" rows="3" name="localizacao"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-link link-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-label shadow-sm">
                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info p-3">
                <h5 class="modal-title text-white fw-bold">
                    <i class="ri-edit-2-line me-1"></i> Editar dados da Empresa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4" style="max-height: 70vh;">
                    <h5 class="card-title text-info border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 13px;">
                        <i class="ri-information-line me-2 fs-18"></i> DADOS DA INSTITUIÇÃO
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label fw-semibold text-muted small d-block">NOVO LOGOTIPO (DEIXE VAZIO PARA MANTER)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div id="preview-edit-container" class="border rounded bg-light d-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 80px; border: 2px dashed #ced4da !important;">
                                    <img id="edit-logo-preview" src="{{ asset('assets/images/img-placeholder.png') }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="logotipo" id="edit-logotipo-input" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-muted small">NOME</label>
                            <input type="text" id="edit_nome" name="nome" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">STATUS</label>
                            <select name="status" id="edit_status" class="form-select border-light bg-light fw-bold">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">NIF</label>
                            <input type="text" id="edit_nif" name="nif" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE PRINCIPAL</label>
                            <input type="text" id="edit_telefone" name="telefone" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE ALTERNATIVO 1</label>
                            <input type="text" id="edit_telefone_1" name="telefone_alternativo_1" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE ALTERNATIVO 2</label>
                            <input type="text" id="edit_telefone_2" name="telefone_alternativo_2" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">E-MAIL PRINCIPAL</label>
                            <input type="email" id="edit_email" name="email" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">E-MAIL ALTERNATIVO</label>
                            <input type="email" id="edit_email_alt" name="email_alternativo" class="form-control border-light bg-light">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small">LOCALIZAÇÃO</label>
                            <textarea id="edit_localizacao" name="localizacao" class="form-control bg-light" rows="2"></textarea>
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
    $('#form-empresa-create').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processando...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
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
                btn.prop('disabled', false).html('<i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Empresa');

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
    // 2. LÓGICA DE EDIÇÃO (UPDATE)
    // ---------------------------------------------------------
   $('.btn-edit').on('click', function() {
        const btn = $(this);
        const id = btn.data('id');

        // Define a URL
        $('#form-edit').attr('action', `/empresas/${id}/atualizar`);

        // Preenche os inputs
        $('#edit_nome').val(btn.data('nome'));
        $('#edit_nif').val(btn.data('nif'));
        $('#edit_telefone').val(btn.data('telefone'));
        $('#edit_telefone_1').val(btn.data('telefone1'));
        $('#edit_telefone_2').val(btn.data('telefone2'));
        $('#edit_email').val(btn.data('email'));
        $('#edit_email_alt').val(btn.data('email_alt'));
        $('#edit_status').val(btn.data('status'));
        $('#edit_localizacao').val(btn.data('localizacao'));

        // Lógica do Preview do Logo no Edit
        const logo = btn.data('logo');
        if (logo) {
            $('#edit-logo-preview').attr('src', `/storage/logos_empresas/${logo}`);
        } else {
            $('#edit-logo-preview').attr('src', "{{ asset('assets/images/img-placeholder.png') }}");
        }
    });

    // Preview imediato ao selecionar arquivo no modal de Edição
    document.getElementById('edit-logotipo-input').addEventListener('change', function(event) {
        const preview = document.getElementById('edit-logo-preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; }
            reader.readAsDataURL(file);
        }
    });
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> A Guardar...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
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
                btn.prop('disabled', false).html('<i class="ri-save-3-line align-bottom me-1"></i> Guardar Alterações');

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

document.getElementById('logotipo-input').addEventListener('change', function(event) {
    const preview = document.getElementById('logo-preview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            // Remove o efeito de opacidade caso queira destacar a nova imagem
            preview.style.opacity = "1";
        }

        reader.readAsDataURL(file);
    } else {
        // Volta para a imagem padrão se o usuário cancelar
        preview.src = "{{ asset('assets/images/users/multi-user.jpg') }}";
    }
});

function openEditFromDetail(id) {
    // 1. Fecha o modal de detalhes atual
    var modalDetalhes = bootstrap.Modal.getInstance(document.getElementById('modalDetalhes' + id));
    modalDetalhes.hide();

    // 2. Dispara o clique no botão de editar da tabela que já tem os dados (data-attributes)
    // Isso evita ter que buscar os dados novamente via AJAX
    document.querySelector(`.btn-edit[data-id="${id}"]`).click();
}
</script>
@endpush
