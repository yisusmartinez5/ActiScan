from fastapi import APIRouter

from app.api.assets import router as assets_router
from app.api.audits import router as audits_router
from app.api.auth import router as auth_router
from app.api.categories import router as categories_router
from app.api.dashboard import router as dashboard_router
from app.api.health import router as health_router
from app.api.users import router as users_router

api_router = APIRouter(prefix="/api")
api_v1_router = APIRouter(prefix="/api/v1")
root_router = APIRouter()

for router in (auth_router, dashboard_router, categories_router, assets_router, audits_router, users_router):
	api_router.include_router(router)
	api_v1_router.include_router(router)

root_router.include_router(health_router)
root_router.include_router(api_router)
root_router.include_router(api_v1_router)
