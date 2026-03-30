from typing import Any

from fastapi import HTTPException
from sqlalchemy import text
from sqlalchemy.engine import Connection


def login_user(conn: Connection, email: str, password: str) -> dict[str, Any]:
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
        {"correo": email, "contrasena": password},
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
