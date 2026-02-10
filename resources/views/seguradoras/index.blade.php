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
            <div class="card-header border-0 align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1 fw-bold text-muted">Gestão de Seguradoras & Empresas</h5>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0 bg-light-subtle">
                <form action="{{ route('seguradoras.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-xxl-5 col-sm-12">
                            <div class="search-box">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control search bg-white border-light"
                                    placeholder="Buscar por nome, código ou NIF...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select bg-white border-light" name="tipo">
                                <option value="">Tipo (Todos)</option>
                                <option value="seguradora" {{ request('tipo') == 'seguradora' ? 'selected' : '' }}>🛡️ Seguradora</option>
                                <option value="empresa" {{ request('tipo') == 'empresa' ? 'selected' : '' }}>🏢 Empresa</option>
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <select class="form-select bg-white border-light" name="status">
                                <option value="">Status (Todos)</option>
                                <option value="activo" {{ request('status') == 'activo' ? 'selected' : '' }}>🟢 Activo</option>
                                <option value="inactivo" {{ request('status') == 'inactivo' ? 'selected' : '' }}>🔴 Inactivo</option>
                            </select>
                        </div>

                        <div class="col-xxl-3 col-sm-4 d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm flex-grow-1">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filtrar
                            </button>
                            <a href="{{ route('seguradoras.index') }}" class="btn btn-soft-danger px-4 shadow-sm" title="Limpar Filtros">
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
                                <th scope="col" class="ps-4">Instituição</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">NIF</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($seguradoras as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="flex-grow-1 mb-0 fs-14 fw-bold text-dark">{{ $item->nome }}</h6>
                                            <small class="text-primary fw-medium">Cód: {{ $item->codigo_seguradora }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->tipo == 'seguradora')
                                        <span class="badge bg-info-subtle text-info text-uppercase px-2">
                                            <i class="ri-shield-check-line me-1"></i>Seguradora
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning text-uppercase px-2">
                                            <i class="ri-building-line me-1"></i>Empresa
                                        </span>
                                    @endif
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
                                            data-tipo="{{ $item->tipo }}"
                                            data-codigo="{{ $item->codigo_seguradora }}"
                                            data-nif="{{ $item->nif }}"
                                            data-telefone="{{ $item->telefone }}"
                                            data-email="{{ $item->email }}"
                                            data-status="{{ $item->status }}"
                                            data-fundo="{{ $item->fundo_global }}"
                                            data-limite="{{ $item->limite_por_funcionario }}"
                                            data-regras="{{ json_encode($item->regras) }}"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit">
                                            <i class="ri-pencil-fill"></i>
                                        </button>
                                    </div>
                                    <div class="modal fade" id="modalDetalhes{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header p-3 {{ $item->tipo == 'seguradora' ? 'bg-info' : 'bg-warning' }}">
                                                    <h5 class="modal-title text-white fw-bold">
                                                        <i class="ri-shield-user-line me-1 align-bottom"></i>
                                                        {{ $item->nome }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body p-4">
                                                    <div class="row">
                                                        <div class="col-lg-4 border-end border-light">
                                                            <div class="text-center mb-4">
                                                                <p class="text-muted mb-1">Código da Instituição</p>
                                                                <h5 class="mb-0 text-primary fw-bold">{{ $item->codigo_seguradora }}</h5>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">NIF</label>
                                                                <p class="fw-medium mb-0">{{ $item->nif }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">E-mail Corporativo</label>
                                                                <p class="fw-medium mb-0">{{ $item->email }}</p>
                                                            </div>
                                                            <div class="mb-0">
                                                                <label class="text-muted mb-0 fs-11 text-uppercase fw-bold">Contacto Telefónico</label>
                                                                <p class="fw-medium mb-0">{{ $item->telefone }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="mb-0">
                                                                <span class="badge {{ $item->status == 'activo' ? 'bg-success' : 'bg-danger' }} me-auto">
                                                                    Status: {{ strtoupper($item->status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        @php
                                                            // Cálculo do consumo
                                                            $valorConsumido = $item->fundo_global - $item->saldo_atual;
                                                            $percentagemConsumida = $item->fundo_global > 0
                                                                ? ($valorConsumido / $item->fundo_global) * 100
                                                                : 0;

                                                            // Define a cor baseada no consumo (Verde -> Amarelo -> Vermelho)
                                                            $corProgresso = 'bg-success';
                                                            if($percentagemConsumida >= 70 && $percentagemConsumida < 90) $corProgresso = 'bg-warning';
                                                            if($percentagemConsumida >= 90) $corProgresso = 'bg-danger';
                                                        @endphp
                                                        <div class="col-lg-8 ps-lg-4">
                                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                                <div>
                                                                    <h4 class="mb-0 fw-bold text-dark">
                                                                        <i class="ri-group-line me-1 text-primary"></i>
                                                                        {{ $item->pacientes_count ?? 0 }}
                                                                    </h4>
                                                                    <p class="text-muted mb-0 fs-12 text-uppercase">Assegurados Registados</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <h4 class="mb-0 fw-bold {{ $item->saldo_atual < ($item->fundo_global * 0.1) ? 'text-danger' : 'text-dark' }}">
                                                                        <small class="fs-13 fw-normal text-muted">Saldo Atual: </small>
                                                                        {{ number_format($item->saldo_atual, 2, ',', '.') }}
                                                                    </h4>
                                                                    <p class="text-muted mb-0 fs-12 text-uppercase">Disponível para Utilização</p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-4">
                                                                <div class="d-flex justify-content-between mb-1 fs-11 text-uppercase fw-bold">
                                                                    <span class="text-muted">Utilização do Fundo Global</span>
                                                                    <span class="{{ $percentagemConsumida > 90 ? 'text-danger' : 'text-muted' }}">
                                                                        {{ number_format($percentagemConsumida, 1) }}% Consumido
                                                                    </span>
                                                                </div>
                                                                <div class="progress progress-sm animated-progess" style="height: 8px;">
                                                                    <div class="progress-bar {{ $corProgresso }}"
                                                                        role="progressbar"
                                                                        style="width: {{ $percentagemConsumida }}%"
                                                                        aria-valuenow="{{ $percentagemConsumida }}"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                @if($percentagemConsumida >= 90)
                                                                    <small class="text-danger mt-1 d-block fs-11 italic">
                                                                        <i class="ri-error-warning-line me-1"></i> Atenção: Fundo quase esgotado!
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            <h6 class="text-primary text-uppercase fw-bold mb-3 fs-12">
                                                                <i class="ri-bank-card-2-line me-1"></i> Configurações Financeiras
                                                            </h6>

                                                            <div class="row g-3 mb-4">
                                                                <div class="col-sm-6">
                                                                    <div class="p-3 border border-dashed rounded bg-light">
                                                                        <span class="text-muted mb-2 d-block fs-13">Fundo Global</span>
                                                                        <h4 class="mb-0 text-success fw-bold">{{ number_format($item->fundo_global, 2, ',', '.') }}</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="p-3 border border-dashed rounded bg-light">
                                                                        <span class="text-muted mb-2 d-block fs-13">Limite por Funcionário</span>
                                                                        <h4 class="mb-0 text-info fw-bold">{{ number_format($item->limite_por_funcionario, 2, ',', '.') }}</h4>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <h6 class="text-primary text-uppercase fw-bold mb-3 fs-12">
                                                                <i class="ri-list-settings-line me-1"></i> Regras de Negócio e Observações
                                                            </h6>

                                                            <div class="bg-light border rounded overflow-hidden">
                                                                @if($item->regras && $item->regras->count() > 0)
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-nowrap mb-0 align-middle">
                                                                            <thead class="bg-soft-primary">
                                                                                <tr class="fs-11 text-uppercase">
                                                                                    <th class="ps-3">Categoria</th>
                                                                                    <th>Aplica-se a</th>
                                                                                    <th class="text-center">Tipo de Valor</th>
                                                                                    <th class="text-center">Empresa</th>
                                                                                    <th class="text-center">Paciente</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($item->regras as $regra)
                                                                                <tr>
                                                                                    <td class="ps-3">
                                                                                        <span class="fw-bold text-dark">{{ $regra->categoria }}</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="badge bg-info-subtle text-info text-capitalize">{{ $regra->aplicavel_a }}</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        @if($regra->tipo_valor == 'percentagem')
                                                                                            <span class="badge bg-primary-subtle text-primary">Percentagem</span>
                                                                                        @else
                                                                                            <span class="badge bg-success-subtle text-success">Valor Fixo</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        @if($regra->tipo_valor == 'percentagem')
                                                                                            <span class="text-success fw-medium">{{ number_format($regra->valor_empresa, 0) }}%</span>
                                                                                        @else
                                                                                            <span class="text-success fw-medium">{{ number_format($regra->valor_empresa, 2, ',', '.') }}</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        @if($regra->tipo_valor == 'percentagem')
                                                                                            <span class="text-danger fw-medium">{{ number_format($regra->valor_paciente, 0) }}%</span>
                                                                                        @else
                                                                                            <span class="text-danger fw-medium">{{ number_format($regra->valor_paciente, 2, ',', '.') }}</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @else
                                                                    <div class="text-center py-4 text-muted">
                                                                        <i class="ri-information-line fs-24 d-block mb-2"></i>
                                                                        <span class="fs-13 italic">Nenhuma regra específica (co-participação) configurada.</span>
                                                                    </div>
                                                                @endif
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
                    <p class="text-muted mb-0">Mostrando {{ $seguradoras->count() }} de {{ $seguradoras->total() }} registos</p>
                    <div>
                        {{ $seguradoras->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                    <i class="ri-bank-line me-1"></i> Registar Novo Convénio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-convenio-create" action="{{ route('seguradoras.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="max-height: 70vh;">

                    <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 14px;">
                        <i class="ri-information-line me-2 fs-20"></i> DADOS DA INSTITUIÇÃO
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-muted small">NOME DA INSTITUIÇÃO <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control border-light bg-light" placeholder="Ex: Unitel, SA" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TIPO DE CONVÉNIO</label>
                            <select name="tipo" class="form-select border-light bg-light fw-bold" required>
                                <option value="seguradora">Seguradora</option>
                                <option value="empresa">Empresa</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">CÓDIGO IDENTIFICADOR</label>
                            <input type="text" name="codigo_seguradora" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">NIF</label>
                            <input type="text" name="nif" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">TELEFONE</label>
                            <input type="text" name="telefone" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-12">
                            <label class="form-label fw-semibold text-muted small">E-MAIL DE CONTACTO</label>
                            <input type="email" name="email" class="form-control border-light bg-light">
                        </div>
                    </div>

                    <h5 class="card-title text-success border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 14px;">
                        <i class="ri-money-dollar-circle-line me-2 fs-20"></i> GESTÃO FINANCEIRA E PLAFOND
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">FUNDO GLOBAL INICIAL</label>
                            <div class="input-group">
                                <span class="input-group-text border-light bg-light-subtle text-success fw-bold">Kz</span>
                                <input type="number" step="0.01" name="fundo_global" class="form-control border-light bg-light">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">LIMITE POR FUNCIONÁRIO</label>
                            <div class="input-group">
                                <span class="input-group-text border-light bg-light-subtle text-primary fw-bold">Kz</span>
                                <input type="number" step="0.01" name="limite_por_funcionario" class="form-control border-light bg-light">
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 14px;">
                        <i class="ri-percent-line me-2 fs-20"></i> REGRAS DE CO-PAGAMENTO
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle" id="tabela-regras">
                            <thead class="text-muted small uppercase">
                                <tr>
                                    <th>Categoria</th>
                                    <th>Abrangência</th>
                                    <th width="21%">Tipo</th>
                                    <th width="12%">Empresa</th>
                                    <th width="12%">Paciente</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-light rounded-3">
                                    <td>
                                        <select name="regras[0][categoria]" class="form-select form-select-sm border-0 bg-transparent fw-bold">
                                            <option value="Consulta">Consulta</option>
                                            <option value="Medicamento">Medicamento</option>
                                            <option value="Laboratorio">Laboratório</option>
                                            <option value="Exames">Exames</option>
                                            <option value="Internamento">Internamento</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="regras[0][aplicavel_a]" class="form-select form-select-sm border-0 bg-transparent">
                                            <option value="todos">Todos</option>
                                            <option value="titular">Titular</option>
                                            <option value="dependente">Dependentes</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="regras[0][tipo_valor]" class="form-select form-select-sm border-0 bg-transparent fw-bold text-primary">
                                            <option value="percentagem">Percentagem (%)</option>
                                            <option value="fixo">Valor Fixo (Kz)</option>
                                        </select>
                                    </td>
                                    <td><input type="number" step="0.01" name="regras[0][empresa]" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" value="100"></td>
                                    <td><input type="number" step="0.01" name="regras[0][paciente]" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" value="0"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-soft-info mt-2" id="add-regra">
                            <i class="ri-add-line align-middle"></i> Adicionar Cláusula
                        </button>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-link link-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-label shadow-sm">
                        <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Convénio
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
                    <i class="ri-edit-2-line me-1"></i> Editar dados do Convénio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit" method="POST">
                @csrf
                <div class="modal-body p-4" style="max-height: 70vh;">
                    <h5 class="card-title text-info border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 13px;">
                        <i class="ri-hospital-line me-2 fs-18"></i> DADOS DA INSTITUIÇÃO
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-muted small">NOME DA INSTITUIÇÃO</label>
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
                            <label class="form-label fw-semibold text-muted small">TIPO DE CONVÉNIO</label>
                            <select name="tipo" id="edit_tipo" class="form-select border-light bg-light fw-bold" required>
                                <option value="seguradora">Seguradora</option>
                                <option value="empresa">Empresa</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">CÓDIGO</label>
                            <input type="text" id="edit_codigo" name="codigo_seguradora" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold text-muted small">NIF</label>
                            <input type="text" id="edit_nif" name="nif" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">TELEFONE</label>
                            <input type="text" id="edit_telefone" name="telefone" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">E-MAIL</label>
                            <input type="email" id="edit_email" name="email" class="form-control border-light bg-light">
                        </div>
                    </div>

                    <h5 class="card-title text-success border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 13px;">
                        <i class="ri-money-dollar-circle-line me-2 fs-18"></i> GESTÃO FINANCEIRA E PLAFOND
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">FUNDO GLOBAL</label>
                            <input type="number" step="0.01" id="edit_fundo_global" name="fundo_global" class="form-control border-light bg-light">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold text-muted small">LIMITE POR FUNCIONÁRIO</label>
                            <input type="number" step="0.01" id="edit_limite_por_funcionario" name="limite_por_funcionario" class="form-control border-light bg-light">
                        </div>
                    </div>

                    <h5 class="card-title text-primary border-bottom pb-3 mb-4 d-flex align-items-center" style="font-size: 13px;">
                        <i class="ri-percent-line me-2 fs-18"></i> REGRAS DE CO-PAGAMENTO
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle" id="tabela-regras-edit">
                            <thead class="text-muted small uppercase">
                                <tr>
                                    <th>Categoria</th>
                                    <th>Abrangência</th>
                                    <th width="21%">Tipo</th>
                                    <th width="12%">Empresa</th>
                                    <th width="12%">Paciente</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-soft-info mt-2" id="add-regra-edit">
                            <i class="ri-add-line align-middle"></i> Adicionar Cláusula
                        </button>
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
    // 1. LÓGICA GERAL (REGRAS DINÂMICAS)
    // ---------------------------------------------------------
    let regraIndex = 1;

    $('#add-regra').on('click', function() {
        let novaLinha = `
        <tr class="bg-light rounded-3 animate__animated animate__fadeIn">
            <td>
                <select name="regras[${regraIndex}][categoria]" class="form-select form-select-sm border-0 bg-transparent fw-bold">
                    <option value="Consulta">Consulta</option>
                    <option value="Medicamento">Medicamento</option>
                    <option value="Exames">Exames</option>
                    <option value="Laboratorio">Laboratório</option>
                    <option value="Internamento">Internamento</option>
                </select>
            </td>
            <td>
                <select name="regras[${regraIndex}][aplicavel_a]" class="form-select form-select-sm border-0 bg-transparent">
                    <option value="todos">Todos</option>
                    <option value="titular">Titular</option>
                    <option value="dependente">Dependentes</option>
                </select>
            </td>
            <td>
                <select name="regras[${regraIndex}][tipo_valor]" class="form-select form-select-sm border-0 bg-transparent fw-bold text-primary">
                    <option value="percentagem">Percentagem (%)</option>
                    <option value="fixo">Valor Fixo (Kz)</option>
                </select>
            </td>
            <td><input type="number" step="0.01" name="regras[${regraIndex}][empresa]" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" value="100"></td>
            <td><input type="number" step="0.01" name="regras[${regraIndex}][paciente]" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" value="0"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-link link-danger remove-regra"><i class="ri-delete-bin-line"></i></button>
            </td>
        </tr>`;

        $('#tabela-regras tbody').append(novaLinha);
        regraIndex++;
        $('.modal-body').animate({ scrollTop: $('.modal-body')[0].scrollHeight }, 500);
    });

    $(document).on('click', '.remove-regra', function() {
        $(this).closest('tr').remove();
    });

    // ---------------------------------------------------------
    // 2. LÓGICA DE REGISTO (STORE)
    // ---------------------------------------------------------
    $('#form-convenio-create').on('submit', function(e) {
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
                btn.prop('disabled', false).html('<i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Registar Convénio');

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
    // 3. LÓGICA DE EDIÇÃO (UPDATE)
    // ---------------------------------------------------------

    // Captura o clique no botão editar da tabela para preencher o modal
    let regraEditIndex = 0;

    // Função auxiliar para gerar linha de regra no modal de EDITAR
    function adicionarLinhaRegraEdit(dados = null) {
        let index = regraEditIndex;
        let novaLinha = `
        <tr class="bg-light rounded-3">
            <td>
                <select name="regras[${index}][categoria]" class="form-select form-select-sm border-0 bg-transparent fw-bold edit-categoria">
                    <option value="Consulta">Consulta</option>
                    <option value="Medicamento">Medicamento</option>
                    <option value="Laboratorio">Laboratório</option>
                    <option value="Exames">Exames</option>
                    <option value="Internamento">Internamento</option>
                </select>
            </td>
            <td>
                <select name="regras[${index}][aplicavel_a]" class="form-select form-select-sm border-0 bg-transparent edit-abrangencia">
                    <option value="todos">Todos</option>
                    <option value="titular">Titular</option>
                    <option value="dependente">Dependentes</option>
                </select>
            </td>
            <td>
                <select name="regras[${index}][tipo_valor]" class="form-select form-select-sm border-0 bg-transparent fw-bold text-primary edit-tipo">
                    <option value="percentagem">Percentagem (%)</option>
                    <option value="fixo">Valor Fixo (Kz)</option>
                </select>
            </td>
            <td><input type="number" step="0.01" name="regras[${index}][empresa]" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" value="${dados ? dados.valor_empresa : 100}"></td>
            <td><input type="number" step="0.01" name="regras[${index}][paciente]" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" value="${dados ? dados.valor_paciente : 0}"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-link link-danger remove-regra"><i class="ri-delete-bin-line"></i></button>
            </td>
        </tr>`;

        $('#tabela-regras-edit tbody').append(novaLinha);

        // Se houver dados (edição), selecionamos os valores nos selects
        if (dados) {
            let fila = $('#tabela-regras-edit tbody tr').last();
            fila.find('.edit-categoria').val(dados.categoria);
            fila.find('.edit-abrangencia').val(dados.aplicavel_a);
            fila.find('.edit-tipo').val(dados.tipo_valor);
        }
        regraEditIndex++;
    }

    // Evento ao clicar no botão Editar da Tabela
    $(document).on('click', '.btn-edit', function() {
        const btn = $(this);

        // 1. Limpar e Resetar a Tabela de Regras do Modal
        $('#tabela-regras-edit tbody').empty();
        regraEditIndex = 0;

        // 2. Preencher Campos Básicos
        $('#edit_nome').val(btn.data('nome'));
        $('#edit_tipo').val(btn.data('tipo'));
        $('#edit_codigo').val(btn.data('codigo'));
        $('#edit_nif').val(btn.data('nif'));
        $('#edit_status').val(btn.data('status'));
        $('#edit_telefone').val(btn.data('telefone'));
        $('#edit_email').val(btn.data('email'));
        $('#edit_fundo_global').val(btn.data('fundo'));
        $('#edit_limite_por_funcionario').val(btn.data('limite'));

        // 3. Carregar Regras Dinamicamente
        let regras = btn.data('regras');
        if (regras && regras.length > 0) {
            regras.forEach(r => adicionarLinhaRegraEdit(r));
        }

        // 4. Definir Action do Form
        $('#form-edit').attr('action', `/convenios/${btn.data('id')}/atualizar`);
    });

    // Botão Adicionar Regra dentro do Modal Edit
    $('#add-regra-edit').on('click', function() {
        adicionarLinhaRegraEdit();
    });

    // Submissão do formulário de edição
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
@push('styles')
<style>
    /* Esconde o botão remover apenas na primeira linha da tabela de regras */
    #tabela-regras tbody tr:first-child .remove-row {
        display: none !important;
    }

    /* Esconde o botão remover na primeira linha para evitar tabelas vazias */
    #tabela-regras tbody tr:first-child .remove-regra,
    #tabela-regras-edit tbody tr:first-child .remove-regra {
        display: none !important;
    }
</style>
@endpush
