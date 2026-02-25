<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $documento->tipo }} - {{ $paciente->nome_completo }}</title>
    <style>
        @page { margin: 1.5cm; }
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        /* Cabeçalho Dinâmico */
        .header { border-bottom: 2px solid #004687; padding-bottom: 10px; margin-bottom: 30px; }
        .clinic-name { font-size: 22px; font-weight: bold; color: #004687; }

        /* Título do Documento */
        .doc-title {
            text-align: center;
            text-transform: uppercase;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #000;
            text-decoration: underline;
        }

        .content-body {
            min-height: 400px;
            text-align: justify;
            margin-bottom: 50px;
        }

        /* Estilos vindos do Editor Quill */
        .content-body p { margin-bottom: 15px; }
        .ql-align-right { text-align: right; }
        .ql-align-center { text-align: center; }
        .ql-align-justify { text-align: justify; }

        /* Rodapé de Assinatura */
        .footer-signature {
            margin-top: 60px;
            text-align: center;
        }
        .signature-line {
            width: 300px;
            margin: 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .medico-nome { font-size: 13px; font-weight: bold; }
        .medico-detalhe { font-size: 10px; color: #555; }

        .date-location { margin-bottom: 20px; text-align: right; font-style: italic; }
    </style>
</head>
<body>

    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="70%" class="clinic-name">
                    {{ $empresa->nome ?? 'Clínica Hospitalar' }}
                </td>
                <td width="30%" align="right">
                    @if($empresa && $empresa->logo)
                        <img src="{{ public_path('storage/logos_empresas/' . $empresa->logo) }}" alt="Logo" style="max-height: 60px;">
                    @else
                        <span style="color: #004687; font-weight: bold;">LOGO</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="date-location">
        {{ $empresa->cidade ?? 'Luanda' }}, {{ date('d') }} de {{ \Carbon\Carbon::now()->locale('pt')->translatedFormat('F') }} de {{ date('Y') }}
    </div>

    <div class="doc-title">
        {{ $documento->titulo ?? $documento->tipo }}
    </div>

    <div class="content-body">
        {!! $documento->conteudo !!}
    </div>

    <div class="footer-signature">
        <div class="signature-line">
            <div class="medico-nome">
                {{ $medico->genero == 'Feminino' ? 'Dra.' : 'Dr.' }} {{ $medico->nome_completo }}
            </div>
            <div class="medico-detalhe">
                {{ $medico->especialidadeRelacao->nome ?? 'Médico' }}<br>
                Nº de Ordem: {{ $medico->numero_ordem ?? '__________' }}
            </div>
        </div>
    </div>

</body>
</html>
