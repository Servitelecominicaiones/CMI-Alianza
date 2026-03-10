<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\InformacionAdicionalColaborador;
use Illuminate\Support\Facades\DB;

class ContratoService
{
    public function crearContratoConInformacion($colaborador, array $data)
    {
        return DB::transaction(function () use ($colaborador, $data) {

            $idEmpresa = $data['id_empresa'];
            unset($data['id_empresa']);

            // Crear información adicional
            $informacion = InformacionAdicionalColaborador::create($data);

            // Crear contrato
            $contrato = Contrato::create([
                'id_empresa' => $idEmpresa,
                'id_colaborador' => $colaborador->id_colaborador,
                'id_informacion_adicional' => $informacion->id_informacion_adicional,
                'estado' => 1
            ]);

            return $contrato;
        });
    }
}