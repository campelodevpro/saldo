<?php

use App\Http\Controllers\ProcessoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('api')->group(function () {
    Route::get('/ping', function () {
        return response()->json(['message' => 'API funcionando!']);
    });
    Route::post('/processo-pai', [ProcessoController::class, 'criarPai']);
    Route::post('/processo-pai/{id}/filho', [ProcessoController::class, 'criarFilho']);
    Route::get('/processo-pai', [ProcessoController::class, 'listarPais']);
    Route::get('/processo-pai/{id}/saldo', [ProcessoController::class, 'consultarSaldo']);

});


