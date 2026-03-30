from typing import Any

from fastapi import HTTPException
from sqlalchemy import text
from sqlalchemy.engine import Connection


def _normalize_role(role: str) -> str:
    value = (role or "").strip().lower()
    if value in {"admin", "administrador"}:
        return "administrador"
    if value in {"capturista", "capturist"}:
        return "capturista"
    return value


def _split_full_name(full_name: str) -> tuple[str, str]:
    clean = " ".join((full_name or "").strip().split())
    if not clean:
        raise HTTPException(status_code=422, detail="El nombre es obligatorio")
    parts = clean.split(" ")
    nombre = parts[0]
    apellido = " ".join(parts[1:]).strip() or "N/A"
    return nombre, apellido


def _resolve_role_id(conn: Connection, role: str) -> tuple[int, str]:
    normalized = _normalize_role(role)
    row = conn.execute(
        text(
            """
            SELECT id, nombre
            FROM roles
            WHERE LOWER(nombre) = :rol
            LIMIT 1
            """
        ),
        {"rol": normalized},
    ).mappings().first()

    if not row:
        raise HTTPException(status_code=400, detail="Rol invalido. Usa administrador o capturista")

    return int(row["id"]), str(row["nombre"])


def _row_to_user(row: dict[str, Any]) -> dict[str, Any]:
    return {
        "id": int(row["id"]),
        "nombre": str(row["nombre"]),
        "email": str(row["email"]),
        "rol": str(row["rol"]),
        "estado": bool(row["estado"]),
    }


def list_users(conn: Connection) -> list[dict[str, Any]]:
    rows = conn.execute(
        text(
            """
            SELECT
                u.id,
                TRIM(COALESCE(u.nombre, '') || ' ' || COALESCE(u.apellido, '')) AS nombre,
                u.correo AS email,
                r.nombre AS rol,
                CASE WHEN LOWER(COALESCE(u.estatus_usuario, '')) = 'activo' THEN 1 ELSE 0 END AS estado
            FROM usuarios u
            INNER JOIN roles r ON r.id = u.id_rol
            WHERE LOWER(COALESCE(u.estatus_usuario, '')) = 'activo'
            ORDER BY u.id DESC
            """
        )
    ).mappings().all()

    return [_row_to_user(row) for row in rows]


def get_user(conn: Connection, user_id: int) -> dict[str, Any]:
    row = conn.execute(
        text(
            """
            SELECT
                u.id,
                TRIM(COALESCE(u.nombre, '') || ' ' || COALESCE(u.apellido, '')) AS nombre,
                u.correo AS email,
                r.nombre AS rol,
                CASE WHEN LOWER(COALESCE(u.estatus_usuario, '')) = 'activo' THEN 1 ELSE 0 END AS estado
            FROM usuarios u
            INNER JOIN roles r ON r.id = u.id_rol
            WHERE u.id = :user_id
            LIMIT 1
            """
        ),
        {"user_id": user_id},
    ).mappings().first()

    if not row:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")

    return _row_to_user(row)


def create_user(conn: Connection, nombre: str, email: str, rol: str, password: str) -> dict[str, Any]:
    email_clean = (email or "").strip().lower()
    if not email_clean:
        raise HTTPException(status_code=422, detail="El correo es obligatorio")

    exists = conn.execute(
        text("SELECT id FROM usuarios WHERE LOWER(correo) = :correo LIMIT 1"),
        {"correo": email_clean},
    ).mappings().first()
    if exists:
        raise HTTPException(status_code=409, detail="Ya existe un usuario con ese correo")

    if not password:
        raise HTTPException(status_code=422, detail="La contrasena es obligatoria")

    nombre_part, apellido_part = _split_full_name(nombre)
    role_id, _ = _resolve_role_id(conn, rol)

    result = conn.execute(
        text(
            """
            INSERT INTO usuarios (nombre, apellido, correo, contrasena, telefono, estatus_usuario, id_rol)
            VALUES (:nombre, :apellido, :correo, :contrasena, NULL, 'Activo', :id_rol)
            """
        ),
        {
            "nombre": nombre_part,
            "apellido": apellido_part,
            "correo": email_clean,
            "contrasena": password,
            "id_rol": role_id,
        },
    )

    return get_user(conn, int(result.lastrowid))


def update_user(
    conn: Connection,
    user_id: int,
    nombre: str,
    email: str,
    rol: str,
    password: str | None = None,
) -> dict[str, Any]:
    current = conn.execute(
        text("SELECT id FROM usuarios WHERE id = :user_id LIMIT 1"),
        {"user_id": user_id},
    ).mappings().first()
    if not current:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")

    email_clean = (email or "").strip().lower()
    duplicate = conn.execute(
        text("SELECT id FROM usuarios WHERE LOWER(correo) = :correo AND id <> :user_id LIMIT 1"),
        {"correo": email_clean, "user_id": user_id},
    ).mappings().first()
    if duplicate:
        raise HTTPException(status_code=409, detail="Ya existe otro usuario con ese correo")

    nombre_part, apellido_part = _split_full_name(nombre)
    role_id, _ = _resolve_role_id(conn, rol)

    if password:
        conn.execute(
            text(
                """
                UPDATE usuarios
                SET nombre = :nombre,
                    apellido = :apellido,
                    correo = :correo,
                    contrasena = :contrasena,
                    id_rol = :id_rol
                WHERE id = :user_id
                """
            ),
            {
                "nombre": nombre_part,
                "apellido": apellido_part,
                "correo": email_clean,
                "contrasena": password,
                "id_rol": role_id,
                "user_id": user_id,
            },
        )
    else:
        conn.execute(
            text(
                """
                UPDATE usuarios
                SET nombre = :nombre,
                    apellido = :apellido,
                    correo = :correo,
                    id_rol = :id_rol
                WHERE id = :user_id
                """
            ),
            {
                "nombre": nombre_part,
                "apellido": apellido_part,
                "correo": email_clean,
                "id_rol": role_id,
                "user_id": user_id,
            },
        )

    return get_user(conn, user_id)


def deactivate_user(conn: Connection, user_id: int) -> None:
    row = conn.execute(
        text("SELECT id FROM usuarios WHERE id = :user_id LIMIT 1"),
        {"user_id": user_id},
    ).mappings().first()
    if not row:
        raise HTTPException(status_code=404, detail="Usuario no encontrado")

    conn.execute(
        text(
            """
            UPDATE usuarios
            SET estatus_usuario = 'Inactivo'
            WHERE id = :user_id
            """
        ),
        {"user_id": user_id},
    )
