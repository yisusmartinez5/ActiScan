from typing import Any

from fastapi import HTTPException
from sqlalchemy import text
from sqlalchemy.engine import Connection


DEFAULT_CATEGORY_ICON = "fa-solid fa-layer-group"
DEFAULT_CATEGORY_COLOR = "#4a90e2"


def _normalized_icon_color(icono: str | None, color: str | None) -> tuple[str, str]:
    icon = (icono or "").strip() or DEFAULT_CATEGORY_ICON
    tone = (color or "").strip() or DEFAULT_CATEGORY_COLOR
    return icon, tone

def list_categories(conn: Connection) -> list[dict[str, Any]]:
    rows = conn.execute(
        text(
            """
            SELECT
                c.id,
                c.nombre,
                c.descripcion,
                c.estatus_categoria,
                COALESCE(c.icono, :default_icon) AS icono,
                COALESCE(c.color, :default_color) AS color,
                COUNT(a.id) AS total_activos
            FROM categoria_activos c
            LEFT JOIN activos a ON a.id_categoria = c.id
            GROUP BY c.id, c.nombre, c.descripcion, c.estatus_categoria, c.icono, c.color
            ORDER BY c.id DESC
            """
        ),
        {"default_icon": DEFAULT_CATEGORY_ICON, "default_color": DEFAULT_CATEGORY_COLOR},
    ).mappings().all()
    return [dict(row) for row in rows]


def get_category(conn: Connection, category_id: int) -> dict[str, Any]:
    row = conn.execute(
        text(
            """
            SELECT
                id,
                nombre,
                descripcion,
                estatus_categoria,
                COALESCE(icono, :default_icon) AS icono,
                COALESCE(color, :default_color) AS color
            FROM categoria_activos
            WHERE id = :category_id
            """
        ),
        {
            "category_id": category_id,
            "default_icon": DEFAULT_CATEGORY_ICON,
            "default_color": DEFAULT_CATEGORY_COLOR,
        },
    ).mappings().first()
    if not row:
        raise HTTPException(status_code=404, detail="Categoria no encontrada")
    return dict(row)


def create_category(
    conn: Connection,
    nombre: str,
    descripcion: str | None,
    icono: str | None,
    color: str | None,
) -> dict[str, Any]:
    clean_name = nombre.strip()
    normalized_icon, normalized_color = _normalized_icon_color(icono=icono, color=color)

    duplicate = conn.execute(
        text("SELECT id FROM categoria_activos WHERE lower(nombre) = lower(:nombre)"),
        {"nombre": clean_name},
    ).first()
    if duplicate:
        raise HTTPException(status_code=409, detail="La categoria ya existe")

    result = conn.execute(
        text(
            """
            INSERT INTO categoria_activos (nombre, descripcion, estatus_categoria, icono, color)
            VALUES (:nombre, :descripcion, 'Activa', :icono, :color)
            """
        ),
        {
            "nombre": clean_name,
            "descripcion": descripcion,
            "icono": normalized_icon,
            "color": normalized_color,
        },
    )
    category_id = result.lastrowid

    return {"id": category_id, "message": "Categoria creada correctamente"}


def update_category(
    conn: Connection,
    category_id: int,
    nombre: str,
    descripcion: str | None,
    icono: str | None,
    color: str | None,
) -> dict[str, Any]:
    current = conn.execute(
        text("SELECT id FROM categoria_activos WHERE id = :category_id"),
        {"category_id": category_id},
    ).first()
    if not current:
        raise HTTPException(status_code=404, detail="Categoria no encontrada")

    clean_name = nombre.strip()
    duplicate = conn.execute(
        text("SELECT id FROM categoria_activos WHERE lower(nombre) = lower(:nombre) AND id <> :category_id"),
        {"nombre": clean_name, "category_id": category_id},
    ).first()
    if duplicate:
        raise HTTPException(status_code=409, detail="La categoria ya existe")

    normalized_icon, normalized_color = _normalized_icon_color(icono=icono, color=color)
    conn.execute(
        text(
            """
            UPDATE categoria_activos
            SET nombre = :nombre,
                descripcion = :descripcion,
                icono = :icono,
                color = :color
            WHERE id = :category_id
            """
        ),
        {
            "category_id": category_id,
            "nombre": clean_name,
            "descripcion": descripcion,
            "icono": normalized_icon,
            "color": normalized_color,
        },
    )
    return {"id": category_id, "message": "Categoria actualizada correctamente"}
