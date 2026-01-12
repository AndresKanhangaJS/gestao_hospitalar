@extends('layouts.app')

@section('title', 'Gestão de Médicos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between shadow-sm p-3 bg-white rounded">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">
                <i class="ri-nurse-line me-1"></i> Médicos
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Sistema</a></li>
                    <li class="breadcrumb-item active">Lista de Médicos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-muted">Corpo Clínico</h5>
                <div class="flex-shrink-0">
                    @can('medicos.registar')
                    <a href="{{ route('medicos.create') }}" class="btn btn-success btn-label">
                        <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Novo Médico
                    </a>
                    @endcan
                </div>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
                <form action="{{ route('medicos.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-xxl-4 col-sm-6">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control search bg-white border-light"
                                    placeholder="Nome, Nº Ordem, Especialidade ou Email...">
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

                        <div class="col-xxl-4 col-sm-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                            </button>
                            <a href="{{ route('medicos.index') }}" class="btn btn-soft-danger w-50 shadow-sm" title="Limpar Filtros">
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
                                <th scope="col" class="ps-4">Médico</th>
                                <th scope="col">Nº Ordem / Especialidade</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($medicos as $medico)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-soft-info text-info fw-bold">
                                                    {{ substr($medico->nome_completo, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-medium text-dark">{{ $medico->nome_completo }}</h6>
                                            <small class="text-muted">Usuário: {{ $medico->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $medico->numero_ordem }}</span>
                                    <div class="text-muted fs-12 mt-1">{{ $medico->especialidade ?? 'Geral' }}</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span><i class="ri-phone-fill me-1 text-success fs-12"></i>{{ $medico->telefone ?? '---' }}</span>
                                        <small class="text-muted">{{ $medico->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $medico->status == 'activo' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-uppercase">
                                        {{ $medico->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 justify-content-center">
                                        @can('medicos.detalhes')
                                        <a href="{{ route('medicos.show', $medico) }}" class="btn btn-sm btn-soft-info" title="Ver Perfil">
                                            <i class="ri-eye-fill"></i>
                                        </a>
                                        @endcan

                                        @can('medicos.editar')
                                        <a href="{{ route('medicos.edit', $medico) }}" class="btn btn-sm btn-soft-primary" title="Editar">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                        @endcan

                                        @can('medicos.eliminar')
                                        <button type="button" data-url="{{ route('medicos.destroy', $medico) }}" class="btn btn-sm btn-soft-danger btn-delete-medico" title="Eliminar">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 text-muted">Nenhum médico encontrado.</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 p-3">
                    <p class="text-muted mb-0">Mostrando {{ $medicos->count() }} de {{ $medicos->total() }} registos</p>
                    <div>{{ $medicos->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Modal de Eliminar --}}
@can('medicos.eliminar')
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
                        <p class="text-muted fs-15">Você está prestes a remover permanentemente o medico: <br><b id="medicoNome" class="text-dark"></b>.</p>

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

@push('scripts')
<script>
    // Lógica JS idêntica a do paciente, apenas trocando a classe do botão
    $(document).on('click', '.btn-delete-medico', function(e) {
        e.preventDefault();
        let url = $(this).data('url');
        let nome = $(this).closest('tr').find('.fw-medium').text();

        $('#deleteForm').attr('action', url);
        $('#medicoNome').text(nome); // Mude o ID no modal para medicoNome
        $('#deleteModal').modal('show');
    });
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find('button[type="submit"]');

        // Feedback visual no botão do modal
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
                    text: response.message || 'O registo foi removido com sucesso.',
                    confirmButtonText: 'Concluído',
                    confirmButtonColor: '#0ab39c', // Verde do seu padrão
                    allowOutsideClick: false
                }).then(() => {
                    location.reload(); // Recarrega a tabela para reflectir a exclusão
                });
            },
            error: function(xhr) {
                // Reabilita o botão em caso de erro
                btnSubmit.prop('disabled', false).html('<i class="ri-delete-bin-line label-icon align-middle fs-16 me-2"></i> Confirmar e Excluir');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        text: errors.motivo ? errors.motivo[0] : 'O motivo é obrigatório para excluir.',
                        confirmButtonText: 'Tentar Novamente',
                        confirmButtonColor: '#f7b84b' // Cor de aviso (Amarelo/Laranja)
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Não foi possível eliminar o registo. Tente novamente.',
                        confirmButtonText: 'Fechar',
                        confirmButtonColor: '#f06548' // Vermelho
                    });
                }
            }
        });
    });
</script>
@endpush
