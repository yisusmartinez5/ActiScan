from typing import Any

from fastapi import HTTPException
from sqlalchemy import text
from sqlalchemy.engine import Connection


def list_audits(conn: Connection) -> list[dict[str, Any]]:
    rows = conn.execute(
        text(
            """
            SELECT
                au.id,
                au.id_activo,
                a.nombre AS activo_nombre,
                au.tipo,
                au.descripcion,
                au.resultado,
                au.fecha_auditoria
            FROM auditorias au
            INNER JOIN activos a ON a.id = au.id_activo
            ORDER BY au.id DESC
            """
        )
    ).mappings().all()
    return [dict(row) for row in rows]


def create_audit(
    conn: Connection,
    id_activo: int,
    tipo: str,
    descripcion: str | None,
    resultado: str,
) -> dict[str, Any]:
    activo = conn.execute(
        text("SELECT id FROM activos WHERE id = :id_activo"),
        {"id_activo": id_activo},
    ).first()
    if not activo:
        raise HTTPException(status_code=404, detail="Activo no encontrado")

    result = conn.execute(
        text(
            """
            INSERT INTO auditorias (id_activo, tipo, descripcion, resultado)
            VALUES (:id_activo, :tipo, :descripcion, :resultado)
            """
        ),
        {
            "id_activo": id_activo,
            "tipo": tipo.strip(),
            "descripcion": descripcion,
            "resultado": resultado.strip(),
        },
    )
    return {"id": result.lastrowid, "message": "Auditoria creada correctamente"}


def delete_audit(conn: Connection, audit_id: int) -> dict[str, Any]:
    deleted = conn.execute(
        text("DELETE FROM auditorias WHERE id = :audit_id"),
        {"audit_id": audit_id},
    )
    if deleted.rowcount == 0:
        raise HTTPException(status_code=404, detail="Auditoria no encontrada")
    return {"message": "Auditoria eliminada correctamente"}
