<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Seguradora;
use App\Models\Episodio;
use App\Models\Medico;
use App\Models\NotaClinica;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Se for Médico
        if ($user->hasRole('Médico')) {
            return view('dashboard.medico', $this->getMedicoData($user));
        }

        // Se for Paciente
        if ($user->hasRole('Paciente')) {
            return view('dashboard.paciente', $this->getPacienteData($user));
        }

        // Se for Admin / Recepção (Dashboard Geral)
        $stats = [
            'total'        => Paciente::count(),
            'ativos'       => Paciente::where('status', 'activo')->count(),
            'particulares' => Paciente::whereNull('seguradora_id')->count(),
            'assegurados'  => Paciente::whereNotNull('seguradora_id')->count(),
        ];

        $generos = Paciente::select('genero', DB::raw('count(*) as total'))
            ->groupBy('genero')
            ->pluck('total', 'genero');

        $porSeguradora = Seguradora::withCount(['pacientes' => function($q) {
                $q->where('status', 'activo');
            }])
            ->get()
            ->where('pacientes_count', '>', 0);

        return view('dashboard.index', compact('stats', 'generos', 'porSeguradora'));
    }

    private function getMedicoData($user)
    {
        // Carrega o médico vinculado ao usuário
        $medico = Medico::where('user_id', $user->id)->first();

        if (!$medico) {
            return [
                'stats' => ['meus_pacientes' => 0, 'atendimentos_abertos' => 0, 'notas_hoje' => 0],
                'agenda_hoje' => collect([])
            ];
        }

        return [
            'stats' => [
                // Conta pacientes únicos atendidos por este médico
                'meus_pacientes' => Episodio::where('medico_id', $medico->id)->distinct('paciente_id')->count(),
                'atendimentos_abertos' => Episodio::where('medico_id', $medico->id)->where('situacao', 'Aberto')->count(),
                'notas_hoje' => NotaClinica::whereHas('episodio', function($q) use ($medico) {
                    $q->where('medico_id', $medico->id);
                })->whereDate('created_at', now())->count(),
            ],
            'agenda_hoje' => Episodio::with(['paciente:id,nome_completo,numero_documento', 'tipoAtendimento:id,nome'])
                ->where('medico_id', $medico->id)
                ->where('situacao', 'Aberto')
                ->whereDate('created_at', now()) // Apenas atendimentos de hoje
                ->latest()
                ->get()
        ];
    }
}
