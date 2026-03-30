from typing import Any

from fastapi import APIRouter, Depends
from sqlalchemy.engine import Connection

from app.schemas.category import CategoryCreateRequest
from app.services.category_service import create_category, get_category, list_categories, update_category
from db import get_connection

router = APIRouter(prefix="/categories", tags=["categories"])


@router.get("")
def get_categories(conn: Connection = Depends(get_connection)) -> list[dict[str, Any]]:
    return list_categories(conn=conn)


@router.get("/{category_id}")
def get_category_by_id(category_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return get_category(conn=conn, category_id=category_id)


@router.post("")
def post_category(payload: CategoryCreateRequest, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return create_category(
        conn=conn,
        nombre=payload.nombre,
        descripcion=payload.descripcion,
        icono=payload.icono,
        color=payload.color,
    )


@router.put("/{category_id}")
def put_category(
    category_id: int,
    payload: CategoryCreateRequest,
    conn: Connection = Depends(get_connection),
) -> dict[str, Any]:
    return update_category(
        conn=conn,
        category_id=category_id,
        nombre=payload.nombre,
        descripcion=payload.descripcion,
        icono=payload.icono,
        color=payload.color,
    )
