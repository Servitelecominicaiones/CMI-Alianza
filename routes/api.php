<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CategoriaController;

/*
|--------------------------------------------------------------------------
| Rutas Modulo Usuarios
|--------------------------------------------------------------------------
*/

Route::get('/usuarios',[UsuarioController::class,'index']);
Route::post('/usuarios', [UsuarioController::class , 'store']);
Route::put('/usuarios/{usuario}', [UsuarioController::class,'update']);
Route::delete('/usuarios/{usuario}',[UsuarioController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Rutas Modulo Areas
|--------------------------------------------------------------------------
*/

Route::get('/areas',[AreaController::class, 'index']);
Route::post('/areas',[AreaController::class, 'store']);
Route::put('/areas/{area}',[AreaController::class, 'update']);
Route::delete('/areas/{area}', [AreaController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Rutas Modulo Categorias
|--------------------------------------------------------------------------
*/

Route::get('/categorias',[CategoriaController::class,'index']);
Route::post('/categorias',[CategoriaController::class, 'store']);
Route::put('/categorias/{categoria}',[CategoriaController::class, 'update']);
Route::delete('/categorias/{categoria}',[CategoriaController::class, 'destroy']);

