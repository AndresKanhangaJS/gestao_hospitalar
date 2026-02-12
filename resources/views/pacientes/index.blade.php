@extends('layouts.app')

@section('title', 'Gestão de Pacientes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-user-heart-line me-1"></i> Pacientes
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Sistema</a></li>
                    <li class="breadcrumb-item active">Lista de Pacientes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-muted">Pacientes</h5>
                <div class="flex-shrink-0">
                    @can('pacientes.registar')
                    <a href="{{ route('pacientes.create') }}" class="btn btn-success btn-label">
                        <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Novo Paciente
                    </a>
                    <button type="button" class="btn btn-soft-secondary btn-label" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="ri-file-excel-2-line label-icon align-middle fs-16 me-2"></i> Importar
                    </button>
                    @endcan
                </div>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
                <form action="{{ route('pacientes.index') }}" method="GET">
                    <div class="row g-2"> <div class="col-xxl-3 col-sm-6">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control search bg-white border-light"
                                    placeholder="Nome, BI, Email ou Telefone...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-3">
                            <select class="form-select bg-white border-light" name="status">
                                <option value="">Status (Todos)</option>
                                <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-3">
                            <select class="form-select bg-white border-light" name="genero">
                                <option value="">Género</option>
                                <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Feminino" {{ request('genero') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select bg-white border-light" name="seguradora_id">
                                <option value="">Seguradora (Todas)</option>

                                {{-- Opção explícita para quem tem seguradora_id como NULL --}}
                                <option value="particular" {{ request('seguradora_id') === 'particular' ? 'selected' : '' }}>
                                    Particulares (Sem Seguro)
                                </option>

                                @foreach($seguradoras as $seg)
                                    <option value="{{ $seg->id }}" {{ request('seguradora_id') == $seg->id ? 'selected' : '' }}>
                                        {{ $seg->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xxl-3 col-sm-8 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                            </button>
                            <a href="{{ route('pacientes.index') }}" class="btn btn-soft-danger w-50 shadow-sm" title="Limpar Filtros">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mb-1">
                    <table class="table table-hover align-middle table-nowrap" id="pacientesTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th scope="col" class="ps-4">Paciente</th>
                                <th scope="col">Documentação</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Perfil (Idade/Género)</th>
                                <th scope="col">Convénio / Seguro</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pacientes as $paciente)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-soft-primary fw-bold">
                                                    {{ substr($paciente->nome_completo, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 fw-medium text-dark">{{ $paciente->nome_completo }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-body border-light">{{ $paciente->tipo_documento }}</span>
                                    <span class="text-muted ms-1">{{ $paciente->numero_documento }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span><i class="ri-phone-fill me-1 text-success fs-12"></i>{{ $paciente->telefone ?? '---' }}</span>
                                        <small class="text-muted">{{ $paciente->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark">
                                        @if($paciente->data_nascimento)
                                            {{ \Carbon\Carbon::parse($paciente->data_nascimento)->age }} Anos
                                        @else
                                            <span class="text-warning">Não informado</span>
                                        @endif
                                    </span>
                                    <span class="text-muted fs-11 ms-1">({{ $paciente->genero }})</span>
                                </td>
                                <td>
                                    @if($paciente->seguradora)
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium text-primary">{{ $paciente->seguradora->nome }}</span>
                                            @if($paciente->numero_cartao_seguro)
                                                <small class="text-muted"><i class="ri-id-card-line me-1"></i>{{ $paciente->numero_cartao_seguro }}</small>
                                            @else
                                                <span class="badge bg-warning w-fit-content" style="width: fit-content;">Nº Pendente</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">Particular</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $paciente->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-uppercase">
                                        {{ $paciente->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 justify-content-center">
                                        @can('pacientes.detalhes')
                                        <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-soft-info" title="Ver Ficha">
                                            <i class="ri-eye-fill"></i>
                                        </a>
                                        @endcan

                                        @can('pacientes.editar')
                                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-soft-primary" title="Editar">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                        @endcan

                                        @can('episodios.registar')
                                        <a href="{{ route('episodios.create', $paciente) }}" class="btn btn-soft-success btn-sm" title="Abrir Atendimento">
                                            <i class="ri-add-circle-line"></i>
                                        </a>
                                        @endcan

                                        @can('pacientes.eliminar')
                                        <button type="button"
                                                data-url="{{ route('pacientes.destroy', $paciente) }}"
                                                class="btn btn-sm btn-soft-danger btn-delete-paciente" title="Eliminar">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 text-muted">Nenhum paciente encontrado.</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 p-3">
                    <p class="text-muted mb-0">Mostrando {{ $pacientes->count() }} de {{ $pacientes->total() }} registos</p>
                    <div>
                        {{ $pacientes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Eliminar --}}
@can('pacientes.eliminar')
<div class="modal fade flip" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger-subtle p-3">
                <h5 class="modal-title text-danger"><i class="ri-error-warning-line me-1"></i> Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <lord-icon
                        src="https://cdn.lordicon.com/gsqxdxog.json"
                        trigger="loop"
                        colors="primary:#405189,secondary:#f06548"
                        style="width:90px;height:90px">
                    </lord-icon>

                    <div class="mt-4">
                        <h4 class="fw-bold">Atenção!</h4>
                        <p class="text-muted fs-15">Você está prestes a remover permanentemente o paciente: <br><b id="pacienteNome" class="text-dark"></b>.</p>

                        <div class="mb-3 text-start mt-4">
                            <label for="motivo" class="form-label fw-bold">Motivo da Exclusão <span class="text-danger">*</span></label>
                            <textarea class="form-control bg-light border-light" name="motivo" id="motivo" rows="3" required placeholder="Informe o motivo obrigatório..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">Manter Registo</button>
                    <button type="submit" class="btn btn-danger btn-label">
                        <i class="ri-delete-bin-line label-icon align-middle fs-16 me-2"></i> Confirmar e Excluir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

{{-- Modal de Importação --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title"><i class="ri-upload-cloud-2-line me-1"></i> Importar Pacientes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            @if(session()->has('import_errors'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="text-danger fw-bold"><i class="ri-error-warning-line me-2"></i> Erros na Importação:</h5>
                    <ul class="mb-0" style="max-height: 200px; overflow-y: auto;">
                        @foreach(session()->get('import_errors') as $validation)
                            <li>
                                <strong>Linha {{ $validation->row() }}:</strong>
                                @foreach($validation->errors() as $e)
                                    {{ $e }}
                                @endforeach
                                (Valor: <em>{{ $validation->values()[$validation->attribute()] ?? 'N/A' }}</em>)
                            </li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('pacientes.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Pacientes no Arquivo</label>
                        <select class="form-select" name="tipo_importacao" id="tipo_importacao">
                            <option value="particular">Particular (Sem Seguro)</option>
                            <option value="segurado">Assegurados (Com Convênio)</option>
                        </select>
                    </div>

                    <div id="seguradora_select_group" style="display: none;" class="mb-3 bg-light p-3 rounded border">
                        <label class="form-label fw-bold text-primary">Selecionar Seguradora para este lote</label>
                        <select class="form-select border-primary" name="seguradora_id">
                            <option value="">Escolha a seguradora...</option>
                            @foreach($seguradoras as $seg)
                                <option value="{{ $seg->id }}">{{ $seg->nome }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Todos os pacientes deste arquivo serão vinculados a esta seguradora.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Arquivo Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                        <div class="mt-2">
                            <a href="{{ asset('templates/modelo_importacao_pacientes.xlsx') }}" class="text-primary fs-12">
                                <i class="ri-download-line"></i> Descarregar modelo de Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btnImportar">Iniciar Importação</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // --- NOTIFICAÇÕES DE SESSÃO (IMPORTAÇÃO) ---
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: "{{ session('success') }}",
                timer: 4000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Ops! Algo correu mal',
                text: "{{ session('error') }}",
                confirmButtonText: 'Entendido'
            });
        @endif

        // --- LÓGICA DO MODAL DE IMPORTAÇÃO ---
        $('#tipo_importacao').on('change', function() {
            if ($(this).val() === 'segurado') {
                $('#seguradora_select_group').slideDown();
                $('select[name="seguradora_id"]').prop('required', true);
            } else {
                $('#seguradora_select_group').slideUp();
                $('select[name="seguradora_id"]').prop('required', false);
            }
        });

        $('#importForm').on('submit', function() {
            $('#btnImportar').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processando...');
        });

        // --- LÓGICA DE ELIMINAÇÃO AJAX ---
        $(document).on('click', '.btn-delete-paciente', function(e) {
            e.preventDefault();
            let url = $(this).data('url');
            let nome = $(this).closest('tr').find('.fw-medium').text();

            $('#deleteForm').attr('action', url);
            $('#pacienteNome').text(nome);
            $('#motivo').val('');
            $('#deleteModal').modal('show');
        });

        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btnSubmit = form.find('button[type="submit"]');

            btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> A eliminar...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado!',
                        text: response.message,
                        confirmButtonColor: '#0ab39c'
                    }).then(() => { location.reload(); });
                },
                error: function(xhr) {
                    btnSubmit.prop('disabled', false).html('<i class="ri-delete-bin-line label-icon align-middle fs-16 me-2"></i> Confirmar e Excluir');
                    let msg = (xhr.status === 422) ? 'O motivo é obrigatório.' : 'Erro ao eliminar.';
                    Swal.fire({ icon: 'error', title: 'Erro', text: msg });
                }
            });
        });
    });
</script>
@endpush
@endsection
