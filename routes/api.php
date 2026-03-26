<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Rutas Modulo Usuarios
|--------------------------------------------------------------------------
*/
Route::get('/usuarios',[UsuarioController::class,'index']);
Route::post('/usuarios', [UsuarioController::class , 'store']);
Route::put('/usuarios/{usuario}', [UsuarioController::class,'update']);
Route::delete('/usuarios/{usuario}',[UsuarioController::class, 'destroy']);
