<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessoPai extends Model
{
    protected $table = 'processo_pai';
    protected $fillable = ['nome', 'saldo', 'valor_total'];

    public function filhos()
    {
        return $this->hasMany(ProcessoFilho::class, 'processo_pai_id');
    }

}
