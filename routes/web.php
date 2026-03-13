<?php

use App\Http\Middleware\PermissionMiddleware;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\HistorialDocumentoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\InformacionAdicionalController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ContratoEmpresaController;

/*
|--------------------------------------------------------------------------
| Redirección raíz
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    //Route::get('/dashboard', function () {
        //return view('dashboard.index');
    //})->name('dashboard');

    Route::get('/dashboard', [dashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Documentos
    |--------------------------------------------------------------------------
    */
    Route::get('documentos', [DocumentoController::class,'index'])->name('documentos.index');
    Route::post('documentos', [DocumentoController::class,'store'])->name('documentos.store');

    Route::get('/documentos/{id}/preview', [DocumentoController::class, 'preview'])
        ->name('documentos.preview')
        ->middleware('auth');

    Route::delete('documentos/{id}', [DocumentoController::class,'destroy'])
        ->name('documentos.destroy')
        ->middleware('permission:documentos.eliminar');

    Route::post('documentos/{id}/duplicar', [DocumentoController::class,'duplicar'])
        ->name('documentos.duplicar');

    Route::get('/documentos/create', [DocumentoController::class, 'create'])
    ->name('documentos.create')
    ->middleware('permission:documentos.crear');

    Route::get('/documentos/{documento}/edit', [DocumentoController::class, 'edit'])
    ->name('documentos.edit')
    ->middleware('permission:documentos.editar');

    Route::put('/documentos/{documento}', [DocumentoController::class, 'update'])
        ->name('documentos.update')
        ->middleware('permission:documentos.editar');

    // Route::get('documentos/{documento}/preview', [DocumentoController::class, 'preview'])
    //     ->name('documentos.preview');

    Route::get('documentos/{documento}/preview', [DocumentoController::class, 'preview'])
        ->name('documentos.preview');

    /*
    |--------------------------------------------------------------------------
    | Usuarios
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:usuarios.ver'])->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])
            ->name('usuarios.index');
    });

    Route::middleware(['permission:usuarios.crear'])->group(function () {
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])
            ->name('usuarios.create');

        Route::post('/usuarios', [UsuarioController::class, 'store'])
            ->name('usuarios.store');
    });

    Route::middleware(['permission:usuarios.editar'])->group(function () {
        Route::get('/usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])
            ->name('usuarios.edit');

        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])
            ->name('usuarios.update');
    });

    Route::middleware(['permission:usuarios.eliminar'])->group(function () {
        Route::put('/usuarios/{id}/inactivar', [UsuarioController::class, 'inactivar'])
            ->name('usuarios.inactivar');
        Route::put('/usuarios/{id}/activar', [UsuarioController::class, 'activar'])
            ->name('usuarios.activar');
    });

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:roles.ver'])->group(function () {
        Route::get('/roles', [RolController::class, 'index'])
            ->name('roles.index');
    });

    Route::middleware(['permission:roles.crear'])->group(function () {
        Route::get('/roles/create', [RolController::class, 'create'])
            ->name('roles.create');

        Route::post('/roles', [RolController::class, 'store'])
            ->name('roles.store');
    });

    Route::middleware(['permission:roles.editar'])->group(function () {
        Route::get('/roles/{rol}/edit', [RolController::class, 'edit'])
            ->name('roles.edit');

        Route::put('/roles/{rol}', [RolController::class, 'update'])
            ->name('roles.update');
    });

    Route::middleware(['permission:roles.eliminar'])->group(function () {
        // Route::resource('roles', RolController::class)->except(['destroy']);

        Route::put('roles/{rol}/inactivar', [RolController::class, 'inactivar'])
            ->name('roles.inactivar');

        Route::put('roles/{rol}/activar', [RolController::class, 'activar'])
            ->name('roles.activar');
    });

    

    /*
    |--------------------------------------------------------------------------
    | Categorías
    |--------------------------------------------------------------------------
    */
    Route::get('/categorias', [CategoriaController::class, 'index'])
        ->name('categorias.index')
        ->middleware('permission:categorias.ver');

    Route::get('/categorias/create', [CategoriaController::class, 'create'])
        ->name('categorias.create')
        ->middleware('permission:categorias.crear');

    Route::post('/categorias', [CategoriaController::class, 'store'])
        ->name('categorias.store')
        ->middleware('permission:categorias.crear');

    Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])
        ->name('categorias.edit')
        ->middleware('permission:categorias.editar');

    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])
        ->name('categorias.update')
        ->middleware('permission:categorias.editar');

    Route::put('/categorias/{categoria}/inactivar', [CategoriaController::class, 'destroy'])
        ->name('categorias.destroy')
        ->middleware('permission:categorias.eliminar');

    
    /*
    |--------------------------------------------------------------------------
    | Area
    |--------------------------------------------------------------------------
    */
    Route::get('/areas', [AreaController::class, 'index'])
        ->name('areas.index')
        ->middleware('permission:areas.ver');

    Route::get('/areas/create', [AreaController::class, 'create'])
        ->name('areas.create')
        ->middleware('permission:areas.crear');

    Route::post('/areas', [AreaController::class, 'store'])
        ->name('areas.store')
        ->middleware('permission:areas.crear');

    Route::get('/areas/{area}/edit', [AreaController::class, 'edit'])
        ->name('areas.edit')
        ->middleware('permission:areas.editar');

    Route::put('/areas/{area}', [AreaController::class, 'update'])
        ->name('areas.update')
        ->middleware('permission:areas.editar');

    Route::put('/areas/{area}/inactivar', [AreaController::class, 'destroy'])
        ->name('areas.destroy')
        ->middleware('permission:areas.eliminar');
    
    /*
    |--------------------------------------------------------------------------
    | Historial Documentos
    |--------------------------------------------------------------------------
    */
    Route::get('historial-documentos', [HistorialDocumentoController::class, 'index'])
        ->name('historial_documentos.index')
        ->middleware('permission:historial.ver');

    Route::get('historial-documentos/{historialDocumento}', [HistorialDocumentoController::class, 'show'])
        ->name('historial_documentos.show')
        ->middleware('permission:historial.ver');

    Route::get('/historial-documentos', [HistorialDocumentoController::class, 'index'])
        ->name('historial.index')
        ->middleware('permission:historial.ver');

    /*
    |--------------------------------------------------------------------------
    | Colaboradores 
    |--------------------------------------------------------------------------
    */
    Route::get('/colaboradores', [ColaboradorController::class, 'index'])
    ->name('colaboradores.index')
    ->middleware('permission:colaboradores.ver');

    Route::get('/colaboradore/crear',[ColaboradorController::class, 'create'])
        ->name('colaboradores.create')
        ->middleware('permission:colaboradores.crear');

    Route::post('/colaboradores', [ColaboradorController::class, 'store'])
        ->name('colaboradores.store')
        ->middleware('permission:colaboradores.crear');

    Route::get('/colaboradores/{colaborador}/edit',[ColaboradorController::class, 'edit'])
        ->name('colaboradores.edit')
        ->middleware('permission:colaboradores.editar');

    Route::put('/colaboradores/{colaborador}', [ColaboradorController::class, 'update'])
        ->name('colaboradores.update')
        ->middleware('permission:colaboradores.editar');

    Route::put('colaboradores/{colaborador}/inactivar',[ColaboradorController::class, 'inactivar'])
        ->name('colaboradores.inactivar')
        ->middleware('permission:colaboradores.eliminar');

    Route::put('colaboradores/{colaborador}/activar',[ColaboradorController::class, 'activar'])
        ->name('colaboradores.activar')
        ->middleware('permission:colaboradores.eliminar');

    Route::get('/colaboradores/{colaborador}/detalle', [ColaboradorController::class, 'detalle'])
        ->name('colaboradores.detalle')
        ->middleware('permission:colaboradores.ver');


    Route::get('/colaboradores/{colaborador}/documentos', [ColaboradorController::class, 'documentos'])
    ->name('colaboradores.documentos')
    ->middleware('permission:colaboradores.ver');

    /*
    |--------------------------------------------------------------------------
    | Información Adicional Colaborador
    |--------------------------------------------------------------------------
    */

    // Ver formulario de crear → permiso crear
    Route::get('/colaboradores/{colaborador}/informacion-adicional/crear', [InformacionAdicionalController::class, 'create'])
    ->name('informacion_adicional.create')
    ->middleware('permission:colaboradores.crear');

    // Guardar → permiso crear
    Route::post('/colaboradores/{colaborador}/informacion-adicional', [InformacionAdicionalController::class, 'store'])
        ->name('informacion_adicional.store')
        ->middleware('permission:colaboradores.crear');

    // Ver formulario de editar → permiso editar
    Route::get('/colaboradores/{colaborador}/informacion-adicional/editar', [InformacionAdicionalController::class, 'edit'])
        ->name('informacion_adicional.edit')
        ->middleware('permission:colaboradores.editar');

    // Actualizar → permiso editar
    Route::put('/colaboradores/{colaborador}/informacion-adicional', [InformacionAdicionalController::class, 'update'])
        ->name('informacion_adicional.update')
        ->middleware('permission:colaboradores.editar');


    /*
    |--------------------------------------------------------------------------
    | Empresas 
    |--------------------------------------------------------------------------
    */

    Route::get('/empresas', [EmpresaController::class,'index'])
        ->name('empresas.index')
        ->middleware('permission:empresas.ver');

    Route::get('/empresas/create',[EmpresaController::class,'create'])
        ->name('empresas.create')
        ->middleware('permission:empresas.crear');
    
    Route::post('/empresas', [EmpresaController::class,'store'])
        ->name('empresas.store')
        ->middleware('permission:empresas.crear');

    Route::get('/empresas/{empresa}/edit', [EmpresaController::class,'edit'])
        ->name('empresas.edit')
        ->middleware('permission:empresas.editar');

    Route::put('/empresas/{empresa}', [EmpresaController::class,'update'])
        ->name('empresas.update')
        ->middleware('permission:empresas.editar');

    Route::put('empresas/{empresa}/inactivar',[EmpresaController::class, 'inactivar'])
        ->name('empresas.inactivar')
        ->middleware('permission:empresas.eliminar');

    Route::put('empresas/{empresa}/activar', [EmpresaController::class, 'activar'])
        ->name('empresas.activar')
        ->middleware('permission:empresas.eliminar');

    Route::get('/empresas/{empresa}/documentos', [EmpresaController::class, 'documentos'])
        ->name('empresas.documentos')
        ->middleware('permission:empresas.ver');

    Route::get('/empresas/{empresa}/detalle', [EmpresaController::class, 'detalle'])
        ->name('empresas.detalle')
        ->middleware('permission:empresas.ver');
    

    /*
    |--------------------------------------------------------------------------
    | Contratos
    |--------------------------------------------------------------------------
    */

    Route::get('colaboradores/{colaborador}/contrato/modal-inactivar', [ContratoController::class, 'inactivarModal'])
        ->name('contrato.modal-inactivar')
        ->Middleware('permission:colaboradores.eliminar');

    Route::put('colaboradores/{colaborador}/contrato/inactivar', [ContratoController::class, 'inactivar'])
        ->name('contrato.inactivar')
        ->Middleware('permission:colaboradores.eliminar');

    Route::get('contratos/{contrato}/info', [ContratoController::class, 'verInfo'])
        ->name('contrato.verInfo')
        ->Middleware('permission:colaboradores.ver');

    Route::get('contratos/{contrato}/documentos',[ContratoController::class , 'documentos'])
        ->name('contratos.documentos')
        ->Middleware('permission:colaboradores.ver');

    Route::get('contratos/{contrato}/documento/crear', [DocumentoController::class, 'createParaContrato'])
    ->name('contratos.documentos.create')
    ->Middleware('permission:colaboradores.crear');

    /*
    |--------------------------------------------------------------------------
    | Contratos Empresas
    |--------------------------------------------------------------------------
    */

    Route::get('empresas/{empresa}/contrato/crear', [ContratoEmpresaController::class, 'create'])
        ->name('contratosEmpresa.create')
        ->middleware('permission:empresas.crear');

    Route::post('empresas/{empresa}/contrato', [ContratoEmpresaController::class, 'store'])
        ->name('contratosEmpresa.store')
        ->middleware('permission:empresas.crear');

    Route::get('empresas/{empresa}/contrato/editar', [ContratoEmpresaController::class, 'edit'])
        ->name('contratosEmpresa.edit')
        ->middleware('permission:empresas.editar');
        
    Route::put('empresas/{empresa}/contrato/actualizar', [ContratoEmpresaController::class, 'update'])
        ->name('contratosEmpresa.update')
        ->middleware('permission:empresas.editar');

    Route::get('empresas/{empresa}/contrato/modal-inactivar', [ContratoEmpresaController::class, 'inactivarModal'])
        ->name('contratosEmpresa.modal-inactivar')
        ->middleware('permission:empresas.eliminar');

    Route::put('empresas/{empresa}/contrato/inactivar', [ContratoEmpresaController::class, 'inactivar'])
        ->name('contratosEmpresa.inactivar')
        ->middleware('permission:empresas.eliminar');

    Route::get('contrato-empresa/{contratoEmpresa}/info', [ContratoEmpresaController::class, 'verInfo'])
        ->name('contratosEmpresa.verInfo')
        ->middleware('permission:empresas.ver');
});