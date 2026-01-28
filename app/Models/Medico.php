<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Episodio;
use App\Traits\HasHashId;

class Medico extends Model
{
    use HasFactory, SoftDeletes, HasHashId;

    protected $fillable = [
        'user_id',
        'codigo_medico',
        'nome_completo',
        'data_nascimento',
        'genero',
        'tipo_documento',
        'numero_documento',
        'telefone',
        'email',
        'morada',
        'numero_ordem',
        'especialidade',
        'user_id_criacao',
        'user_id_atualizacao',
        'status'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    // Relacionamentos

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function criador()
    {
        return $this->belongsTo(User::class, 'user_id_criacao');
    }

    public function atualizador()
    {
        return $this->belongsTo(User::class, 'user_id_atualizacao');
    }

    public function episodios()
    {
        return $this->hasMany(Episodio::class, 'medico_id');
    }
}
