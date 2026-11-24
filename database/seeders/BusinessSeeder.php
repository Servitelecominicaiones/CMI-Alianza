<?php

namespace Database\Seeders;

use App\Helpers\CryptoHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos iniciales: roles, permisos, catálogos y usuario administrador.
 * Idempotente: usa insertOrIgnore / updateOrInsert, así que puede re-ejecutarse.
 */
class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Roles ----
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'nombre' => 'Administrador',      'descripcion' => 'Acceso total',          'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Gestor Documental',  'descripcion' => 'Gestiona documentos',   'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Viewer Documental',  'descripcion' => 'Solo consulta',         'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ---- Catálogo de permisos (lista fija del sistema; no hay tabla `permisos`,
        //      el código la maneja como arreglo en RolController) ----
        $permisos = [
            'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
            'roles.ver', 'roles.crear', 'roles.editar', 'roles.eliminar',
            'documentos.ver', 'documentos.crear', 'documentos.editar', 'documentos.eliminar', 'documentos.mover',
            'configuracion.editar',
            'areas.ver', 'areas.crear', 'areas.editar', 'areas.eliminar',
            'historial.ver',
            'categorias.ver', 'categorias.crear', 'categorias.editar', 'categorias.eliminar',
            'colaboradores.ver', 'colaboradores.crear', 'colaboradores.editar', 'colaboradores.eliminar',
            'empresas.ver', 'empresas.crear', 'empresas.editar', 'empresas.eliminar',
        ];

        // ---- Permisos por rol (tabla permisos_rol) ----
        $asignar = function (int $rolId, array $lista) {
            foreach ($lista as $permiso) {
                DB::table('permisos_rol')->updateOrInsert(
                    ['rol_id' => $rolId, 'permiso' => $permiso],
                    ['rol_id' => $rolId, 'permiso' => $permiso]
                );
            }
        };
        // Administrador: todos
        $asignar(1, $permisos);
        // Gestor Documental: documentos, colaboradores, empresas e historial
        $asignar(2, array_values(array_filter($permisos, fn ($p) =>
            str_starts_with($p, 'documentos.') || str_starts_with($p, 'colaboradores.')
            || str_starts_with($p, 'empresas.') || $p === 'historial.ver'
        )));
        // Viewer: solo *.ver
        $asignar(3, array_values(array_filter($permisos, fn ($p) => str_ends_with($p, '.ver'))));

        // ---- Configuración ----
        DB::table('configuracion')->updateOrInsert(
            ['clave' => 'ruta_bodega'],
            ['valor' => 'bodega_documental']
        );

        // ---- Catálogos ----
        DB::table('identificacion')->insertOrIgnore([
            ['tipo_identificacion' => 'Cédula de ciudadanía',          'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_identificacion' => 'Cédula de extranjería',         'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_identificacion' => 'Pasaporte',                     'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_identificacion' => 'Permiso por protección temporal','estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('areas')->insertOrIgnore([
            ['nombre' => 'Desarrollo', 'estado' => 1], ['nombre' => 'Infraestructura', 'estado' => 1],
            ['nombre' => 'Compras', 'estado' => 1], ['nombre' => 'Talento Humano', 'estado' => 1],
        ]);
        DB::table('categorias')->insertOrIgnore([
            ['nombre' => 'Contratos', 'estado' => 1], ['nombre' => 'Certificacion Laboral', 'estado' => 1],
            ['nombre' => 'Hoja de vida', 'estado' => 1], ['nombre' => 'Anexos', 'estado' => 1],
        ]);

        // ---- Usuario administrador ----
        // La contraseña se cifra con CryptoHelper (AES reversible) porque así la
        // valida el login. CAMBIA esta contraseña tras el primer ingreso.
        $email = env('SEED_ADMIN_EMAIL', 'admin@cmi.local');
        $pass  = env('SEED_ADMIN_PASSWORD', 'Admin123*');
        DB::table('usuarios')->updateOrInsert(
            ['email' => $email],
            [
                'nombre'              => 'Administrador',
                'password'            => CryptoHelper::Enc('enc', $pass),
                'rol_id'              => 1,
                'estado'              => 1,
                'password_changed_at' => now(),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]
        );
    }
}
