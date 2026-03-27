<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\RolController;

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

/*
|--------------------------------------------------------------------------
| Rutas Modulo Colaboradores
|--------------------------------------------------------------------------
*/

Route::get('/colaboradores',[ColaboradorController::class, 'index']);
Route::post('/colaboradores',[ColaboradorController::class, 'store']);
Route::put('/colaboradores/{colaborador}',[ColaboradorController::class, 'update']);
Route::delete('/colaboradores/{colaborador}',[ColaboradorController::class, 'inactivar']);

/*
|--------------------------------------------------------------------------
| Rutas Modulo Empresas
|--------------------------------------------------------------------------
*/

Route::get('/empresas',[EmpresaController::class,'index']);
Route::post('/empresas',[EmpresaController::class,'store']);
Route::put('/empresas/{empresa}',[EmpresaController::class,'update']);
Route::delete('/empresas/{empresa}',[EmpresaController::class,'inactivar']);

/*
|--------------------------------------------------------------------------
| Rutas Modulo Roles    
|--------------------------------------------------------------------------
*/

Route::get('/roles',[RolController::class,'index']);
Route::post('/roles',[RolController::class,'store']);
Route::put('/roles/{rol}',[RolController::class,'update']);
Route::delete('/roles/{rol}',[RolController::class,'inactivar']);


