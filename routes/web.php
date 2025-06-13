<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el CRUD de clientes (puntos turísticos)
Route::resource('clientes', ClienteController::class);

// Ruta específica para obtener coordenadas (para el mapa)
Route::get('/api/clientes/coordenadas', [ClienteController::class, 'coordenadas']);