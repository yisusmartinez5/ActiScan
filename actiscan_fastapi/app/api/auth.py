from typing import Any

from fastapi import APIRouter, Depends
from sqlalchemy.engine import Connection

from app.schemas.auth import LoginRequest
from app.services.auth_service import login_user
from db import get_connection

router = APIRouter(prefix="/auth", tags=["auth"])


@router.post("/login")
def login(payload: LoginRequest, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return login_user(conn=conn, email=str(payload.email), password=payload.password)
