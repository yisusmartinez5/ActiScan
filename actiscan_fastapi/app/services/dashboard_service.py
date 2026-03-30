from typing import Any

from sqlalchemy import text
from sqlalchemy.engine import Connection

def get_dashboard_summary(conn: Connection) -> dict[str, Any]:
    total_assets = conn.execute(text("SELECT COUNT(*) FROM activos")).scalar_one()
    total_audits = conn.execute(text("SELECT COUNT(*) FROM auditorias")).scalar_one()
    total_users = conn.execute(
        text(
            """
            SELECT COUNT(*)
            FROM usuarios
            WHERE lower(coalesce(estatus_usuario, 'activo')) = 'activo'
            """
        )
    ).scalar_one()
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
            "auditorias_realizadas": total_audits,
            "total_auditorias": total_audits,
            "total_usuarios": total_users,
            "categorias_activas": active_categories,
            "activos_con_observaciones": obs_assets,
        },
        "recent_assets": [dict(row) for row in recent_assets],
    }


def get_lookups(conn: Connection) -> dict[str, list[dict[str, Any]]]:
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
