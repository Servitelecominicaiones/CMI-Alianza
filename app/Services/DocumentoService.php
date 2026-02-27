<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentoService
{
    private $rutaBodega;

    public function __construct()
    {
        $config = DB::table('configuracion')
            ->where('clave', 'ruta_bodega')
            ->first();
        
        $this->rutaBodega = $config 
            ? public_path($config->valor) 
            : public_path('bodega_documental');
    }

    public function construirRuta($area, $categoria, $anio)
    {
        return "{$area}/{$categoria}/{$anio}";
    }

    public function guardarDocumento($archivo, $areaId, $categoriaId, $anio, $usuarioId, $descripcion = null)
    {
        $area = DB::table('areas')->find($areaId);
        $categoria = DB::table('categorias')->find($categoriaId);

        if (!$area || !$categoria) {
            throw new \Exception('Área o categoría no encontrada');
        }

        $rutaRelativa = $this->construirRuta($area->nombre, $categoria->nombre, $anio);
        $rutaCompleta = $this->rutaBodega . '/' . $rutaRelativa;

        if (!file_exists($rutaCompleta)) {
            mkdir($rutaCompleta, 0755, true);
        }

        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = Str::slug(pathinfo($nombreOriginal, PATHINFO_FILENAME)) 
            . '_' . time() . '.' . $extension;

        $archivo->move($rutaCompleta, $nombreArchivo);

        $documentoId = DB::table('documentos')->insertGetId([
            'nombre_original' => $nombreOriginal,
            'nombre_archivo' => $nombreArchivo,
            'ruta_completa' => $rutaRelativa . '/' . $nombreArchivo,
            'area_id' => $areaId,
            'categoria_id' => $categoriaId,
            'anio' => $anio,
            'extension' => $extension,
            'tamanio' => $archivo->getSize(),
            'descripcion' => $descripcion,
            'usuario_carga_id' => $usuarioId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->registrarHistorial($documentoId, null, $rutaRelativa . '/' . $nombreArchivo, 
            $usuarioId, 'Creación');

        return $documentoId;
    }

    public function moverDocumento($documentoId, $nuevaAreaId, $nuevaCategoriaId, $nuevoAnio, $usuarioId)
    {
        $documento = DB::table('documentos')->find($documentoId);
        
        if (!$documento) {
            throw new \Exception('Documento no encontrado');
        }

        $area = DB::table('areas')->find($nuevaAreaId);
        $categoria = DB::table('categorias')->find($nuevaCategoriaId);

        if (!$area || !$categoria) {
            throw new \Exception('Área o categoría no encontrada');
        }

        $nuevaRutaRelativa = $this->construirRuta($area->nombre, $categoria->nombre, $nuevoAnio);
        $nuevaRutaCompleta = $this->rutaBodega . '/' . $nuevaRutaRelativa;

        if (!file_exists($nuevaRutaCompleta)) {
            mkdir($nuevaRutaCompleta, 0755, true);
        }

        $rutaActualCompleta = $this->rutaBodega . '/' . $documento->ruta_completa;
        $nuevaRutaArchivoCompleta = $nuevaRutaCompleta . '/' . $documento->nombre_archivo;

        if (file_exists($rutaActualCompleta)) {
            rename($rutaActualCompleta, $nuevaRutaArchivoCompleta);
        }

        $nuevaRutaBD = $nuevaRutaRelativa . '/' . $documento->nombre_archivo;

        DB::table('documentos')
            ->where('id', $documentoId)
            ->update([
                'ruta_completa' => $nuevaRutaBD,
                'area_id' => $nuevaAreaId,
                'categoria_id' => $nuevaCategoriaId,
                'anio' => $nuevoAnio,
                'updated_at' => now()
            ]);

        $this->registrarHistorial($documentoId, $documento->ruta_completa, 
            $nuevaRutaBD, $usuarioId, 'Movimiento');

        return true;
    }

    public function eliminarDocumento($documentoId, $usuarioId)
    {
        $documento = DB::table('documentos')->find($documentoId);
        
        if (!$documento) {
            throw new \Exception('Documento no encontrado');
        }

        $rutaCompleta = $this->rutaBodega . '/' . $documento->ruta_completa;

        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }

        $this->registrarHistorial($documentoId, $documento->ruta_completa, 
            null, $usuarioId, 'Eliminación');

        DB::table('documentos')->where('id', $documentoId)->delete();

        return true;
    }

    public function descargarDocumento($documentoId)
    {
        $documento = DB::table('documentos')->find($documentoId);
        
        if (!$documento) {
            throw new \Exception('Documento no encontrado');
        }

        $rutaCompleta = $this->rutaBodega . '/' . $documento->ruta_completa;

        if (!file_exists($rutaCompleta)) {
            throw new \Exception('Archivo no encontrado en el sistema');
        }

        return [
            'path' => $rutaCompleta,
            'nombre' => $documento->nombre_original
        ];
    }

    private function registrarHistorial($documentoId, $rutaAnterior, $rutaNueva, $usuarioId, $accion)
    {
        DB::table('historial_documentos')->insert([
            'documento_id' => $documentoId,
            'ruta_anterior' => $rutaAnterior,
            'ruta_nueva' => $rutaNueva,
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'created_at' => now()
        ]);
    }
}