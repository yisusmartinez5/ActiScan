from pydantic import BaseModel, EmailStr, Field


class UserBase(BaseModel):
    nombre: str = Field(min_length=1, max_length=120)
    email: EmailStr
    rol: str = Field(default="capturista", min_length=3, max_length=30)


class UserCreate(UserBase):
    password: str = Field(min_length=1, max_length=255)


class UserUpdate(UserBase):
    password: str | None = Field(default=None, min_length=1, max_length=255)


class UserResponse(BaseModel):
    id: int
    nombre: str
    email: EmailStr
    rol: str
    estado: bool
