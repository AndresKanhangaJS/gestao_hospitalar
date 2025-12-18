@extends('layouts.app')

@section('title', 'Permissões de Acesso')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Permissões</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">ACL</a></li>
                    <li class="breadcrumb-item active">Permissões</li>
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
                    <h5 class="card-title mb-0 flex-grow-1">Permissões</h5>
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#addPermissionModal"><i class="ri-add-line align-bottom me-1"></i> Registar</button>
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
                                <th class="sort" data-sort="project_name">Permissão</th>
                                <th class="sort" data-sort="priority">Acções</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <div class="hstack gap-3 fs-15">
                                        <a href="javascript:void(0);"
                                            class="link-primary edit-permission"
                                            data-id="{{ $permission->id }}"
                                            title="Editar">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="link-danger delete-permission"
                                            data-id="{{ $permission->id }}"
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
<div class="modal fade" id="deletePermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <lord-icon
                    src="https://cdn.lordicon.com/gsqxdxog.json"
                    trigger="loop"
                    colors="primary:#405189,secondary:#f06548"
                    style="width:90px;height:90px">
                </lord-icon>
                <h4>Eliminar permissão?</h4>
                <p class="text-muted">
                    Esta ação é permanente e pode afetar papéis associados.
                </p>

                <div class="hstack gap-2 justify-content-center">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" id="confirmDeletePermission">
                        Sim, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<!--end delete modal -->

<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0">
            <div class="modal-header bg-info-subtle">
                <h5 class="modal-title">Registar Permissão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Designação</label>
                    <input type="text" name="name" class="form-control" placeholder="Designação da permissão..." required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Registar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0">
            <div class="modal-header bg-warning-subtle">
                <h5 class="modal-title">Editar Permissão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editPermissionForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <label class="form-label">Designação</label>
                    <input type="text" name="name" id="edit-permission-name" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-warning">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>




@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // EDITAR
    const editModal = new bootstrap.Modal(document.getElementById('editPermissionModal'));
    const editForm  = document.getElementById('editPermissionForm');
    const nameInput = document.getElementById('edit-permission-name');

    document.querySelectorAll('.edit-permission').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            editForm.action = `/permissoes/${id}`;

            const res = await fetch(`/permissoes/${id}`);
            const data = await res.json();

            nameInput.value = data.name;
            editModal.show();
        });
    });

    // DELETE
    const deleteModal = new bootstrap.Modal(document.getElementById('deletePermissionModal'));
    let deleteId = null;

    document.querySelectorAll('.delete-permission').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteId = btn.dataset.id;
            deleteModal.show();
        });
    });

    document.getElementById('confirmDeletePermission')
        .addEventListener('click', async () => {

        if (!deleteId) return;

        const res = await fetch(`/permissoes/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (res.ok) {
            document
                .querySelector(`.delete-permission[data-id="${deleteId}"]`)
                .closest('tr')
                .remove();

            deleteModal.hide();
        }
    });

});
</script>
@endpush

@endsection
