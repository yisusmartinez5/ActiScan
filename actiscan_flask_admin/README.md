## ActiScan Flask Admin

Panel administrativo Flask conectado a FastAPI mediante un bridge interno.

### Bridge de API

- Frontend llama a `/api-bridge/...`
- Flask reenvia a FastAPI usando `ACTISCAN_API_BASE` o autodeteccion

### Variables recomendadas

- `ACTISCAN_API_BASE=http://127.0.0.1:8001/api`
- `ACTISCAN_API_V1_BASE=http://127.0.0.1:8001/api/v1`

### Ejecutar en Docker

```bash
cd actiscan_flask_admin
docker compose up --build
```

Si FastAPI corre en otro compose/proyecto, usa `host.docker.internal:8001` como base.

