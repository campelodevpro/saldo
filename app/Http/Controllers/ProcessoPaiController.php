<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessoPai;
use App\Models\ProcessoFilho;

class ProcessoPaiController extends Controller
{
    //
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
            // $processoPai->VALORTOTAL = $processoPai->VALORTOTAL + $validatedData['VALORTOTAL'];
            $processoPai->VALORTOTAL = $validatedData['VALORTOTAL'];
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
            ], 404);
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

        if ($validatedData['VALOR'] > $processoPai->SALDO) {
            return response()->json([
                'message' => 'O valor do processo filho não pode ser maior que o saldo do processo pai.'
            ], 400);
        }
        

        $processoFilho = ProcessoFilho::where('NPROCFILHO', $validatedData['NPROCFILHO'])->first(); 
        // $processoFilho = ProcessoFilho::where('NPROCFILHO', $validatedData['NPROCFILHO'])->first() ?? false;


        if ($processoFilho) {
            // Atualizar registro existente
            $processoFilho->NUMEROAPROVACAO = $processoFilho->NUMEROAPROVACAO + 1;
            $processoFilho->VALOR = $processoFilho->VALOR + $validatedData['VALOR'];
            $processoFilho->save();

            // Atualizar saldo do processo pai
            $processoPai->SALDO -= $validatedData['VALOR'];
            $processoPai->save();

            return response()->json([
                'message' => 'Processo Filho atualizado com sucesso.',
                'processoFilho' => $processoFilho
            ], 200);
        } else {
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
                'message' => 'Processo Filho criado com sucesso.',
                'processoFilho' => $processoFilho
            ], 201);
        }
   
   
    }





}
