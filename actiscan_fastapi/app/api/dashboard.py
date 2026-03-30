from typing import Any

from fastapi import APIRouter, Depends
from sqlalchemy.engine import Connection

from app.services.dashboard_service import get_dashboard_summary, get_lookups
from db import get_connection

router = APIRouter(tags=["dashboard"])


@router.get("/dashboard/summary")
def dashboard_summary(conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return get_dashboard_summary(conn=conn)


@router.get("/lookups")
def lookups(conn: Connection = Depends(get_connection)) -> dict[str, list[dict[str, Any]]]:
    return get_lookups(conn=conn)
