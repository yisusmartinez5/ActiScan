from typing import Any

from fastapi import APIRouter, Depends
from sqlalchemy.engine import Connection

from app.schemas.audit import AuditCreateRequest
from app.services.audit_service import create_audit, delete_audit, list_audits
from db import get_connection

router = APIRouter(prefix="/audits", tags=["audits"])


@router.get("")
def get_audits(conn: Connection = Depends(get_connection)) -> list[dict[str, Any]]:
    return list_audits(conn=conn)


@router.post("")
def post_audit(payload: AuditCreateRequest, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return create_audit(
        conn=conn,
        id_activo=payload.id_activo,
        tipo=payload.tipo,
        descripcion=payload.descripcion,
        resultado=payload.resultado,
    )


@router.delete("/{audit_id}")
def remove_audit(audit_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return delete_audit(conn=conn, audit_id=audit_id)
