from __future__ import annotations

import os
import re
from pathlib import Path

from sqlalchemy import create_engine, text
from sqlalchemy.engine import Engine


ROOT_DIR = Path(__file__).resolve().parents[1]
DEFAULT_DB_PATH = Path(__file__).resolve().parent / "actiscan.db"
DB_PATH = Path(os.getenv("ACTISCAN_DB_PATH", str(DEFAULT_DB_PATH))).resolve()

SQL_DUMP_CANDIDATES = [
    Path(os.getenv("ACTISCAN_SQL_DUMP", "")).resolve() if os.getenv("ACTISCAN_SQL_DUMP") else None,
    (Path(__file__).resolve().parent / "actiscan (1).sql").resolve(),
    (ROOT_DIR / "actiscan (1).sql").resolve(),
    Path("/app/actiscan (1).sql").resolve(),
]


def _resolve_sql_dump_path() -> Path:
    for candidate in SQL_DUMP_CANDIDATES:
        if candidate and candidate.exists():
            return candidate
    raise FileNotFoundError("SQL dump not found. Set ACTISCAN_SQL_DUMP with a valid path.")


SQL_DUMP_PATH = _resolve_sql_dump_path()

DATABASE_URL = f"sqlite:///{DB_PATH}"
engine: Engine = create_engine(DATABASE_URL, future=True)


def _transform_sql_server_to_sqlite(statement: str) -> str:
    stmt = statement
    stmt = re.sub(r"\s+", " ", stmt).strip()
    stmt = re.sub(r"^CREATE TABLE\s+", "CREATE TABLE IF NOT EXISTS ", stmt, flags=re.IGNORECASE)
    stmt = stmt.replace("DATETIME", "TEXT")
    stmt = stmt.replace("BIT", "INTEGER")
    stmt = re.sub(r"VARCHAR\(\d+\)", "TEXT", stmt, flags=re.IGNORECASE)
    stmt = re.sub(
        r"\bid\s+INT\s+IDENTITY\s*\(\s*1\s*,\s*1\s*\)\s+PRIMARY\s+KEY",
        "id INTEGER PRIMARY KEY AUTOINCREMENT",
        stmt,
        flags=re.IGNORECASE,
    )
    stmt = re.sub(r"\bINT\b", "INTEGER", stmt, flags=re.IGNORECASE)
    stmt = stmt.replace("GETDATE()", "CURRENT_TIMESTAMP")
    return stmt


def _extract_table_statements(raw_sql: str) -> list[str]:
    statements = re.findall(r"CREATE TABLE\s+.*?\);", raw_sql, flags=re.IGNORECASE | re.DOTALL)
    return [_transform_sql_server_to_sqlite(stmt) for stmt in statements]


def _seed_demo_data() -> None:
    with engine.begin() as conn:
        has_users = conn.execute(text("SELECT COUNT(*) FROM usuarios")).scalar_one()
        if has_users:
            return

        conn.execute(
            text(
                """
                INSERT INTO roles (nombre, descripcion) VALUES
                ('Administrador', 'Control completo'),
                ('Capturista', 'Registro de activos');
                """
            )
        )

        conn.execute(
            text(
                """
                INSERT INTO usuarios (nombre, apellido, correo, contrasena, telefono, estatus_usuario, id_rol)
                VALUES
                ('Gael', 'Martinez', 'admin@actiscan.com', '123456', '5550001111', 'Activo', 1),
                ('Ana', 'Lopez', 'capturista@actiscan.com', '123456', '5550002222', 'Activo', 2);
                """
            )
        )

        conn.execute(
            text(
                """
                INSERT INTO categoria_activos (nombre, descripcion, estatus_categoria) VALUES
                ('Computadora', 'Equipos de computo portatiles y de escritorio', 'Activa'),
                ('Servidor', 'Servidores y equipos de almacenamiento', 'Activa'),
                ('Red', 'Equipos de networking y comunicaciones', 'Activa');
                """
            )
        )

        conn.execute(
            text(
                """
                INSERT INTO estado_activos (nombre, descripcion) VALUES
                ('Operacional', 'Activo en uso'),
                ('Mantenimiento', 'En revision');
                """
            )
        )

        conn.execute(
            text(
                """
                INSERT INTO ubicaciones (nombre, descripcion) VALUES
                ('A-207', 'Edificio A'),
                ('B-207', 'Edificio B'),
                ('C-207', 'Edificio C');
                """
            )
        )

        conn.execute(
            text(
                """
                INSERT INTO activos
                    (codigo_interno, nombre, descripcion, marca, modelo, numero_serie, id_categoria, id_estado_activo, id_ubicacion, id_usuario)
                VALUES
                    ('AST0001', 'Laptop Asus ROG', 'Laptop de desarrollo', 'ASUS', 'ROG 15', 'SN-99283', 1, 1, 3, 2),
                    ('AST0002', 'Laptop Dell XPS', 'Laptop de analisis', 'Dell', 'XPS 13', 'SN-22334', 1, 1, 2, 2),
                    ('AST0003', 'Laptop MSI', 'Laptop de soporte', 'MSI', 'Modern 14', 'SN-11221', 1, 2, 1, 2);
                """
            )
        )


def init_db() -> None:
    DB_PATH.parent.mkdir(parents=True, exist_ok=True)

    raw_sql = SQL_DUMP_PATH.read_text(encoding="utf-8")
    table_statements = _extract_table_statements(raw_sql)

    with engine.begin() as conn:
        conn.execute(text("PRAGMA foreign_keys = ON"))
        for stmt in table_statements:
            conn.execute(text(stmt))

    _seed_demo_data()
