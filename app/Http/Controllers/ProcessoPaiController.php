<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessoPai;
use App\Models\ProcessoFilho;
use Illuminate\Http\JsonResponse;



/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentação da API",
 *      description="Descrição da API do Sistema",
 *      @OA\Contact(
 *          email="suporte@seusistema.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Servidor da API"
 * )
 */

class ProcessoPaiController extends Controller
{
    
/**
 * Criar ou atualizar um processo pai.
 *
 * Esta rota permite criar um novo processo pai ou atualizar um já existente com base no campo `NPROCPAI`.
 * Caso o processo já exista, o sistema verifica e atualiza informações como `VALORTOTAL`, `SALDO` e `NUMEROAPROVACAO`.
 *
 * @OA\Post(
 *     path="/novoprocpai",
 *     tags={"Processos"},
 *     summary="Criar ou atualizar um processo pai",
 *     description="Cria um novo processo pai ou atualiza um existente com base no número do processo.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="NPROCPAI",
 *                 type="string",
 *                 description="Número do Processo Pai",
 *                 example="PRC12345"
 *             ),
 *             @OA\Property(
 *                 property="VALORTOTAL",
 *                 type="number",
 *                 format="float",
 *                 description="Valor total do processo pai",
 *                 example=1000.50
 *             ),
 *             @OA\Property(
 *                 property="STATUSPROCESSO",
 *                 type="string",
 *                 description="Status do processo pai",
 *                 example="Em andamento"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Processo Pai criado com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Processo Pai criado com sucesso."
 *             ),
 *             @OA\Property(
 *                 property="processoPai",
 *                 type="object",
 *                 ref="#/components/schemas/ProcessoPai"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Processo Pai atualizado com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Processo Pai atualizado com sucesso."
 *             ),
 *             @OA\Property(
 *                 property="processoPai",
 *                 type="object",
 *                 ref="#/components/schemas/ProcessoPai"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erro de saldo insuficiente.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="R$ Saldo Insuficiente Reduzir Despesa já alocada, Saldo: R$0"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="O campo VALORTOTAL é obrigatório."
 *             ),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 additionalProperties=@OA\Property(
 *                     type="array",
 *                     @OA\Items(type="string")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function novoProcPai(Request $request)
    {
        $validatedData = $request->validate([
            'NPROCPAI' => 'required|string',
            'VALORTOTAL' => 'required|numeric',
            'STATUSPROCESSO' => 'required|string',
        ]);

        $processoPai = ProcessoPai::where('NPROCPAI', $validatedData['NPROCPAI'])->first();

        if ($processoPai) {
            // Atualizar registro existente
            $processoPai->NUMEROAPROVACAO = $processoPai->NUMEROAPROVACAO + 1;
            
            // Valor Total diferente do valor passado na primeira aprovacao ou na aprovacao anterior
            // Verifica se o Valor Total mudou em relação à primeira aprovação ou aprovação anterior
        if ($processoPai->VALORTOTAL !== $validatedData['VALORTOTAL']) {
            // Calcula a diferença absoluta entre os valores
            $diferencaNovoValor = abs($processoPai->VALORTOTAL - $validatedData['VALORTOTAL']);
            
            // Atualiza o VALORTOTAL e ajusta o SALDO conforme necessário
            if ($validatedData['VALORTOTAL'] > $processoPai->VALORTOTAL) {
                // Aumenta o SALDO quando o novo valor é maior
                $processoPai->SALDO += $diferencaNovoValor;
            } else {
                // Reduz o SALDO quando o novo valor é menor, apenas se houver saldo suficiente
                if ($processoPai->SALDO >= $diferencaNovoValor) {
                    $processoPai->SALDO -= $diferencaNovoValor;
                } else {
                    // Opcional: defina uma lógica para tratar saldo insuficiente
                    $processoPai->SALDO = 0; // Ou mantenha o saldo como está, se necessário
                    return response()->json([
                        'message' => 'R$ Saldo Insuficiente Reduzir Despesa já alocada, Saldo: R$'.$processoPai->SALDO
                    ], 202);
                }
            }

            // Atualiza o VALORTOTAL com o novo valor
            $processoPai->VALORTOTAL = $validatedData['VALORTOTAL'];
        }

            
            $processoPai->STATUSPROCESSO = $validatedData['STATUSPROCESSO'];
            $processoPai->save();

            return response()->json([
                'message' => 'Processo Pai atualizado com sucesso.',
                'processoPai' => $processoPai
            ], 200);
        } else {
            // Criar novo registro
            $processoPai = ProcessoPai::create([
                'NPROCPAI' => $validatedData['NPROCPAI'],
                'VALORTOTAL' => $validatedData['VALORTOTAL'],
                'NUMEROAPROVACAO' => 1,
                'STATUSPROCESSO' => $validatedData['STATUSPROCESSO'],
                'SALDO' => $validatedData['VALORTOTAL'],
            ]);

            return response()->json([
                'message' => 'Processo Pai criado com sucesso.',
                'processoPai' => $processoPai
            ], 201);
        }
    }

    public function listarProcEmAndamento()
    {
        $processos = ProcessoPai::where('STATUSPROCESSO', 'Em andamento')->get();

        return response()->json([
            'message' => 'Processos Pai em andamento listados com sucesso.',
            'processos' => $processos
        ], 200);
    }

    /**
     * Inactivate a PROCESSOPAI record by setting STATUSPROCESSO to 'Encerrado'.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inativarProcPai(Request $request)
    {
        $validatedData = $request->validate([
            'NPROCPAI' => 'required|string',
        ]);

        $processoPai = ProcessoPai::where('NPROCPAI', $validatedData['NPROCPAI'])->first();

        if ($processoPai) {
            $processoPai->STATUSPROCESSO = 'Encerrado';
            $processoPai->save();

            return response()->json([
                'message' => 'Processo Pai inativado com sucesso.',
                'processoPai' => $processoPai
            ], 200);
        } else {
            return response()->json([
                'message' => 'Processo Pai não encontrado.',
            ], 202);
        }
    }

    /**
     * Create or update a PROCESSOFILHO record.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function novoProcFilho(Request $request)
    {
        $validatedData = $request->validate([
            'NPROCPAI' => 'required|string',
            'NPROCFILHO' => 'required|string',
            'VALOR' => 'required|numeric|min:0',
        ]);

        $processoPai = ProcessoPai::where('NPROCPAI', $validatedData['NPROCPAI'])->first();

        
        
        if (!$processoPai) {
            return response()->json([
                'message' => 'Processo Pai não encontrado.'
            ], 404);
        }

        $processoFilho = ProcessoFilho::where('NPROCFILHO', $validatedData['NPROCFILHO'])
                                      ->where('NPROCPAI', $validatedData['NPROCPAI'])
                                      ->first();

        
        // if($validatedData['VALOR'] < $processoFilho->VALOR){
        //     $diferencaValor = ($processoFilho->VALOR - $validatedData['VALOR']);
        //     $processoPai->SALDO += $diferencaValor;
        //     $processoPai->save();

            //$processoPai = ProcessoPai::where('NPROCPAI', $validatedData['NPROCPAI'])->first();

        
        // }
        if ($processoFilho) {

            $saldoFilho = $processoFilho->VALOR;

            //se o valor do filho for igual nao faz nada
            if(($validatedData['VALOR'] == $saldoFilho) ){
                return response()->json([
                    'message' => '0'
                ], 202);
            }
                
            //quando o processo filho for zerar o valor para liberar saldo
            if(($validatedData['VALOR'] == 0.0) || ($validatedData['VALOR'] == 0.00)){
                $processoPai->SALDO += $saldoFilho;
                $processoFilho->VALOR = $validatedData['VALOR'];
                $processoPai->save();
                $processoFilho->save();

                return response()->json([
                    'message' => '-1'
                ], 202);
            }

            //verificar se o valor é menor que valor armazenado,
            //Reduz o valor comprometido no filho e libera saldo no pai
            if(($validatedData['VALOR'] < $saldoFilho) ){
                //calcula a diferenca para estornar para o pai antes de atualizar para o novo valor a menor
                $estorno = ($saldoFilho - $validatedData['VALOR']);
                $processoPai->SALDO += $estorno;
                $processoFilho->VALOR = $validatedData['VALOR'];
                $processoPai->save();
                $processoFilho->save();

                return response()->json([
                    'message' => '2'
                ], 201);
            }
            //verificar se o valor é maior que valor armazenado,
            //aumentar o valor comprometido do filho e reduz saldo do pai
            if(($validatedData['VALOR'] > $saldoFilho) ){
                //novo valor informado precisa ser (menor ou igual ao soma do valor do filho + saldo do pai)
                if(($validatedData['VALOR'] <= ($saldoFilho+$processoPai->SALDO))){
                    $diferenca = $validatedData['VALOR'] - $saldoFilho;
                    $processoPai->SALDO -= $diferenca;
                    $processoFilho->VALOR = $validatedData['VALOR'];
                    $processoPai->save();
                    $processoFilho->save();

                    return response()->json([
                        'message' => '3'
                    ], 202);
                }
                
            }

            // Verificar se o novo valor não excede o saldo
            if (($validatedData['VALOR'] + $processoFilho->VALOR) > $processoPai->SALDO) {
                return response()->json([
                    'message' => '-2'
                ], 202);
            }   
            
            // Atualizar registro existente
            $processoFilho->NUMEROAPROVACAO = $processoFilho->NUMEROAPROVACAO + 1;
            $processoFilho->VALOR = $processoFilho->VALOR + $validatedData['VALOR'];
            $processoFilho->save();

            // Atualizar saldo do processo pai
            $processoPai->SALDO -= $validatedData['VALOR'];
            $processoPai->save();

            return response()->json([
                'message' => '4',
                'processoFilho' => $processoFilho
            ], 200);
        } else {

            if (($validatedData['VALOR'] > $processoPai->SALDO )) {
                return response()->json([
                    'message' => '-3' //valor do processo filho nao pode ser maior que o saldo do pai
                ], 202);
            }
            // Criar novo registro
            $processoFilho = ProcessoFilho::create([
                'PROCESSOPAI_ID' => $processoPai->id,
                'NPROCFILHO' => $validatedData['NPROCFILHO'],
                'NPROCPAI' => $validatedData['NPROCPAI'],
                'VALOR' => $validatedData['VALOR'],
                'STATUSPROCESSO' => 'Em andamento',
            ]);

            // Atualizar saldo do processo pai
            $processoPai->SALDO -= $validatedData['VALOR'];
            $processoPai->save();

            return response()->json([
                'message' => '1',
                'processoFilho' => $processoFilho
            ], 201);
        }
   
   
    }

    /**
     * Listar todos os processos filhos em andamento.
     *
     * @OA\Get(
     *     path="/todosFilhosEmAndamento",
     *     tags={"Processos"},
     *     summary="Lista todos os processos filhos com status 'Em andamento'",
     *     description="Retorna uma lista de processos filhos que estão em andamento.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de processos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Processos Pai em andamento listados com sucesso."
     *             ),
     *             @OA\Property(
     *                 property="processos",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ProcessoFilho")
     *             )
     *         )
     *     )
     * )
     */
    public function TodosFilhosEmAndamento(): JsonResponse
    {
        $processos = ProcessoFilho::where('STATUSPROCESSO', 'Em andamento')->get();

        return response()->json([
            'message' => 'Processos Pai em andamento listados com sucesso.',
            'processos' => $processos
        ], 200);
    }







}
