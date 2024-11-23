<?php

use App\Http\Controllers\ProcessoPaiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('api')->group(function () {
    Route::get('/ping', function () {
        return response()->json(['message' => 'API funcionando!']);
    });
    // Route::post('/novoprocessopai', [ProcessoController::class, 'criarPai']);
    // Route::post('/novofilho/{NumeroProcessoPai}', [ProcessoController::class, 'criarFilho']);
    // Route::get('/listartodosprocessospais', [ProcessoController::class, 'listarPais']);
    
    // Route::get('/processo-pai/{id}/saldo', [ProcessoController::class, 'consultarSaldo']);
    // Route::post('/processo-pai/{id}/creditar', [ProcessoController::class, 'creditarValor']);

    Route::get('/listarProcEmAndamento', [ProcessoPaiController::class, 'listarProcEmAndamento']);

    Route::post('/processospai/inativar', [ProcessoPaiController::class, 'inativarProcPai']);   
    Route::post('/novoprocpai', [ProcessoPaiController::class, 'novoProcPai']);
    Route::post('/novoprocfilho', [ProcessoPaiController::class, 'novoProcFilho']);
    // Route::post('/novoprocfilho', function () {
    //     return response()->json(['message' => 'API funcionando!']);

    // });
});