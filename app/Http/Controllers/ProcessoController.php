<?php

namespace App\Http\Controllers;

use App\Models\ProcessoPai;
use App\Models\ProcessoFilho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessoController extends Controller
{
    public function criarPai(Request $request)
    {
        $pai = ProcessoPai::create($request->only(['nome', 'saldo', 'valor_total']));
        return response()->json($pai, 201);
    }

    // Criar um processo filho e debitar do saldo
    public function criarFilho(Request $request, $processoPaiId)
    {
        DB::beginTransaction();

        try {
            $pai = ProcessoPai::findOrFail($processoPaiId);

            if ($pai->saldo < $request->valor) {
                return response()->json(['error' => 'Saldo insuficiente.'], 400);
            }

            // Atualizar saldo do pai
            $pai->decrement('saldo', $request->valor);

            // Criar o processo filho
            $filho = ProcessoFilho::create([
                'processo_pai_id' => $pai->id,
                'valor' => $request->valor,
            ]);

            DB::commit();

            return response()->json($filho, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Listar todos os processos pai
    public function listarPais()
    {
        return response()->json(ProcessoPai::with('filhos')->get());
    }

    public function consultarSaldo($id)
    {
        try {
            // Obter o processo pai pelo ID
            $pai = ProcessoPai::findOrFail($id);

            // Retornar o saldo atual
            return response()->json([
                'processo_pai_id' => $pai->id,
                'nome' => $pai->nome,
                'saldo' => $pai->saldo,
                'valor_total' => $pai->valor_total
            ], 200);
        } catch (\Exception $e) {
            // Retornar erro caso o processo pai não seja encontrado
            return response()->json(['error' => 'Processo Pai não encontrado.'], 404);
        }
    }

    public function creditarValor(Request $request, $id)
    {
        try {
            // Validação da entrada
            $request->validate([
                'valor' => 'required|numeric|min:0.01', // O valor deve ser positivo
            ]);

            // Obter o processo pai pelo ID
            $pai = ProcessoPai::findOrFail($id);

            // Incrementar o saldo com o valor informado
            $pai->increment('saldo', $request->valor);

            return response()->json([
                'message' => 'Valor creditado com sucesso.',
                'processo_pai_id' => $pai->id,
                'saldo_atual' => $pai->saldo,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retornar erros de validação
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Tratar outros erros
            return response()->json(['error' => 'Erro ao creditar valor.'], 500);
        }
    }

}
