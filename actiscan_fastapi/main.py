from __future__ import annotations

from datetime import datetime
from typing import Any

from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, EmailStr, Field
from sqlalchemy import text

from db import engine, init_db


app = FastAPI(title="ActiScan API", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)


class LoginRequest(BaseModel):
    email: EmailStr
    password: str = Field(min_length=1)


class CategoryCreateRequest(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    descripcion: str | None = Field(default=None, max_length=150)


class AssetCreateRequest(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    marca: str | None = Field(default=None, max_length=100)
    modelo: str | None = Field(default=None, max_length=100)
    numero_serie: str | None = Field(default=None, max_length=100)
    codigo_interno: str | None = Field(default=None, max_length=50)
    descripcion: str | None = Field(default=None, max_length=150)
    id_categoria: int
    id_estado_activo: int
    id_ubicacion: int
    id_usuario: int | None = None


@app.on_event("startup")
def on_startup() -> None:
    init_db()


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.post("/api/auth/login")
def login(payload: LoginRequest) -> dict[str, Any]:
    with engine.begin() as conn:
        row = conn.execute(
            text(
                """
                SELECT u.id, u.nombre, u.apellido, u.correo, r.nombre AS rol
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.id_rol
                WHERE u.correo = :correo
                  AND u.contrasena = :contrasena
                  AND u.estatus_usuario = 'Activo'
                """
            ),
            {"correo": payload.email, "contrasena": payload.password},
        ).mappings().first()

    if not row:
        raise HTTPException(status_code=401, detail="Credenciales invalidas")

    return {
        "user": {
            "id": row["id"],
            "nombre": row["nombre"],
            "apellido": row["apellido"],
            "correo": row["correo"],
            "rol": row["rol"],
        }
    }


@app.get("/api/dashboard/summary")
def dashboard_summary() -> dict[str, Any]:
    with engine.begin() as conn:
        total_assets = conn.execute(text("SELECT COUNT(*) FROM activos")).scalar_one()
        active_categories = conn.execute(
            text("SELECT COUNT(*) FROM categoria_activos WHERE estatus_categoria = 'Activa'")
        ).scalar_one()
        obs_assets = conn.execute(
            text(
                """
                SELECT COUNT(*)
                FROM activos a
                INNER JOIN estado_activos e ON e.id = a.id_estado_activo
                WHERE lower(e.nombre) LIKE '%mantenimiento%'
                """
            )
        ).scalar_one()

        recent_assets = conn.execute(
            text(
                """
                SELECT a.id, a.codigo_interno, a.nombre, c.nombre AS categoria, e.nombre AS estado, u.nombre AS ubicacion
                FROM activos a
                INNER JOIN categoria_activos c ON c.id = a.id_categoria
                INNER JOIN estado_activos e ON e.id = a.id_estado_activo
                INNER JOIN ubicaciones u ON u.id = a.id_ubicacion
                ORDER BY a.id DESC
                LIMIT 6
                """
            )
        ).mappings().all()

    return {
        "stats": {
            "total_activos": total_assets,
            "categorias_activas": active_categories,
            "activos_con_observaciones": obs_assets,
        },
        "recent_assets": [dict(row) for row in recent_assets],
    }


@app.get("/api/lookups")
def lookups() -> dict[str, list[dict[str, Any]]]:
    with engine.begin() as conn:
        categories = conn.execute(
            text(
                """
                SELECT id, nombre
                FROM categoria_activos
                WHERE estatus_categoria = 'Activa'
                ORDER BY nombre
                """
            )
        ).mappings().all()

        statuses = conn.execute(text("SELECT id, nombre FROM estado_activos ORDER BY nombre")).mappings().all()
        locations = conn.execute(text("SELECT id, nombre FROM ubicaciones ORDER BY nombre")).mappings().all()
        users = conn.execute(
            text(
                """
                SELECT id, nombre || ' ' || apellido AS nombre
                FROM usuarios
                WHERE estatus_usuario = 'Activo'
                ORDER BY nombre
                """
            )
        ).mappings().all()

    return {
        "categories": [dict(row) for row in categories],
        "statuses": [dict(row) for row in statuses],
        "locations": [dict(row) for row in locations],
        "users": [dict(row) for row in users],
    }


@app.get("/api/categories")
def list_categories() -> list[dict[str, Any]]:
    with engine.begin() as conn:
        rows = conn.execute(
            text(
                """
                SELECT c.id, c.nombre, c.descripcion, c.estatus_categoria, COUNT(a.id) AS total_activos
                FROM categoria_activos c
                LEFT JOIN activos a ON a.id_categoria = c.id
                GROUP BY c.id, c.nombre, c.descripcion, c.estatus_categoria
                ORDER BY c.id DESC
                """
            )
        ).mappings().all()
    return [dict(row) for row in rows]


@app.post("/api/categories")
def create_category(payload: CategoryCreateRequest) -> dict[str, Any]:
    with engine.begin() as conn:
        duplicate = conn.execute(
            text("SELECT id FROM categoria_activos WHERE lower(nombre) = lower(:nombre)"),
            {"nombre": payload.nombre.strip()},
        ).first()
        if duplicate:
            raise HTTPException(status_code=409, detail="La categoria ya existe")

        result = conn.execute(
            text(
                """
                INSERT INTO categoria_activos (nombre, descripcion, estatus_categoria)
                VALUES (:nombre, :descripcion, 'Activa')
                """
            ),
            {"nombre": payload.nombre.strip(), "descripcion": payload.descripcion},
        )
        category_id = result.lastrowid

    return {"id": category_id, "message": "Categoria creada correctamente"}


@app.get("/api/assets")
def list_assets(
    q: str | None = Query(default=None),
    category_id: int | None = Query(default=None),
    status_id: int | None = Query(default=None),
) -> list[dict[str, Any]]:
    conditions = []
    params: dict[str, Any] = {}

    if q:
        conditions.append("(a.nombre LIKE :q OR a.codigo_interno LIKE :q OR a.numero_serie LIKE :q)")
        params["q"] = f"%{q.strip()}%"
    if category_id:
        conditions.append("a.id_categoria = :category_id")
        params["category_id"] = category_id
    if status_id:
        conditions.append("a.id_estado_activo = :status_id")
        params["status_id"] = status_id

    where_clause = " WHERE " + " AND ".join(conditions) if conditions else ""
    sql = f"""
        SELECT
            a.id,
            a.codigo_interno,
            a.nombre,
            a.descripcion,
            a.marca,
            a.modelo,
            a.numero_serie,
            c.id AS id_categoria,
            c.nombre AS categoria,
            e.id AS id_estado,
            e.nombre AS estado,
            u.id AS id_ubicacion,
            u.nombre AS ubicacion
        FROM activos a
        INNER JOIN categoria_activos c ON c.id = a.id_categoria
        INNER JOIN estado_activos e ON e.id = a.id_estado_activo
        INNER JOIN ubicaciones u ON u.id = a.id_ubicacion
        {where_clause}
        ORDER BY a.id DESC
    """

    with engine.begin() as conn:
        rows = conn.execute(text(sql), params).mappings().all()

    return [dict(row) for row in rows]


@app.get("/api/assets/{asset_id}")
def get_asset(asset_id: int) -> dict[str, Any]:
    with engine.begin() as conn:
        row = conn.execute(
            text(
                """
                SELECT
                    a.id,
                    a.codigo_interno,
                    a.nombre,
                    a.descripcion,
                    a.marca,
                    a.modelo,
                    a.numero_serie,
                    c.id AS id_categoria,
                    c.nombre AS categoria,
                    e.id AS id_estado,
                    e.nombre AS estado,
                    u.id AS id_ubicacion,
                    u.nombre AS ubicacion
                FROM activos a
                INNER JOIN categoria_activos c ON c.id = a.id_categoria
                INNER JOIN estado_activos e ON e.id = a.id_estado_activo
                INNER JOIN ubicaciones u ON u.id = a.id_ubicacion
                WHERE a.id = :asset_id
                """
            ),
            {"asset_id": asset_id},
        ).mappings().first()
        if not row:
            raise HTTPException(status_code=404, detail="Activo no encontrado")

    return dict(row)


@app.post("/api/assets")
def create_asset(payload: AssetCreateRequest) -> dict[str, Any]:
    code = payload.codigo_interno or f"AST{datetime.now().strftime('%H%M%S')}"

    with engine.begin() as conn:
        duplicate_code = conn.execute(
            text("SELECT id FROM activos WHERE codigo_interno = :codigo"), {"codigo": code}
        ).first()
        if duplicate_code:
            raise HTTPException(status_code=409, detail="Codigo interno ya existe")

        duplicate_serial = None
        if payload.numero_serie:
            duplicate_serial = conn.execute(
                text("SELECT id FROM activos WHERE numero_serie = :serie"),
                {"serie": payload.numero_serie},
            ).first()
        if duplicate_serial:
            raise HTTPException(status_code=409, detail="Numero de serie ya existe")

        result = conn.execute(
            text(
                """
                INSERT INTO activos
                (codigo_interno, nombre, descripcion, marca, modelo, numero_serie, id_categoria, id_estado_activo, id_ubicacion, id_usuario)
                VALUES
                (:codigo_interno, :nombre, :descripcion, :marca, :modelo, :numero_serie, :id_categoria, :id_estado_activo, :id_ubicacion, :id_usuario)
                """
            ),
            {
                "codigo_interno": code,
                "nombre": payload.nombre.strip(),
                "descripcion": payload.descripcion,
                "marca": payload.marca,
                "modelo": payload.modelo,
                "numero_serie": payload.numero_serie,
                "id_categoria": payload.id_categoria,
                "id_estado_activo": payload.id_estado_activo,
                "id_ubicacion": payload.id_ubicacion,
                "id_usuario": payload.id_usuario,
            },
        )
        asset_id = result.lastrowid

    return {"id": asset_id, "codigo_interno": code, "message": "Activo creado correctamente"}


@app.post("/api/assets/{asset_id}/qr")
def create_qr(asset_id: int) -> dict[str, Any]:
    qr_value = f"ACTISCAN-ASSET-{asset_id}-{int(datetime.now().timestamp())}"

    with engine.begin() as conn:
        exists = conn.execute(text("SELECT id FROM activos WHERE id = :asset_id"), {"asset_id": asset_id}).first()
        if not exists:
            raise HTTPException(status_code=404, detail="Activo no encontrado")

        conn.execute(
            text(
                """
                INSERT INTO qr_activos (valor_qr, vigente, id_activo)
                VALUES (:valor_qr, 1, :id_activo)
                """
            ),
            {"valor_qr": qr_value, "id_activo": asset_id},
        )

    return {"asset_id": asset_id, "qr": qr_value}
