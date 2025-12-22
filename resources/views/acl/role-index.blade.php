@extends('layouts.app')

@section('title', 'Papeis/Perfis de Acesso')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Papeis</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">ACL</a></li>
                    <li class="breadcrumb-item active">Papeis</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="tasksList">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Papeis</h5>
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            @can('papel.registar')
                                <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Registar
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form>
                    <div class="row g-3">
                        <div class="col-xxl-4 col-sm-12">
                            <div class="search-box">
                                <input type="text" class="form-control search bg-light border-light" placeholder="Search for tasks or something...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->

                        {{-- <div class="col-xxl-7 col-sm-12">
                            <div class="input-light">
                                <select class="form-control" data-choices data-choices-search-false name="choices-single-default" id="idStatus">
                                    <option value="">Status</option>
                                    <option value="all" selected>All</option>
                                    <option value="New">New</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Inprogress">Inprogress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div> --}}
                        <!--end col-->
                        <div class="col-xxl-1 col-sm-4">
                            <button type="button" class="btn btn-primary w-100" onclick="SearchData();"> <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                Filtrar
                            </button>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
            <!--end card-body-->
            <div class="card-body">
                <div class="table-responsive table-card mb-4">
                    <table class="table align-middle table-nowrap mb-0" id="tasksTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="sort" data-sort="ordem">#</th>
                                <th class="sort" data-sort="project_name">Papel</th>
                                <th class="sort" data-sort="tasks_name">Permissões</th>
                                @can('papeis.accoes')
                                    <th>Acções</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @if ($role->permissions)
                                        @foreach ($role->permissions as $permission)
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                @can('papeis.accoes')
                                <td>
                                    <div class="hstack gap-3 fs-15">
                                        @can('papeis.editar')
                                        <a href="javascript:void(0);" class="link-primary edit-role" data-id="{{ $role->id }}">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                        @endcan
                                        @can('papeis.eliminar')
                                        <a href="javascript:void(0);" class="link-danger delete-role" data-id="{{ $role->id }}">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
                {{-- <div class="d-flex justify-content-end mt-2">
                    <div class="pagination-wrap hstack gap-2">
                        <a class="page-item pagination-prev disabled" href="#">
                            Previous
                        </a>
                        <ul class="pagination listjs-pagination mb-0"></ul>
                        <a class="page-item pagination-next" href="#">
                            Next
                        </a>
                    </div>
                </div> --}}
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<div class="modal fade flip" id="deleteRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-5 text-center">

                <lord-icon
                    src="https://cdn.lordicon.com/gsqxdxog.json"
                    trigger="loop"
                    colors="primary:#405189,secondary:#f06548"
                    style="width:90px;height:90px">
                </lord-icon>

                <div class="mt-4 text-center">
                    <h4>Eliminar papel do sistema?</h4>

                    <p class="text-muted fs-14 mb-4">
                        Este papel controla permissões e níveis de acesso no sistema.
                        Ao eliminá-lo, os utilizadores associados deixarão de herdar estas permissões.
                        Esta ação é permanente.
                    </p>

                    <div class="hstack gap-2 justify-content-center">
                        <button
                            class="btn btn-link btn-ghost-success fw-medium text-decoration-none"
                            data-bs-dismiss="modal">
                            <i class="ri-close-line me-1 align-middle"></i> Cancelar
                        </button>

                        <button
                            class="btn btn-danger"
                            id="confirmDeleteRole">
                            Sim, eliminar papel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!--end delete modal -->

<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="exampleModalLabel">Registar Papel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" autocomplete="off" action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label for="Designacao-field" class="form-label">Designação</label>
                            <input
                                type="text"
                                id="Designacao-field"
                                name="name"
                                class="form-control"
                                placeholder="Designação do papel..."
                                required
                            />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <p>Permissões:</p>

                        @foreach ($permissions as $permission)
                            <div class="col-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        id="permission{{ $permission->id }}"
                                    >
                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="submit" class="btn btn-success">Registar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade zoomIn" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-warning-subtle">
                <h5 class="modal-title" id="edit-modal-title">Editar Papel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Designação</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>

                    <p>Permissões:</p>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-permission"
                                           type="checkbox"
                                           value="{{ $permission->name }}"
                                           name="permissions[]">
                                    <label class="form-check-label">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
{{-- Script para modal de edição de papel --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const modalEl = document.getElementById('editRoleModal');
        const editModal = new bootstrap.Modal(modalEl);
        const editForm  = document.getElementById('editRoleForm');
        const nameInput = document.getElementById('edit-name');
        const title     = document.getElementById('edit-modal-title');
        const checkboxes = document.querySelectorAll('.edit-permission');

        document.querySelectorAll('.edit-role').forEach(btn => {
            btn.addEventListener('click', async () => {

                // abre imediatamente
                editModal.show();

                // reset
                nameInput.value = '';
                checkboxes.forEach(cb => cb.checked = false);

                const roleId = btn.dataset.id;
                editForm.action = `/papeis/${roleId}`;

                const res = await fetch(`/papeis/${roleId}`);
                const data = await res.json();

                title.innerText = `Editar Papel | ${data.name}`;
                nameInput.value = data.name;

                checkboxes.forEach(cb => {
                    if (data.permissions.includes(cb.value)) {
                        cb.checked = true;
                    }
                });
            });
        });

    });
</script>

{{-- Script para modal de eliminação de papel --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

    const deleteModalEl = document.getElementById('deleteRoleModal');
    const deleteModal = new bootstrap.Modal(deleteModalEl);
    const deleteBtn = deleteModalEl.querySelector('#confirmDeleteRole');

    let deleteRoleId = null;

    // abrir modal
    document.querySelectorAll('.delete-role').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteRoleId = btn.dataset.id;
            deleteModal.show();
        });
    });

    // confirmar delete
    deleteBtn.addEventListener('click', async () => {

        if (!deleteRoleId) return;

        try {
            const res = await fetch(`/papeis/${deleteRoleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();

            if (!res.ok) {
                alert(data.message || 'Erro ao eliminar papel');
                return;
            }

            // fechar modal
            deleteModal.hide();

            // remover linha da tabela
            document
                .querySelector(`.delete-role[data-id="${deleteRoleId}"]`)
                .closest('tr')
                .remove();

            deleteRoleId = null;

        } catch (e) {
            console.error(e); // log para debug
            alert('Erro inesperado ao eliminar.');
        }

    });

});

</script>

@endpush



@endsection
