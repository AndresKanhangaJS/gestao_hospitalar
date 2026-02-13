<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laudo Laboratorial - {{ $requisicao->codigo_requisicao }}</title>
    <style>
        @page { margin: 1.5cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }

        /* Cabeçalho */
        .header { border-bottom: 2px solid #004687; padding-bottom: 10px; margin-bottom: 20px; }
        .clinic-name { font-size: 20px; font-weight: bold; color: #004687; }
        .doc-title { text-align: center; font-size: 16px; font-weight: bold; margin: 10px 0; text-decoration: underline; }

        /* Box Paciente */
        .info-box { background: #f8f9fa; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px; }
        .info-table { width: 100%; }
        .label { font-weight: bold; color: #004687; font-size: 9px; text-transform: uppercase; }

        /* Tabela de Resultados */
        .exame-container { margin-bottom: 25px; }
        .exame-title { background: #004687; color: white; padding: 5px 10px; font-weight: bold; font-size: 12px; margin-bottom: 5px; }
        .results-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .results-table th { border-bottom: 1px solid #004687; padding: 6px; text-align: left; font-size: 10px; color: #555; }
        .results-table td { padding: 8px 6px; border-bottom: 1px solid #eee; }

        /* Alertas de Resultado */
        .valor-fora { font-weight: bold; color: #d9534f; } /* Vermelho se fora da ref */
        .valor-normal { font-weight: bold; }

        /* Notas */
        .obs-box { margin-top: 15px; padding: 10px; border: 1px solid #eee; font-style: italic; }

        /* Rodapé */
        .footer { position: fixed; bottom: 30px; width: 100%; text-align: center; border-top: 1px solid #eee; pt-10px; }
    </style>
</head>
<body>

    <div class="header">
        <table width="100%">
            <tr>
                <td class="clinic-name">CLÍNICA ISPAJ - LABORATÓRIO</td>
                <td align="right">REQ #{{ $requisicao->codigo_requisicao }}</td>
            </tr>
        </table>
    </div>

    <div class="doc-title">RELATÓRIO DE EXAMES LABORATORIAIS</div>

    <div class="info-box">
        <table class="info-table">
            <tr>
                <td width="60%">
                    <span class="label">Paciente:</span><br>
                    <span style="font-size: 13px; font-weight: bold;">{{ $paciente->nome_completo }}</span>
                </td>
                <td width="20%">
                    <span class="label">Idade:</span><br>
                    {{ $paciente->data_nascimento->age }} Anos
                </td>
                <td width="20%">
                    <span class="label">Sexo:</span><br>
                    {{ $paciente->genero }}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 10px;">
                    <span class="label">Médico Solicitante:</span><br>
                    {{ $medico->name }}
                </td>
                <td style="padding-top: 10px;">
                    <span class="label">Data Coleta:</span><br>
                    {{ $requisicao->created_at->format('d/m/Y') }}
                </td>
                <td style="padding-top: 10px;">
                    <span class="label">Emissão Laudo:</span><br>
                    {{ $requisicao->data_resultado ? date('d/m/Y H:i', strtotime($requisicao->data_resultado)) : '---' }}
                </td>
            </tr>
        </table>
    </div>

    @foreach($requisicao->itens as $item)
    <div class="exame-container">
        <div class="exame-title">{{ strtoupper($item->exame->nome) }}</div>
        <table class="results-table">
            <thead>
                <tr>
                    <th width="35%">PARÂMETRO</th>
                    <th width="20%">RESULTADO</th>
                    <th width="15%">UNIDADE</th>
                    <th width="30%">VALORES DE REFERÊNCIA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->resultados as $res)
                    @php
                        $foraDaFaixa = false;
                        if($res->exameItem->tipo_campo == 'numerico' && $res->valor_resultado) {
                            $v = floatval($res->valor_resultado);
                            $min = floatval($res->exameItem->referencia_minimo);
                            $max = floatval($res->exameItem->referencia_maximo);
                            if($v < $min || $v > $max) $foraDaFaixa = true;
                        }
                    @endphp
                <tr>
                    <td>{{ $res->exameItem->descricao }}</td>
                    <td class="{{ $foraDaFaixa ? 'valor-fora' : 'valor-normal' }}">
                        {{ $res->valor_resultado }}
                        {!! $foraDaFaixa ? ' <small>(*)</small>' : '' !!}
                    </td>
                    <td>{{ $res->exameItem->unidade_medida }}</td>
                    <td style="color: #666;">
                        {{ $res->exameItem->referencia_minimo }} a {{ $res->exameItem->referencia_maximo }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    @if($requisicao->observacoes_laboratorio)
        <div class="obs-box">
            <strong>Notas do Laboratório:</strong><br>
            {{ $requisicao->observacoes_laboratorio }}
        </div>
    @endif

    <div style="margin-top: 50px; text-align: center;">
        <div style="width: 250px; border-top: 1px solid #333; margin: 0 auto;"></div>
        <p style="margin-top: 5px;">
            <strong>Responsável Técnico</strong><br>
            {{-- <small>Liberação eletrônica via sistema</small> --}}
        </p>
    </div>

    <div class="footer">
        Gerado em {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
