<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receita Médica - {{ $receita->codigo_receita }}</title>
    <style>
        @page { margin: 1.2cm; }
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* Cabeçalho Invertido */
        .header { border-bottom: 2px solid #004687; padding-bottom: 8px; margin-bottom: 15px; }
        .clinic-name { font-size: 22px; font-weight: bold; color: #004687; text-align: left; }
        .logo-placeholder { text-align: right; color: #004687; font-size: 20px; font-weight: bold; }

        /* Metadados */
        .receita-meta { text-align: right; font-size: 9px; color: #666; margin-bottom: 10px; }

        /* Paciente */
        .paciente-box { background: #f8f9fa; padding: 10px; border: 1px solid #eee; margin-bottom: 20px; border-radius: 4px; }
        .label { font-weight: bold; text-transform: uppercase; font-size: 9px; color: #004687; display: block; margin-bottom: 2px; }
        .paciente-nome { font-size: 13px; font-weight: bold; }

        /* Tabela de Prescrição */
        .prescricao-title { border-left: 4px solid #004687; padding-left: 8px; font-size: 12px; font-weight: bold; margin-bottom: 10px; color: #004687; }
        .itens-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .itens-table th { background: #f0f4f8; color: #004687; padding: 8px; text-align: left; font-size: 10px; border-bottom: 2px solid #004687; }
        .itens-table td { border-bottom: 1px solid #eee; padding: 8px; vertical-align: middle; word-wrap: break-word; }

        /* Prevenção de quebra de linha em medicamentos */
        .itens-table tr { page-break-inside: avoid; }
        .med-nome { font-weight: bold; font-size: 11px; color: #000; }

        /* Ênfase nas Observações */
        .obs-container {
            margin-top: 25px;
            padding: 12px;
            background-color: #fcfcfc;
            border: 1px solid #ddd;
            border-left: 5px solid #004687;
            page-break-inside: avoid; /* Evita que as observações cortem entre páginas */
        }
        .obs-content { font-size: 11px; color: #222; text-align: justify; line-height: 1.5; }

        /* Rodapé Centralizado */
        .footer { position: fixed; bottom: 40px; width: 100%; text-align: center; }
        .data-emissao { margin-bottom: 35px; font-size: 11px; text-transform: capitalize; }
        .signature-line { width: 280px; margin: 0 auto; border-top: 1px solid #333; padding-top: 5px; }
        .medico-info { font-size: 12px; font-weight: bold; }
        .medico-crm { font-size: 10px; color: #555; }

        /* Nota de Emissão Eletrônica */
        .emissao-eletronica { margin-top: 15px; font-size: 6px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>

    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="70%" class="clinic-name">{{ $empresa->nome ?? 'Clínica Hospitalar' }}</td>
                <td width="30%" class="logo-placeholder">
                    @if($empresa && $empresa->logo)
                        <img src="{{ public_path('storage/logos_empresas/' . $empresa->logo) }}" alt="Logo" style="max-height: 50px;">
                    @else
                        {{ 'LOGO' }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="receita-meta">
        <strong>RECEITA #</strong>{{ $receita->codigo_receita }} |
        <strong>EMISSÃO:</strong> {{ $receita->created_at->format('d/m/Y H:i') }}
    </div>

    <div class="paciente-box">
        <table width="100%">
            <tr>
                <td>
                    <span class="label">Paciente</span>
                    <span class="paciente-nome">{{ $paciente->nome_completo }}</span>
                </td>
                <td width="100px" align="right">
                    <span class="label">Idade</span>
                    <strong>{{ $paciente->data_nascimento->age ?? '--' }} anos</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="prescricao-title">PRESCRIÇÃO MÉDICA</div>

    <table class="itens-table">
        <thead>
            <tr>
                <th width="35%">Medicamento</th>
                <th width="15%">Dosagem</th>
                <th width="20%">Frequência</th>
                <th width="20%">Duração</th>
                <th width="10%">Qtd</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receita->itens as $item)
            <tr>
                <td class="med-nome">{{ $item->medicamento }}</td>
                <td>{{ $item->dosagem }}</td>
                <td>{{ $item->frequencia }}</td>
                <td>{{ $item->duracao ?? '---' }}</td>
                <td>{{ $item->quantidade }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($receita->observacoes_gerais)
        <div class="obs-container">
            <span class="label">Observações e Recomendações</span>
            <div class="obs-content">
                {!! nl2br(e($receita->observacoes_gerais)) !!}
            </div>
        </div>
    @endif

    <div class="footer">
        <div class="data-emissao">
            {{ date('d') }} de {{ \Carbon\Carbon::now()->locale('pt')->translatedFormat('M') }} de {{ date('Y') }}
        </div>

        <div class="signature-line">
            <div class="medico-info">
                {{ $medico->genero == 'Feminino' ? 'Dra.' : 'Dr.' }} {{ $medico->nome_completo }}
            </div>
            <div class="medico-crm">Nº de Ordem: {{ $medico->numero_ordem ?? '__________' }}</div>
        </div>

        {{-- <div class="emissao-eletronica">
            Emitido eletronicamente pelo sistema
        </div> --}}
    </div>

</body>
</html>
