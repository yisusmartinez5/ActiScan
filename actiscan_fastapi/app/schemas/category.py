from pydantic import BaseModel, Field


class CategoryCreateRequest(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    descripcion: str | None = Field(default=None, max_length=150)
    icono: str | None = Field(default=None, max_length=100)
    color: str | None = Field(default=None, max_length=20)
