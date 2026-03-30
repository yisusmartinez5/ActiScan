from __future__ import annotations

import os
import re
from collections.abc import Iterator
from pathlib import Path

from sqlalchemy import create_engine, text
from sqlalchemy.engine import Connection
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


def get_connection() -> Iterator[Connection]:
    with engine.begin() as conn:
        conn.execute(text("PRAGMA foreign_keys = ON"))
        yield conn


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


def _ensure_runtime_extensions() -> None:
    with engine.begin() as conn:
        asset_columns = {
            row["name"] for row in conn.execute(text("PRAGMA table_info(activos)")).mappings().all()
        }
        if "foto_base64" not in asset_columns:
            conn.execute(text("ALTER TABLE activos ADD COLUMN foto_base64 TEXT"))

        category_columns = {
            row["name"] for row in conn.execute(text("PRAGMA table_info(categoria_activos)")).mappings().all()
        }
        if "icono" not in category_columns:
            conn.execute(text("ALTER TABLE categoria_activos ADD COLUMN icono TEXT"))
        if "color" not in category_columns:
            conn.execute(text("ALTER TABLE categoria_activos ADD COLUMN color TEXT"))

        conn.execute(
            text(
                """
                UPDATE categoria_activos
                SET icono = COALESCE(icono, 'fa-solid fa-layer-group'),
                    color = COALESCE(color, '#4a90e2')
                """
            )
        )

        conn.execute(
            text(
                """
                CREATE TABLE IF NOT EXISTS auditorias (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    id_activo INTEGER NOT NULL,
                    tipo TEXT NOT NULL,
                    descripcion TEXT,
                    resultado TEXT NOT NULL,
                    fecha_auditoria TEXT DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY(id_activo) REFERENCES activos(id)
                )
                """
            )
        )


def _seed_demo_data() -> None:
    with engine.begin() as conn:
        has_roles = conn.execute(text("SELECT COUNT(*) FROM roles")).scalar_one()
        if not has_roles:
            conn.execute(
                text(
                    """
                    INSERT INTO roles (nombre, descripcion) VALUES
                    ('Administrador', 'Control completo'),
                    ('Capturista', 'Registro de activos');
                    """
                )
            )

        has_permisos = conn.execute(text("SELECT COUNT(*) FROM permisos")).scalar_one()
        if not has_permisos:
            conn.execute(
                text(
                    """
                    INSERT INTO permisos (codigo, descripcion) VALUES
                    ('ACTIVOS_VER', 'Permite consultar activos'),
                    ('ACTIVOS_CREAR', 'Permite registrar activos'),
                    ('USUARIOS_GESTION', 'Permite administrar usuarios');
                    """
                )
            )

        has_rol_permisos = conn.execute(text("SELECT COUNT(*) FROM rol_permisos")).scalar_one()
        if not has_rol_permisos:
            conn.execute(
                text(
                    """
                    INSERT INTO rol_permisos (id_rol, id_permiso) VALUES
                    (1, 1), (1, 2), (1, 3),
                    (2, 1), (2, 2);
                    """
                )
            )

        has_users = conn.execute(text("SELECT COUNT(*) FROM usuarios")).scalar_one()
        if has_users:
            has_movement_types = conn.execute(text("SELECT COUNT(*) FROM tipo_movimientos")).scalar_one()
            if not has_movement_types:
                conn.execute(
                    text(
                        """
                        INSERT INTO tipo_movimientos (nombre, descripcion) VALUES
                        ('Alta', 'Registro inicial del activo'),
                        ('Cambio de ubicacion', 'Traslado interno de activo'),
                        ('Baja', 'Retiro o desincorporacion de activo');
                        """
                    )
                )
            return

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

        conn.execute(
            text(
                """
                INSERT INTO tipo_movimientos (nombre, descripcion) VALUES
                ('Alta', 'Registro inicial del activo'),
                ('Cambio de ubicacion', 'Traslado interno de activo'),
                ('Baja', 'Retiro o desincorporacion de activo');
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

    _ensure_runtime_extensions()

    _seed_demo_data()
