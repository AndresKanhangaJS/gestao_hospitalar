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

        /* Cabeçalho Padrão Clínica */
        .header { border-bottom: 2px solid #004687; padding-bottom: 10px; margin-bottom: 30px; }
        .clinic-name { font-size: 24px; font-weight: bold; color: #004687; }
        .logo-placeholder { text-align: right; color: #004687; font-weight: bold; }

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

        /* Conteúdo Principal (Renderizado do Quill) */
        .content-body {
            min-height: 400px;
            text-align: justify;
            margin-bottom: 50px;
        }

        /* Estilização para o HTML que vem do Quill */
        .content-body p { margin-bottom: 15px; }
        .content-body strong { color: #000; }
        .ql-align-right { text-align: right; }
        .ql-align-center { text-align: center; }

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

        .date-location { margin-bottom: 40px; text-align: right; }

        .emissao-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8px;
            color: #aaa;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table width="100%">
            <tr>
                <td class="clinic-name">CLÍNICA ISPAJ</td>
                <td class="logo-placeholder">(LOGO)</td>
            </tr>
        </table>
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
                Nº de Ordem: {{ $medico->numero_ordem ?? '__________' }}
            </div>
        </div>
    </div>

    {{-- <div class="emissao-footer">
        Emitida eletronicamente pelo sistema
    </div> --}}

</body>
</html>
