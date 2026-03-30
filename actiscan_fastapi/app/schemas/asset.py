from pydantic import BaseModel, Field


class AssetCreateRequest(BaseModel):
    nombre: str = Field(min_length=2, max_length=100)
    marca: str | None = Field(default=None, max_length=100)
    modelo: str | None = Field(default=None, max_length=100)
    numero_serie: str | None = Field(default=None, max_length=100)
    codigo_interno: str | None = Field(default=None, max_length=50)
    descripcion: str | None = Field(default=None, max_length=150)
    id_categoria: int
    id_estado_activo: int
    id_ubicacion: int
    id_usuario: int | None = None
    foto_base64: str | None = None
