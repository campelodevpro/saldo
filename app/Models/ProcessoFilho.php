<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessoFilho extends Model
{
    protected $table = 'processo_filho';
    protected $fillable = ['processo_pai_id', 'valor'];

    public function pai()
    {
        return $this->belongsTo(ProcessoPai::class, 'processo_pai_id');
    }
}
