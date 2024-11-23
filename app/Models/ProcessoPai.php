<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessoPai extends Model
{
    use HasFactory;

    protected $table = 'PROCESSOPAI';

    protected $fillable = [
        'NPROCPAI',
        'VALORTOTAL',
        'NUMEROAPROVACAO',
        'STATUSPROCESSO',
        'SALDO',
    ];

}
