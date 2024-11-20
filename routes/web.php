<?php

use App\Http\Controllers\ProcessoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});



// Route::post('/processo-pai', [ProcessoController::class, 'criarPai']);
// Route::post('/processo-pai/{id}/filho', [ProcessoController::class, 'criarFilho']);
// Route::get('/processo-pai', [ProcessoController::class, 'listarPais']);

