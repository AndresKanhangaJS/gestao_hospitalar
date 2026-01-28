<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExameCategoria extends Model
{
    public function exames()
    {
        return $this->hasMany(Exame::class, 'exame_categoria_id');
    }
}
