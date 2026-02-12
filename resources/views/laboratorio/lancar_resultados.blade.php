@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow-lg border-0 sticky-top" style="top: 80px; z-index: 100;">
            <div class="card-header bg-dark p-4 border-0 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="ri-heart-pulse-line text-white" style="font-size: 100px;"></i>
                </div>

                <div class="d-flex align-items-center position-relative">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg p-1 bg-soft-light rounded-circle shadow-lg">
                            <div class="avatar-title bg-primary text-white rounded-circle fs-24 fw-bold border border-2 border-dark">
                                {{ substr($requisicao->episodio->paciente->nome_completo, 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="text-white mb-1 fw-bold letter-spacing-sm">
                                    {{ $requisicao->episodio->paciente->nome_completo }}
                                </h4>
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <span class="text-white-50 fs-13">
                                        #<span class="text-white fw-medium">{{ $requisicao->episodio->paciente->codigo_paciente }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <div class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                <i class="ri-hashtag me-1"></i> REQ: {{ $requisicao->codigo_requisicao }}
                            </div>

                            @php
                                $prioColor = $requisicao->prioridade == 'urgente' ? 'danger' : 'warning';
                            @endphp
                            <div class="badge bg-{{ $prioColor }}-subtle text-{{ $prioColor }} border border-{{ $prioColor }}-subtle px-3 py-2 rounded-pill shadow-sm">
                                <i class="ri-flashlight-fill me-1"></i> {{ strtoupper($requisicao->prioridade) }}
                            </div>

                            <div class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill">
                                <i class="ri-user-md-line me-1"></i> {{ $requisicao->medico->genero == 'Masculino' ? 'Dr.' : 'Dra.' }} {{ $requisicao->medico->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="p-4 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-uppercase fw-bold mb-0 fs-12 text-muted">Progresso do Lançamento</h6>
                        <span class="badge bg-success" id="perc-texto">0%</span>
                    </div>
                    <div class="progress progress-sm rounded-pill" style="height: 7px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="progress-lab"></div>
                    </div>
                </div>

                <div class="list-group list-group-flush" id="exame-nav">
                    @foreach($requisicao->itens as $item)
                    <a href="#exame-{{ $item->id }}" class="list-group-item list-group-item-action border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-3">
                                <div class="avatar-title rounded-circle bg-light text-muted nav-icon" id="nav-icon-{{ $item->id }}">
                                    <i class="ri-flask-line"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">{{ $item->exame->nome }}</h6>
                                <small class="text-muted">{{ $item->resultados->count() }} parâmetros</small>
                            </div>
                        </div>
                        <i class="ri-checkbox-circle-fill text-success fs-18 d-none check-done" id="check-{{ $item->id }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="card-footer p-4 bg-white">
                <button type="button" onclick="confirmarPublicacao()" class="btn btn-primary btn-lg w-100 shadow-sm btn-label">
                    <i class="ri-send-plane-fill label-icon align-middle fs-16 me-2"></i> PUBLICAR RESULTADOS
                </button>
                <a href="{{ route('laboratorio.index') }}" class="btn btn-link w-100 text-muted mt-2">Sair sem guardar</a>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">
        <form id="form-salvar-resultados" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="requisicao_id" value="{{ $requisicao->id }}">

            @foreach($requisicao->itens as $item)
            <div class="card border-0 shadow-sm mb-4 card-exame" id="exame-{{ $item->id }}">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-soft-primary text-primary me-3 fs-14">#{{ $loop->iteration }}</span>
                        <h5 class="card-title mb-0 fw-bold text-dark">{{ $item->exame->nome }}</h5>
                    </div>
                </div>
                <div class="card-body bg-light-subtle p-4">
                    <div class="row g-4">
                        @foreach($item->resultados as $res)
                        <div class="col-md-6">
                            <div class="form-group-lab bg-white p-3 rounded-3 shadow-sm border border-light transition-all">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="fw-semibold text-muted small mb-0">{{ strtoupper($res->exameItem->descricao) }}</label>
                                    <span class="badge bg-light text-primary">{{ $res->exameItem->unidade_medida }}</span>
                                </div>

                                <div class="input-group input-group-lg border-bottom">
                                    <input
                                        type="{{ $res->exameItem->tipo_campo == 'numerico' ? 'number' : 'text' }}"
                                        name="resultados[{{ $res->id }}]"
                                        class="form-control bg-transparent border-0 ps-0 input-lab"
                                        placeholder="0.00"
                                        data-item-id="{{ $item->id }}"
                                        data-min="{{ $res->exameItem->referencia_minimo }}"
                                        data-max="{{ $res->exameItem->referencia_maximo }}"
                                        value="{{ $res->valor_resultado }}"
                                    >
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted" style="font-size: 11px;">
                                        Ref: <span class="fw-bold">{{ $res->exameItem->referencia_minimo }} - {{ $res->exameItem->referencia_maximo }}</span>
                                    </span>
                                    <div class="status-indicator"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <div class="card border-0 shadow-lg overflow-hidden mb-5">
                <div class="card-header bg-dark py-3">
                    <h6 class="text-white mb-0"><i class="ri-chat-settings-line me-2"></i>Conclusão do Laboratório</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Observações do Técnico / Patologista</label>
                        <textarea name="observacoes_laboratorio" class="form-control border-light bg-light" rows="3" placeholder="Digite notas importantes para o médico..."></textarea>
                    </div>
                    <div class="p-3 bg-light rounded-3 border border-dashed border-primary">
                        <label class="form-label fw-bold mb-1">Anexo Digital (Opcional)</label>
                        <input type="file" name="arquivo_anexo" class="form-control border-0 bg-transparent">
                        <small class="text-muted">Formatos aceitos: PDF, JPG, PNG (Max 5MB)</small>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .transition-all { transition: all 0.3s ease; }
    .form-group-lab:focus-within {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
        border-color: #405189 !important;
    }
    .card-exame { scroll-margin-top: 100px; }
    .input-lab { font-weight: 700; color: #405189; }
    .input-lab::placeholder { font-weight: 400; color: #ced4da; }
</style>

<script>
$(document).ready(function() {
    function atualizarInterface() {
        let totalGlobal = $('.input-lab').length;
        let preenchidosGlobal = 0;

        $('.card-exame').each(function() {
            let card = $(this);
            let itemId = card.attr('id').split('-')[1];
            let inputs = card.find('.input-lab');
            let preenchidosNoCard = 0;

            inputs.each(function() {
                let input = $(this);
                let val = input.val();
                let min = parseFloat(input.data('min'));
                let max = parseFloat(input.data('max'));
                let indicador = input.closest('.form-group-lab').find('.status-indicator');

                if (val !== "") {
                    preenchidosNoCard++;
                    preenchidosGlobal++;

                    // Lógica de alerta
                    let nVal = parseFloat(val);
                    if (!isNaN(nVal) && !isNaN(min) && !isNaN(max)) {
                        if (nVal < min || nVal > max) {
                            indicador.html('<span class="text-danger fs-11 fw-bold"><i class="ri-error-warning-fill"></i> CRÍTICO</span>');
                            input.closest('.form-group-lab').addClass('border-danger-subtle bg-danger-subtle');
                        } else {
                            indicador.html('<span class="text-success fs-11 fw-bold"><i class="ri-checkbox-circle-fill"></i> NORMAL</span>');
                            input.closest('.form-group-lab').removeClass('border-danger-subtle bg-danger-subtle');
                        }
                    }
                } else {
                    indicador.empty();
                }
            });

            // Atualiza navegação lateral
            if (preenchidosNoCard === inputs.length && inputs.length > 0) {
                $(`#check-${itemId}`).removeClass('d-none');
                $(`#nav-icon-${itemId}`).addClass('bg-success text-white').removeClass('bg-light text-muted');
            } else {
                $(`#check-${itemId}`).addClass('d-none');
                $(`#nav-icon-${itemId}`).removeClass('bg-success text-white').addClass('bg-light text-muted');
            }
        });

        // Barra de progresso total
        let progresso = (preenchidosGlobal / totalGlobal) * 100;
        $('#progress-lab').css('width', progresso + '%');
        $('#perc-texto').text(Math.round(progresso) + '%');
    }

    $('.input-lab').on('input change', atualizarInterface);
    atualizarInterface(); // Rodar no load
});

function confirmarPublicacao() {
    Swal.fire({
        title: 'Finalizar Exames?',
        text: "Verifique se todos os valores estão corretos. O laudo será gerado imediatamente.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#34c38f',
        cancelButtonColor: '#f46a6a',
        confirmButtonText: '<i class="ri-check-line me-1"></i> Sim, publicar agora!',
        cancelButtonText: 'Revisar valores'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#form-salvar-resultados').submit();
        }
    });
}

function confirmarPublicacao() {
    const form = document.getElementById('form-salvar-resultados');
    const formData = new FormData(form);

    Swal.fire({
        title: 'Publicar Resultados?',
        text: "O laudo será gerado e ficará disponível para o paciente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, publicar!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: "{{ route('laboratorio.guardar') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            }).fail(error => {
                Swal.showValidationMessage(`Erro: ${error.responseJSON.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Sucesso!', 'Resultados publicados.', 'success')
                .then(() => window.location.href = "{{ route('laboratorio.index') }}");
        }
    });
}
</script>
@endpush
