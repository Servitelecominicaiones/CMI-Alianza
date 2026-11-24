<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Esquema de negocio de CMI Alianza.
 *
 * Reconstruido a partir de los modelos y controladores (rama Dev_Serv), ya que
 * el proyecto no tenía migraciones para estas tablas. Si el equipo dispone del
 * dump oficial de producción, ese es la fuente de verdad y reemplaza a esto.
 *
 * Las tablas de Laravel (users, sessions, cache, jobs, personal_access_tokens)
 * las crean las migraciones 0001_* y la de tokens; aquí solo van las de negocio.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE roles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE permisos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE permisos_rol (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  rol_id BIGINT UNSIGNED NOT NULL,
  permiso VARCHAR(100) NOT NULL,
  INDEX (rol_id),
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usuarios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol_id BIGINT UNSIGNED NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  password_changed_at TIMESTAMP NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  FOREIGN KEY (rol_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE areas (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categorias (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE configuracion (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(100) NOT NULL UNIQUE,
  valor VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE identificacion (
  id_identificacion BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo_identificacion VARCHAR(100) NOT NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE empresa (
  id_empresa BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nit VARCHAR(50) NOT NULL,
  nombre_empresa VARCHAR(200) NOT NULL,
  actividad VARCHAR(255) NULL,
  ciudad VARCHAR(100) NULL,
  direccion VARCHAR(255) NULL,
  barrio VARCHAR(100) NULL,
  telefono VARCHAR(50) NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE colaborador (
  id_colaborador BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_empresa BIGINT UNSIGNED NULL,
  id_tipo_identificacion BIGINT UNSIGNED NULL,
  numero_identificacion VARCHAR(50) NOT NULL,
  primer_nombre VARCHAR(100) NOT NULL,
  segundo_nombre VARCHAR(100) NULL,
  primer_apellido VARCHAR(100) NOT NULL,
  segundo_apellido VARCHAR(100) NULL,
  fecha_nacimiento DATE NULL,
  genero VARCHAR(30) NULL,
  direccion VARCHAR(255) NULL,
  ciudad VARCHAR(100) NULL,
  barrio VARCHAR(100) NULL,
  estado_civil VARCHAR(50) NULL,
  telefono_residencial VARCHAR(50) NULL,
  telefono_celular VARCHAR(50) NULL,
  estudios VARCHAR(255) NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa),
  FOREIGN KEY (id_tipo_identificacion) REFERENCES identificacion(id_identificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE informacion_adicional_colaborador (
  id_informacion_adicional BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_colaborador BIGINT UNSIGNED NULL,
  cargo VARCHAR(150) NULL,
  eps VARCHAR(150) NULL,
  fondo VARCHAR(150) NULL,
  caja_compensacion VARCHAR(150) NULL,
  salario_basico DECIMAL(15,2) NULL,
  sub_transporte DECIMAL(15,2) NULL,
  bono_servicio DECIMAL(15,2) NULL,
  factor_prestacional DECIMAL(15,2) NULL,
  bono_salud_y_vivienda DECIMAL(15,2) NULL,
  medios_transporte DECIMAL(15,2) NULL,
  prima_riesgo DECIMAL(15,2) NULL,
  auxilio_formacion DECIMAL(15,2) NULL,
  comision_fija DECIMAL(15,2) NULL,
  productividad_fija DECIMAL(15,2) NULL,
  tiempo_extra_fijo DECIMAL(15,2) NULL,
  bono_mercado DECIMAL(15,2) NULL,
  auxilio_equipo DECIMAL(15,2) NULL,
  recargo_nocturno DECIMAL(15,2) NULL,
  trans_adicional DECIMAL(15,2) NULL,
  fecha_inicial DATE NULL,
  fecha_terminacion DATE NULL,
  cajaUbica VARCHAR(100) NULL,
  carpetaIn VARCHAR(100) NULL,
  carpetaFin VARCHAR(100) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contratos (
  id_contrato BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_empresa BIGINT UNSIGNED NULL,
  id_colaborador BIGINT UNSIGNED NOT NULL,
  id_informacion_adicional BIGINT UNSIGNED NULL,
  inicio_contrato DATE NULL,
  finalizacion_contrato DATE NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  motivo_inactivacion VARCHAR(500) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa),
  FOREIGN KEY (id_colaborador) REFERENCES colaborador(id_colaborador),
  FOREIGN KEY (id_informacion_adicional) REFERENCES informacion_adicional_colaborador(id_informacion_adicional)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE informacion_adicional_empresa (
  id_informacion_adicional_empresa BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  inicio_contrato DATE NULL,
  finalizacion_contrato DATE NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE contrato_empresa (
  id_contrato_empresa BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_empresa BIGINT UNSIGNED NOT NULL,
  id_informacion_adicional_empresa BIGINT UNSIGNED NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  motivo_inactivacion VARCHAR(500) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa),
  FOREIGN KEY (id_informacion_adicional_empresa) REFERENCES informacion_adicional_empresa(id_informacion_adicional_empresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE documentos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_original VARCHAR(255) NOT NULL,
  nombre_archivo VARCHAR(255) NOT NULL,
  ruta_completa VARCHAR(500) NOT NULL,
  area_id BIGINT UNSIGNED NULL,
  categoria_id BIGINT UNSIGNED NULL,
  anio SMALLINT NULL,
  extension VARCHAR(20) NULL,
  tamanio BIGINT NULL,
  descripcion TEXT NULL,
  usuario_carga_id BIGINT UNSIGNED NULL,
  estado TINYINT NOT NULL DEFAULT 1,
  owner_id BIGINT UNSIGNED NULL,
  owner_type VARCHAR(50) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  INDEX (owner_type, owner_id),
  FOREIGN KEY (area_id) REFERENCES areas(id),
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE historial_documentos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  documento_id BIGINT UNSIGNED NULL,
  nombre_documento VARCHAR(255) NULL,
  usuario_id BIGINT UNSIGNED NULL,
  accion VARCHAR(50) NOT NULL,
  ruta_anterior VARCHAR(500) NULL,
  ruta_nueva VARCHAR(500) NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (documento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL);
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ([
            'historial_documentos', 'documentos', 'contrato_empresa',
            'informacion_adicional_empresa', 'contratos',
            'informacion_adicional_colaborador', 'colaborador', 'empresa',
            'identificacion', 'configuracion', 'categorias', 'areas',
            'usuarios', 'permisos_rol', 'permisos', 'roles',
        ] as $tabla) {
            Schema::dropIfExists($tabla);
        }
        Schema::enableForeignKeyConstraints();
    }
};
