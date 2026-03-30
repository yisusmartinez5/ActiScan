from typing import Any

from fastapi import APIRouter, Depends, Query
from fastapi.responses import Response
from sqlalchemy.engine import Connection

from app.schemas.asset import AssetCreateRequest
from app.services.asset_service import create_asset, create_qr, delete_asset, get_asset, get_latest_qr, get_latest_qr_png, list_assets, update_asset
from db import get_connection

router = APIRouter(prefix="/assets", tags=["assets"])


@router.get("")
def get_assets(
    q: str | None = Query(default=None),
    category_id: int | None = Query(default=None),
    status_id: int | None = Query(default=None),
    conn: Connection = Depends(get_connection),
) -> list[dict[str, Any]]:
    return list_assets(conn=conn, q=q, category_id=category_id, status_id=status_id)


@router.get("/{asset_id}")
def get_asset_by_id(asset_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return get_asset(conn=conn, asset_id=asset_id)


@router.post("")
def post_asset(payload: AssetCreateRequest, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return create_asset(conn=conn, payload=payload.model_dump())


@router.put("/{asset_id}")
def put_asset(
    asset_id: int,
    payload: AssetCreateRequest,
    conn: Connection = Depends(get_connection),
) -> dict[str, Any]:
    return update_asset(conn=conn, asset_id=asset_id, payload=payload.model_dump())


@router.delete("/{asset_id}")
def remove_asset(asset_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return delete_asset(conn=conn, asset_id=asset_id)


@router.post("/{asset_id}/qr")
def post_asset_qr(asset_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return create_qr(conn=conn, asset_id=asset_id)


@router.get("/{asset_id}/qr/latest")
def get_asset_qr_latest(asset_id: int, conn: Connection = Depends(get_connection)) -> dict[str, Any]:
    return get_latest_qr(conn=conn, asset_id=asset_id)


@router.get("/{asset_id}/qr/latest/image")
def get_asset_qr_latest_image(asset_id: int, conn: Connection = Depends(get_connection)) -> Response:
    png_bytes = get_latest_qr_png(conn=conn, asset_id=asset_id)
    return Response(content=png_bytes, media_type="image/png")
