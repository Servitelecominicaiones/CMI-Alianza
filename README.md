# CMI Alianza — Sistema de Gestión Documental

Aplicación web para la **gestión documental** de colaboradores y empresas: carga, clasificación, versionado e historial de documentos, con control de acceso por roles y permisos.

Desarrollada en **Laravel 12 (PHP 8.2)**, con **MySQL 8**, **Nginx** y despliegue en **Docker**. La interfaz usa Blade + Bootstrap 5 y DataTables.

---

## Funcionalidades

- **Autenticación y seguridad**: login, control de sesión, cambio de contraseña obligatorio por vencimiento.
- **Roles y permisos** granulares (ej. `documentos.crear`, `empresas.editar`). Roles base: Administrador, Gestor Documental, Viewer Documental.
- **Usuarios**: alta, edición, activación/inactivación.
- **Documentos**: carga (hasta 25 MB), previsualización protegida, edición, duplicado, inactivación, **papelera** (documentos inactivos) e **historial** de acciones con exportación a Excel.
- **Colaboradores**: datos personales, información adicional (nómina, EPS, fondo, etc.), contratos y detalle.
- **Empresas**: datos, contratos de empresa e información adicional.
- **Contratos** de colaborador y de empresa, con inactivación y motivo.
- **Áreas** y **Categorías** para clasificar los documentos.
- **Dashboard** con totales (usuarios, documentos, roles, colaboradores, empresas).

Los documentos se guardan en disco bajo `public/bodega_documental/<area>/<categoria>/<año>/` y se asocian de forma **polimórfica** a su propietario (empresa, colaborador, contrato o contrato de empresa).

---

## Arquitectura

| Componente | Tecnología |
|---|---|
| Backend | Laravel 12 (PHP 8.2-fpm) |
| Base de datos | MySQL 8 |
| Servidor web | Nginx (fastcgi a php-fpm en el puerto 9000) |
| Frontend | Blade + Bootstrap 5 + DataTables (CDN) |
| Exportación | maatwebsite/excel |
| Contenedores | Docker / Docker Compose |

---

## Puesta en marcha (desarrollo local)

Requisitos: Docker y Docker Compose.

```bash
# 1. Copiar variables de entorno
cp .env.example .env            # ajustar DB_* y APP_URL

# 2. Levantar los contenedores (app, nginx, mysql)
docker compose up -d --build

# 3. Preparar la aplicación
docker exec cmi_app php artisan key:generate --force
docker exec cmi_app php artisan migrate --force
```

> **Nota sobre la base de datos:** `php artisan migrate` crea tanto las tablas de Laravel como las de negocio (empresas, colaboradores, documentos, roles, permisos, etc.). Luego `php artisan db:seed` carga roles, permisos, catálogos y el usuario administrador. El esquema de negocio fue reconstruido a partir de los modelos; si existe el dump oficial de producción, ese prevalece.

> **Contraseñas:** se almacenan cifradas con `App\Helpers\CryptoHelper` (AES). Para crear un usuario manualmente, cifra la contraseña con `CryptoHelper::Enc('enc', '...')`.

---

## Despliegue en servidor (Portainer + Traefik)

El stack `docker-compose.portainer.yml` está preparado para desplegar en el servidor con **Portainer CE** (stack desde Git) y **Traefik** como reverse proxy. A diferencia de `docker-compose.yml`:

- **`pull_policy: build`** en las imágenes propias (`app`, `nginx`): Portainer las **construye** en vez de intentar descargarlas de un registro (si no, falla con `pull access denied for cmi_app`). Para tomar cambios: *Pull and redeploy* con *Re-pull image and redeploy*.
- **Sin bind mounts del repo**: el código viaja dentro de las imágenes (`COPY . .`), porque Portainer clona el repo en su propio contenedor y esa ruta no existe en el host.
- **No publica puertos**: el ingreso va por Traefik (red externa `net_proxy`, TLS en Traefik). Evita conflictos entre stacks.
- **Variables por la sección *Environment* del stack**, no por un `.env` del repo.
- Datos persistentes en volúmenes con nombre: `cmi-mysql-data`, `cmi-storage`, `cmi-bodega`.

**Variables del stack** — en Portainer se definen en **Stack → *Environment*** (no hace falta un `.env` en el repo; de hecho no existe porque está en `.gitignore`). La app toma su configuración de estas variables de entorno:

| Variable | Ejemplo | Descripción |
|---|---|---|
| `APP_KEY` | `base64:...` | **Obligatoria.** Genérala con `php artisan key:generate --show` |
| `APP_HOST` | `cmi-dev.servitel.co` | Dominio que publica Traefik |
| `APP_URL` | `https://cmi-dev.servitel.co` | URL base (igual al dominio, con `https`) |
| `APP_ENV` / `APP_DEBUG` / `APP_LOCALE` | `local` / `true` / `es` | (Opcionales) por defecto dev |
| `TRAEFIK_ENTRYPOINT` | `websecure` | Entrypoint de Traefik |
| `TRAEFIK_CERTRESOLVER` | `le` | (Opcional) resolver de certificados (descomentar en el compose) |
| `DB_DATABASE` / `DB_USERNAME` | `cmi` / `cmi` | Base y usuario MySQL |
| `DB_PASSWORD` | | **Obligatoria.** Contraseña del usuario MySQL |
| `DB_ROOT_PASSWORD` | | **Obligatoria.** Contraseña root de MySQL |

> **Despliegue por CLI** (en vez de Portainer): puedes poner estas variables en un archivo `.env` junto al compose y levantar con `docker compose --env-file .env -f docker-compose.portainer.yml up -d --build`.

La red externa debe existir antes de desplegar (la crea Traefik):

```bash
docker network create net_proxy   # solo si aún no existe
```

Nginx enruta internamente en el puerto 80; Traefik termina el TLS, por lo que **no se usa** el certificado autofirmado de `docker/nginx/ssl`.

### Datos persistentes

Los datos viven en **volúmenes con nombre** (independientes del ciclo de vida de los contenedores):

| Volumen | Contenido |
|---|---|
| `cmi-mysql-data` | Base de datos MySQL |
| `cmi-storage` | `storage/app` de Laravel |
| `cmi-bodega` | Documentos cargados (`public/bodega_documental`) |

### Primer arranque (base de datos)

Como la BD arranca vacía, tras levantar el stack hay que crear el esquema y los datos iniciales (una sola vez):

```bash
docker exec cmi-app php artisan migrate --force     # crea TODAS las tablas (Laravel + negocio)
docker exec cmi-app php artisan db:seed --force     # roles, permisos, catálogos y usuario admin
```

Esto crea un usuario administrador inicial:

- **Usuario:** `admin@cmi.local`  ·  **Contraseña:** `Admin123*`  → **cámbiala tras el primer ingreso.**

(Se pueden fijar otras credenciales con las variables `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` antes del seed.)

> El esquema de negocio fue **reconstruido** a partir de los modelos (no existía como migraciones). Si el equipo dispone del dump oficial de producción, ese es la fuente de verdad; cárgalo en su lugar.

---

## Estrategia de ramas

| Rama | Uso |
|---|---|
| `main` | Rama estable / oficial. |
| `dev` | Desarrollo e integración de nuevas funcionalidades. |
| `Dev_Serv` | Rama ligada al despliegue del servidor. |

Flujo sugerido: trabajar en `dev` → fusionar a `main` cuando esté estable → actualizar `Dev_Serv` al desplegar.

---

## Pendientes técnicos

- [x] **Versionar el esquema de BD** como migraciones de Laravel (reconstruido en `2026_11_24_000001_create_business_schema`; validar contra el dump oficial cuando esté disponible).
- [ ] **No guardar la llave privada SSL** (`docker/nginx/ssl/key.pem`) en el repositorio; regenerarla.
- [ ] **Sacar del repositorio los PDFs reales** de `public/bodega_documental/` (contienen datos personales).
- [ ] **Reemplazar el cifrado reversible de contraseñas** por un hash seguro (`bcrypt`/`argon2`).
