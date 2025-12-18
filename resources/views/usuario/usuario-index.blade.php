@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Usuários</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">ACL</a></li> --}}
                    <li class="breadcrumb-item active">Usuários</li>
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
                    <h5 class="card-title mb-0 flex-grow-1">Usuários</h5>
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Registar</button>
                            <button class="btn btn-secondary" id="remove-actions" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
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
                                <th class="sort" data-sort="project_name">Nome</th>
                                <th class="sort" data-sort="tasks_name">Email</th>
                                <th class="sort" data-sort="tasks_name">Status</th>
                                <th class="sort" data-sort="priority">Acções</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if ($usuario->status == 'activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="hstack gap-3 fs-15">
                                        <a href="javascript:void(0);"
                                            class="link-primary edit-user"
                                            data-id="{{ $usuario->id }}"
                                            title="Editar">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="link-info view-user"
                                            data-id="{{ $usuario->id }}"
                                            title="Detalhes">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="link-danger delete-user"
                                            data-id="{{ $usuario->id }}"
                                            title="Eliminar">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
<div class="modal fade flip" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body p-5 text-center">
                <lord-icon
                    src="https://cdn.lordicon.com/gsqxdxog.json"
                    trigger="loop"
                    colors="primary:#405189,secondary:#f06548"
                    style="width:90px;height:90px">
                </lord-icon>

                <div class="mt-4">
                    <h4>Eliminar usuário?</h4>

                    <p class="text-muted fs-14 mb-4">
                        Esta ação deve ser utilizada apenas em casos de
                        <strong>erro no registo do usuário</strong>.
                        Ao confirmar, o usuário será removido permanentemente do sistema.
                    </p>

                    <div class="hstack gap-2 justify-content-center">
                        <button class="btn btn-light" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button class="btn btn-danger" id="confirmDeleteUser">
                            Sim, eliminar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!--end delete modal -->

<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="exampleModalLabel">Registar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" autocomplete="off" action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-7">
                            <label for="Designacao-field" class="form-label">Nome</label>
                            <input
                                type="text"
                                id="Designacao-field"
                                name="name"
                                class="form-control"
                                placeholder="Nome do usuário..."
                                required
                            />
                        </div>
                        <div class="col-lg-5">
                            <label for="Email-field" class="form-label">Email</label>
                            <input
                                type="email"
                                id="Email-field"
                                name="email"
                                class="form-control"
                                placeholder="Email do usuário..."
                                required
                            />
                        </div>
                    </div>

                    <div class="row mt-3">
                        <p>Papeis/Perfis:</p>

                        @foreach ($roles as $role)
                            <div class="col-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        id="role{{ $role->id }}"
                                    >
                                    <label class="form-check-label" for="role{{ $role->id }}">
                                        {{ $role->name }}
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

<div class="modal fade zoomIn" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-warning-subtle">
                <h5 class="modal-title" id="edit-user-title">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit-email" class="form-control" required>
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit-status" class="form-control">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-3">

                    <p>Papéis / Perfis:</p>
                    <div class="row">
                        @foreach ($roles as $role)
                            <div class="col-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input edit-role-checkbox"
                                        type="checkbox"
                                        value="{{ $role->name }}"
                                        name="roles[]"
                                    >
                                    <label class="form-check-label">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">
                        Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade zoomIn" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0">

            <div class="modal-header p-3 bg-primary-subtle">
                <h5 class="modal-title">
                    <i class="ri-user-3-line me-1"></i> Detalhes do Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Nome:</strong>
                        <span id="view-name" class="float-end"></span>
                    </li>

                    <li class="list-group-item">
                        <strong>Email:</strong>
                        <span id="view-email" class="float-end"></span>
                    </li>

                    <li class="list-group-item">
                        <strong>Status:</strong>
                        <span id="view-status" class="float-end"></span>
                    </li>

                    <li class="list-group-item">
                        <strong>Papéis:</strong>
                        <div id="view-roles" class="mt-2"></div>
                    </li>

                    <li class="list-group-item">
                        <strong>Registado em:</strong>
                        <span id="view-created" class="float-end"></span>
                    </li>

                    <li class="list-group-item">
                        <strong>Actualizado em:</strong>
                        <span id="view-updated" class="float-end"></span>
                    </li>
                </ul>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Fechar
                </button>
            </div>

        </div>
    </div>
</div>




@push('scripts')
{{-- Script para modal de edição de usuário --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const modalEl = document.getElementById('editUserModal');
    const editModal = new bootstrap.Modal(modalEl);

    const form = document.getElementById('editUserForm');
    const nameInput = document.getElementById('edit-name');
    const emailInput = document.getElementById('edit-email');
    const statusSelect = document.getElementById('edit-status');
    const roleCheckboxes = document.querySelectorAll('.edit-role-checkbox');

    document.querySelectorAll('.edit-user').forEach(btn => {
        btn.addEventListener('click', async () => {

            editModal.show(); // abre imediatamente

            // reset
            nameInput.value = '';
            emailInput.value = '';
            statusSelect.value = 'activo';
            roleCheckboxes.forEach(cb => cb.checked = false);

            const userId = btn.dataset.id;
            form.action = `/usuarios/${userId}`;

            const res = await fetch(`/usuarios/${userId}`);
            const data = await res.json();

            nameInput.value = data.name;
            emailInput.value = data.email;
            statusSelect.value = data.status;

            roleCheckboxes.forEach(cb => {
                if (data.roles.includes(cb.value)) {
                    cb.checked = true;
                }
            });
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const modalEl = document.getElementById('viewUserModal');
    const viewModal = new bootstrap.Modal(modalEl);

    const nameEl = document.getElementById('view-name');
    const emailEl = document.getElementById('view-email');
    const statusEl = document.getElementById('view-status');
    const rolesEl = document.getElementById('view-roles');
    const createdEl = document.getElementById('view-created');
    const updatedEl = document.getElementById('view-updated');

    document.querySelectorAll('.view-user').forEach(btn => {
        btn.addEventListener('click', async () => {

            viewModal.show();

            // reset
            rolesEl.innerHTML = '';

            const userId = btn.dataset.id;

            const res = await fetch(`/usuarios/${userId}/details`);
            const data = await res.json();

            nameEl.textContent = data.name;
            emailEl.textContent = data.email;
            createdEl.textContent = data.created_at;
            updatedEl.textContent = data.updated_at;

            // status badge
            statusEl.innerHTML = data.status === 'activo'
                ? '<span class="badge bg-success">Activo</span>'
                : '<span class="badge bg-danger">Inactivo</span>';

            // roles
            if (data.roles.length) {
                data.roles.forEach(role => {
                    rolesEl.innerHTML += `
                        <span class="badge bg-info me-1">${role}</span>
                    `;
                });
            } else {
                rolesEl.innerHTML = '<span class="text-muted">Sem papel atribuído</span>';
            }
        });
    });

});
</script>

{{-- Script para modal de eliminação de usuário --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const modalEl = document.getElementById('deleteUserModal');
    const deleteModal = new bootstrap.Modal(modalEl);
    const confirmBtn = document.getElementById('confirmDeleteUser');

    let deleteUserId = null;

    document.querySelectorAll('.delete-user').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteUserId = btn.dataset.id;
            deleteModal.show();
        });
    });

    confirmBtn.addEventListener('click', async () => {

        if (!deleteUserId) return;

        try {
            const res = await fetch(`/usuarios/${deleteUserId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (!res.ok) {
                alert(data.message || 'Erro ao eliminar usuário.');
                return;
            }

            // remover linha da tabela
            document
                .querySelector(`.delete-user[data-id="${deleteUserId}"]`)
                .closest('tr')
                .remove();

            deleteModal.hide();
            deleteUserId = null;

        } catch (e) {
            console.error(e);
            alert('Erro inesperado ao eliminar.');
        }

    });

});
</script>

@endpush



@endsection
