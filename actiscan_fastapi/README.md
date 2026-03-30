# ActiScan FastAPI

Servicio API para conectar los frontends de Laravel con la logica de negocio y la base de datos usando el esquema de `actiscan (1).sql`.

## Docker (recomendado)

```bash
cd actiscan_fastapi
docker compose up --build
```

La API queda disponible en:

`http://localhost:8001`

Se crea la base SQLite persistente en:

`actiscan_fastapi/data/actiscan.db`

## Local sin Docker

### 1. Instalar dependencias

```bash
cd actiscan_fastapi
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
```

### 2. Ejecutar la API

```bash
uvicorn main:app --reload --host 127.0.0.1 --port 8001
```

## 3. Integracion con Laravel

Laravel ya usa el bridge interno `/api-bridge`, por lo que no necesitas exponer la URL de FastAPI al navegador.
Solo debes asegurar que FastAPI este activo en el host o red Docker accesible desde el contenedor de Laravel.

## Nota de base de datos

Al iniciar, la API toma el archivo `actiscan (1).sql`, transforma el esquema a SQLite y lo crea en:

`actiscan_fastapi/data/actiscan.db`

Tambien inserta datos iniciales si la tabla `usuarios` esta vacia.

## Estructura de la API

La API esta organizada por capas para separar responsabilidades:

```text
actiscan_fastapi/
	app/
		api/          # Endpoints (routers)
		schemas/      # Modelos de entrada (Pydantic)
		services/     # Logica de negocio y consultas SQL
		factory.py    # Creacion y configuracion de FastAPI
	db.py           # Conexion y bootstrap de SQLite
	main.py         # Entry point (app = create_app())
```

Los endpoints publicos se mantienen sin cambios para no romper integraciones existentes.

## Versionado y compatibilidad

La API expone rutas en dos versiones activas:

- Compatibilidad actual: `/api/...`
- Nueva version: `/api/v1/...`

Ejemplos equivalentes:

- `/api/auth/login` y `/api/v1/auth/login`
- `/api/assets` y `/api/v1/assets`
- `/api/categories` y `/api/v1/categories`

El endpoint de salud permanece en `/health`.

## Base de datos y esquema SQL

Se toma como fuente el esquema de `actiscan (1).sql` (tablas de roles, permisos, activos, movimientos, asignaciones y QR).
En el arranque, la API:

- transforma el SQL Server DDL a SQLite,
- crea tablas si no existen,
- y aplica semillas iniciales coherentes con el esquema (roles, permisos, rol_permisos, usuarios, catalogos y tipo_movimientos).
