<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="ProcessoFilho",
 *     type="object",
 *     title="Processo Filho",
 *     properties={
 *         @OA\Property(
 *             property="id",
 *             type="integer",
 *             description="ID do processo filho"
 *         ),
 *         @OA\Property(
 *             property="STATUSPROCESSO",
 *             type="string",
 *             description="Status do processo"
 *         ),
 *         @OA\Property(
 *             property="created_at",
 *             type="string",
 *             format="date-time",
 *             description="Data de criação do processo"
 *         ),
 *         @OA\Property(
 *             property="updated_at",
 *             type="string",
 *             format="date-time",
 *             description="Data de atualização do processo"
 *         )
 *     }
 * )
 */


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
        'NUMEROAPROVACAO',
        'STATUSPROCESSO',
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
