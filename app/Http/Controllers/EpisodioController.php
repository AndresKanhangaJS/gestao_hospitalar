<?php

namespace App\Http\Controllers;

use App\Models\Episodio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EpisodioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'tipo_atendimento_id' => 'required|exists:tipos_atendimentos,id',
            'medico_id' => 'required|exists:users,id',
        ]);

        // Código de Atendimento (Ex: ISPAJ-2025-0001)
        $ultimoId = Episodio::max('id') + 1;
        $codigo = "ISPAJ-" . date('Y') . "-" . str_pad($ultimoId, 4, '0', STR_PAD_LEFT);

        Episodio::create([
            'paciente_id'         => $request->paciente_id,
            'medico_id'           => $request->medico_id,
            'tipo_atendimento_id' => $request->tipo_atendimento_id,
            'user_id_criacao'     => Auth::id(),
            'codigo_atendimento'  => $codigo,
            'data_abertura'       => Carbon::now(),
            'situacao'            => 'Aberto',
            'status'              => 'activo'
        ]);

        return redirect()->back()->with('success', 'Atendimento iniciado: ' . $codigo);
    }
}
