<?php 
    
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Documento;
use App\Models\Colaborador;
use App\Models\Empresa;


    class DashboardController extends Controller{

        public function index()
        {
            $totalUsuarios = Usuario::count();
            $totalDocumentos = Documento::count();
            $totalRoles = Rol::count();
            $totalColaboradores = Colaborador::count();
            $totalEmpresas = Empresa::count();

            return view('dashboard.index',
                compact(
                    'totalUsuarios',
                    'totalDocumentos',
                    'totalRoles',
                    'totalColaboradores',
                    'totalEmpresas'
                ));
        }
    }
