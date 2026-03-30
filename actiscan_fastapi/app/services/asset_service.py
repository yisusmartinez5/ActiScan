from datetime import datetime
from io import BytesIO
from typing import Any

from fastapi import HTTPException
import qrcode
from sqlalchemy import text
from sqlalchemy.engine import Connection

def list_assets(
    conn: Connection,
    q: str | None,
    category_id: int | None,
    status_id: int | None,
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
            a.descripcion AS observaciones,
            a.foto_base64,
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

    rows = conn.execute(text(sql), params).mappings().all()

    return [dict(row) for row in rows]


def get_asset(conn: Connection, asset_id: int) -> dict[str, Any]:
    row = conn.execute(
        text(
            """
            SELECT
                a.id,
                a.codigo_interno,
                a.nombre,
                a.descripcion,
                a.descripcion AS observaciones,
                a.foto_base64,
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


def create_asset(conn: Connection, payload: dict[str, Any]) -> dict[str, Any]:
    code = payload.get("codigo_interno") or f"AST{datetime.now().strftime('%H%M%S')}"

    duplicate_code = conn.execute(
        text("SELECT id FROM activos WHERE codigo_interno = :codigo"), {"codigo": code}
    ).first()
    if duplicate_code:
        raise HTTPException(status_code=409, detail="Codigo interno ya existe")

    duplicate_serial = None
    if payload.get("numero_serie"):
        duplicate_serial = conn.execute(
            text("SELECT id FROM activos WHERE numero_serie = :serie"),
            {"serie": payload["numero_serie"]},
        ).first()
    if duplicate_serial:
        raise HTTPException(status_code=409, detail="Numero de serie ya existe")

    result = conn.execute(
        text(
            """
            INSERT INTO activos
            (codigo_interno, nombre, descripcion, marca, modelo, numero_serie, id_categoria, id_estado_activo, id_ubicacion, id_usuario, foto_base64)
            VALUES
            (:codigo_interno, :nombre, :descripcion, :marca, :modelo, :numero_serie, :id_categoria, :id_estado_activo, :id_ubicacion, :id_usuario, :foto_base64)
            """
        ),
        {
            "codigo_interno": code,
            "nombre": payload["nombre"].strip(),
            "descripcion": payload.get("descripcion") or payload.get("observaciones"),
            "marca": payload.get("marca"),
            "modelo": payload.get("modelo"),
            "numero_serie": payload.get("numero_serie"),
            "id_categoria": payload["id_categoria"],
            "id_estado_activo": payload["id_estado_activo"],
            "id_ubicacion": payload["id_ubicacion"],
            "id_usuario": payload.get("id_usuario"),
            "foto_base64": payload.get("foto_base64"),
        },
    )
    asset_id = result.lastrowid

    return {"id": asset_id, "codigo_interno": code, "message": "Activo creado correctamente"}


def update_asset(conn: Connection, asset_id: int, payload: dict[str, Any]) -> dict[str, Any]:
    current = conn.execute(
        text("SELECT id, codigo_interno, numero_serie FROM activos WHERE id = :asset_id"),
        {"asset_id": asset_id},
    ).mappings().first()
    if not current:
        raise HTTPException(status_code=404, detail="Activo no encontrado")

    code = payload.get("codigo_interno") or current["codigo_interno"]

    duplicate_code = conn.execute(
        text("SELECT id FROM activos WHERE codigo_interno = :codigo AND id <> :asset_id"),
        {"codigo": code, "asset_id": asset_id},
    ).first()
    if duplicate_code:
        raise HTTPException(status_code=409, detail="Codigo interno ya existe")

    incoming_serial = payload.get("numero_serie")
    if incoming_serial:
        duplicate_serial = conn.execute(
            text("SELECT id FROM activos WHERE numero_serie = :serie AND id <> :asset_id"),
            {"serie": incoming_serial, "asset_id": asset_id},
        ).first()
        if duplicate_serial:
            raise HTTPException(status_code=409, detail="Numero de serie ya existe")

    conn.execute(
        text(
            """
            UPDATE activos
            SET codigo_interno = :codigo_interno,
                nombre = :nombre,
                descripcion = :descripcion,
                marca = :marca,
                modelo = :modelo,
                numero_serie = :numero_serie,
                id_categoria = :id_categoria,
                id_estado_activo = :id_estado_activo,
                id_ubicacion = :id_ubicacion,
                id_usuario = :id_usuario
                , foto_base64 = :foto_base64
            WHERE id = :asset_id
            """
        ),
        {
            "asset_id": asset_id,
            "codigo_interno": code,
            "nombre": payload["nombre"].strip(),
            "descripcion": payload.get("descripcion") or payload.get("observaciones"),
            "marca": payload.get("marca"),
            "modelo": payload.get("modelo"),
            "numero_serie": incoming_serial,
            "id_categoria": payload["id_categoria"],
            "id_estado_activo": payload["id_estado_activo"],
            "id_ubicacion": payload["id_ubicacion"],
            "id_usuario": payload.get("id_usuario"),
            "foto_base64": payload.get("foto_base64"),
        },
    )

    return {"id": asset_id, "codigo_interno": code, "message": "Activo actualizado correctamente"}


def delete_asset(conn: Connection, asset_id: int) -> dict[str, Any]:
    exists = conn.execute(
        text("SELECT id FROM activos WHERE id = :asset_id"),
        {"asset_id": asset_id},
    ).first()
    if not exists:
        raise HTTPException(status_code=404, detail="Activo no encontrado")

    existing_tables = {
        row["name"]
        for row in conn.execute(text("SELECT name FROM sqlite_master WHERE type = 'table'"))
        .mappings()
        .all()
    }

    dependent_tables = [
        "qr_activos",
        "auditorias",
        "asignaciones_activos",
        "asignaciones",
        "movimientos",
    ]
    for table_name in dependent_tables:
        if table_name in existing_tables:
            conn.execute(
                text(f"DELETE FROM {table_name} WHERE id_activo = :asset_id"),
                {"asset_id": asset_id},
            )

    conn.execute(text("DELETE FROM activos WHERE id = :asset_id"), {"asset_id": asset_id})

    return {"message": "Activo eliminado correctamente"}


def create_qr(conn: Connection, asset_id: int) -> dict[str, Any]:
    qr_value = f"ACTISCAN-ASSET-{asset_id}-{int(datetime.now().timestamp())}"

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


def get_latest_qr(conn: Connection, asset_id: int) -> dict[str, Any]:
    exists = conn.execute(text("SELECT id FROM activos WHERE id = :asset_id"), {"asset_id": asset_id}).first()
    if not exists:
        raise HTTPException(status_code=404, detail="Activo no encontrado")

    row = conn.execute(
        text(
            """
            SELECT valor_qr, id, vigente
            FROM qr_activos
            WHERE id_activo = :asset_id
            ORDER BY id DESC
            LIMIT 1
            """
        ),
        {"asset_id": asset_id},
    ).mappings().first()

    if not row:
        raise HTTPException(status_code=404, detail="El activo aun no tiene QR generado")

    return {
        "asset_id": asset_id,
        "qr": row["valor_qr"],
        "qr_id": row["id"],
        "vigente": row["vigente"],
    }


def get_latest_qr_png(conn: Connection, asset_id: int) -> bytes:
    latest = get_latest_qr(conn=conn, asset_id=asset_id)
    qr_value = latest["qr"]

    qr = qrcode.QRCode(box_size=8, border=2)
    qr.add_data(qr_value)
    qr.make(fit=True)
    img = qr.make_image(fill_color="black", back_color="white")

    buf = BytesIO()
    img.save(buf, format="PNG")
    return buf.getvalue()
