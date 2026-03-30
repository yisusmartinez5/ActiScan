# Integracion ActiScan (FastAPI + Flask + Laravel)

Este workspace ya queda conectado para que `actiscan_flask_admin` y `actiscan_laravel` consuman la API de `actiscan_fastapi` por `/api-bridge`.

## Opciones de ejecucion

## 1) Todo junto (recomendado)

Desde la raiz del workspace:

```bash
docker compose -f docker-compose.fullstack.yml up --build
```

Servicios:

- FastAPI: http://localhost:8001
- Laravel: http://localhost:8000
- Flask Admin: http://localhost:5000

## 2) Ejecutar proyectos por separado

Configura estas variables:

- Flask: `ACTISCAN_API_BASE=http://127.0.0.1:8001/api`
- Laravel: `ACTISCAN_API_BASE=http://127.0.0.1:8001/api`

Ambos proyectos soportan FastAPI versionado (`/api/v1`) y fallback automatico.

## Notas tecnicas

- Flask usa bridge en `app.py` con ruta `/api-bridge/<path:path>`.
- Laravel usa bridge en `routes/web.php` con ruta `/api-bridge/{path}`.
- FastAPI mantiene compatibilidad en `/api/*` y `/api/v1/*`.
