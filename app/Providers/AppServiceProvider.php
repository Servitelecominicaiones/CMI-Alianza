<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Mapeo de relaciones polimorficas:
        Relation::enforceMorphMap([
            'empresa' => 'App\Models\Empresa',
            'colaborador' => 'App\Models\Colaborador',
            'contrato' => 'App\Models\Contrato',
            'contratoEmpresa' => 'App\Models\ContratoEmpresa',
            'user' => 'App\Models\User'
        ]);
    }
}
