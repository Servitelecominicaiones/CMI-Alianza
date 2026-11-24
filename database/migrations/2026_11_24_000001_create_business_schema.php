<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Esquema de negocio de CMI Alianza.
 *
 * Basado en el DUMP OFICIAL de la base `cmi_alianza` (documento "Bases de datos",
 * MariaDB). Se mantienen tipos (int(11)), llaves foráneas y el ENUM de owner_type
 * tal cual el dump.
 *
 * Se AÑADEN 5 columnas que el código actual (rama Dev_Serv) escribe y que el dump
 * —anterior a esos cambios— no incluía:
 *   - usuarios.password_changed_at            (módulo de cambio de contraseña)
 *   - historial_documentos.nombre_documento   (historial)
 *   - informacion_adicional_colaborador.cajaUbica / carpetaIn / carpetaFin
 *
 * Las tablas de Laravel (users, sessions, cache, jobs, personal_access_tokens)
 * las crean las migraciones 0001_* y la de tokens.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `permisos_rol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_id` int(11) NOT NULL,
  `permiso` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `permisos_rol_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `identificacion` (
  `id_identificacion` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_identificacion` varchar(50) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_identificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `nit` varchar(50) NOT NULL,
  `nombre_empresa` varchar(150) NOT NULL,
  `actividad` varchar(150) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `barrio` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ciudad` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  UNIQUE KEY `nit` (`nit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `colaborador` (
  `id_colaborador` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_identificacion` int(11) NOT NULL,
  `numero_identificacion` varchar(50) NOT NULL,
  `primer_nombre` varchar(100) NOT NULL,
  `segundo_nombre` varchar(100) DEFAULT NULL,
  `primer_apellido` varchar(100) NOT NULL,
  `segundo_apellido` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `barrio` varchar(100) DEFAULT NULL,
  `estado_civil` varchar(50) DEFAULT NULL,
  `telefono_residencial` varchar(20) DEFAULT NULL,
  `telefono_celular` varchar(20) DEFAULT NULL,
  `estudios` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_colaborador`),
  KEY `fk_colaborador_identificacion` (`id_tipo_identificacion`),
  CONSTRAINT `fk_colaborador_identificacion` FOREIGN KEY (`id_tipo_identificacion`) REFERENCES `identificacion` (`id_identificacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `informacion_adicional_colaborador` (
  `id_informacion_adicional` int(11) NOT NULL AUTO_INCREMENT,
  `cargo` varchar(100) DEFAULT NULL,
  `eps` varchar(100) DEFAULT NULL,
  `fondo` varchar(100) DEFAULT NULL,
  `caja_compensacion` varchar(100) DEFAULT NULL,
  `salario_basico` decimal(12,2) DEFAULT NULL,
  `sub_transporte` decimal(12,2) DEFAULT NULL,
  `bono_servicio` decimal(12,2) DEFAULT NULL,
  `factor_prestacional` decimal(12,2) DEFAULT NULL,
  `bono_salud_y_vivienda` decimal(12,2) DEFAULT NULL,
  `medios_transporte` decimal(12,2) DEFAULT NULL,
  `prima_riesgo` decimal(12,2) DEFAULT NULL,
  `auxilio_formacion` decimal(12,2) DEFAULT NULL,
  `comision_fija` decimal(12,2) DEFAULT NULL,
  `productividad_fija` decimal(12,2) DEFAULT NULL,
  `tiempo_extra_fijo` decimal(12,2) DEFAULT NULL,
  `bono_mercado` decimal(12,2) DEFAULT NULL,
  `auxilio_equipo` decimal(12,2) DEFAULT NULL,
  `recargo_nocturno` decimal(12,2) DEFAULT NULL,
  `trans_adicional` decimal(12,2) DEFAULT NULL,
  `fecha_inicial` date DEFAULT NULL,
  `fecha_terminacion` date DEFAULT NULL,
  `cajaUbica` varchar(100) DEFAULT NULL,
  `carpetaIn` varchar(100) DEFAULT NULL,
  `carpetaFin` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_informacion_adicional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `informacion_adicional_empresa` (
  `id_informacion_adicional_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `inicio_contrato` date DEFAULT NULL,
  `finalizacion_contrato` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_informacion_adicional_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `contratos` (
  `id_contrato` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_colaborador` int(11) NOT NULL,
  `id_informacion_adicional` int(11) NOT NULL,
  `inicio_contrato` date DEFAULT NULL,
  `finalizacion_contrato` date DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `motivo_inactivacion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_contrato`),
  KEY `fk_contrato_empresa` (`id_empresa`),
  KEY `fk_contrato_colaborador` (`id_colaborador`),
  KEY `fk_contrato_info` (`id_informacion_adicional`),
  CONSTRAINT `fk_contrato_colaborador` FOREIGN KEY (`id_colaborador`) REFERENCES `colaborador` (`id_colaborador`),
  CONSTRAINT `fk_contrato_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`),
  CONSTRAINT `fk_contrato_info` FOREIGN KEY (`id_informacion_adicional`) REFERENCES `informacion_adicional_colaborador` (`id_informacion_adicional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `contrato_empresa` (
  `id_contrato_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_informacion_adicional_empresa` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `motivo_inactivacion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_contrato_empresa`),
  KEY `fk_contratoEmpresa_empresa` (`id_empresa`),
  KEY `fk_contratoEmpresa_infoAdicionalEmpresa` (`id_informacion_adicional_empresa`),
  CONSTRAINT `fk_contratoEmpresa_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id_empresa`),
  CONSTRAINT `fk_contratoEmpresa_infoAdicionalEmpresa` FOREIGN KEY (`id_informacion_adicional_empresa`) REFERENCES `informacion_adicional_empresa` (`id_informacion_adicional_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_original` varchar(255) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_completa` varchar(500) NOT NULL,
  `area_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `tamanio` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `usuario_carga_id` int(11) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `owner_type` enum('empresa','colaborador','contrato','contratoEmpresa') DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `area_id` (`area_id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `usuario_carga_id` (`usuario_carga_id`),
  CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `documentos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `documentos_ibfk_3` FOREIGN KEY (`usuario_carga_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `historial_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_id` int(11) NOT NULL,
  `nombre_documento` varchar(255) DEFAULT NULL,
  `ruta_anterior` varchar(500) DEFAULT NULL,
  `ruta_nueva` varchar(500) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `accion` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `documento_id` (`documento_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `historial_documentos_ibfk_1` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`),
  CONSTRAINT `historial_documentos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL);
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ([
            'historial_documentos', 'documentos', 'contrato_empresa', 'contratos',
            'informacion_adicional_empresa', 'informacion_adicional_colaborador',
            'colaborador', 'empresa', 'configuracion', 'categorias', 'areas',
            'identificacion', 'permisos_rol', 'usuarios', 'roles',
        ] as $tabla) {
            Schema::dropIfExists($tabla);
        }
        Schema::enableForeignKeyConstraints();
    }
};
