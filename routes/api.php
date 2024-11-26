<?php

use App\Http\Controllers\ProcessoPaiController;
use Illuminate\Support\Facades\Route;



Route::middleware('api')->group(function () {
    Route::get('/ping', function () {
        return response()->json(['message' => 'API funcionando!']);
    });
    

    Route::get('/listarProcEmAndamento', [ProcessoPaiController::class, 'listarProcEmAndamento']);
    Route::get('/todosFilhosEmAndamento', [ProcessoPaiController::class, 'TodosFilhosEmAndamento']);

    Route::post('/processospai/inativar', [ProcessoPaiController::class, 'inativarProcPai']);   
    Route::post('/novoprocpai', [ProcessoPaiController::class, 'novoProcPai']);
    Route::post('/novoprocfilho', [ProcessoPaiController::class, 'novoProcFilho']);
    
});