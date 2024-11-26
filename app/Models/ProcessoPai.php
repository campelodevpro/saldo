<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="ProcessoPai",
 *     type="object",
 *     title="Processo Pai",
 *     description="Representa um processo pai no sistema.",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do processo pai",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="NPROCPAI",
 *         type="string",
 *         description="Número do Processo Pai",
 *         example="PRC12345"
 *     ),
 *     @OA\Property(
 *         property="VALORTOTAL",
 *         type="number",
 *         format="float",
 *         description="Valor total do processo pai",
 *         example=1000.50
 *     ),
 *     @OA\Property(
 *         property="NUMEROAPROVACAO",
 *         type="integer",
 *         description="Número de aprovações realizadas no processo",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="STATUSPROCESSO",
 *         type="string",
 *         description="Status do processo pai",
 *         example="Em andamento"
 *     ),
 *     @OA\Property(
 *         property="SALDO",
 *         type="number",
 *         format="float",
 *         description="Saldo disponível no processo pai",
 *         example=500.00
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de criação do processo",
 *         example="2024-11-26T10:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de atualização do processo",
 *         example="2024-11-26T12:00:00Z"
 *     )
 * )
 */

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
