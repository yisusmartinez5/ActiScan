from typing import Any

from fastapi import APIRouter, Depends, status
from sqlalchemy.engine import Connection

from app.schemas.user import UserCreate, UserResponse, UserUpdate
from app.services.user_service import create_user, deactivate_user, get_user, list_users, update_user
from db import get_connection

router = APIRouter(prefix="/admin/users", tags=["users"])


@router.get("", response_model=list[UserResponse])
def get_users(conn: Connection = Depends(get_connection)) -> list[dict[str, Any]]:
    return list_users(conn)


@router.get("/{user_id}", response_model=UserResponse)
def get_user_by_id(user_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return get_user(conn, user_id)


@router.post("", response_model=UserResponse, status_code=status.HTTP_201_CREATED)
def post_user(payload: UserCreate, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return create_user(
        conn=conn,
        nombre=payload.nombre,
        email=str(payload.email),
        rol=payload.rol,
        password=payload.password,
    )


@router.put("/{user_id}", response_model=UserResponse)
def put_user(user_id: int, payload: UserUpdate, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return update_user(
        conn=conn,
        user_id=user_id,
        nombre=payload.nombre,
        email=str(payload.email),
        rol=payload.rol,
        password=payload.password,
    )


@router.delete("/{user_id}")
def delete_user(user_id: int, conn: Connection = Depends(get_connection)) -> dict[str, str]:
    deactivate_user(conn, user_id)
    return {"detail": "Usuario desactivado"}
