<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessoFilho extends Model
{
    //
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'PROCESSOFILHO';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PROCESSOPAI_ID',
        'NPROCFILHO',
        'NPROCPAI',
        'VALOR',
        'STATUSPROCESSO',
        'NUMEROAPROVACAO',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Define the relationship with the ProcessoPai model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processoPai()
    {
        return $this->belongsTo(ProcessoPai::class, 'PROCESSOPAI_ID');
    }
}
