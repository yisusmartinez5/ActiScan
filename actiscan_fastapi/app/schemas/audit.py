from pydantic import BaseModel, Field


class AuditCreateRequest(BaseModel):
    id_activo: int
    tipo: str = Field(min_length=2, max_length=50)
    descripcion: str | None = Field(default=None, max_length=500)
    resultado: str = Field(min_length=2, max_length=100)
