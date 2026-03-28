@extends('layouts.capturist')

@section('title', 'Nuevo Activo')
@section('nav_assets', 'active')

@section('content')
    <div class="page-toolbar">
        <div>
            <h1 class="page-title">Nuevo activo</h1>
            <p class="page-subtitle">Registrar un nuevo elemento en el inventario conservando el flujo actual.</p>
        </div>

        <a class="ghost-button" href="{{ route('capturist.assets') }}">Volver</a>
    </div>

    <div class="content-card">
        <div class="split-card">
            <button class="photo-box" type="button">
                <i class="fa-regular fa-image"></i>
                <strong>Agregar foto</strong>
                <span>Selecciona una imagen de referencia para el activo.</span>
            </button>

            <form>
                <div class="form-grid">
                    <div class="full-width">
                        <label class="form-label-block" for="asset_name">Nombre</label>
                        <input class="auth-input" id="asset_name" type="text">
                    </div>

                    <div>
                        <label class="form-label-block" for="asset_serial">No. serial</label>
                        <input class="auth-input" id="asset_serial" type="text">
                    </div>

                    <div>
                        <label class="form-label-block" for="asset_brand">Marca</label>
                        <select class="auth-select" id="asset_brand">
                            <option selected disabled>Selecciona una marca</option>
                            <option>ASUS</option>
                            <option>Dell</option>
                            <option>HP</option>
                            <option>Lenovo</option>
                            <option>Acer</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-block" for="asset_category">Categoria</label>
                        <select class="auth-select" id="asset_category">
                            <option selected disabled>Selecciona una categoria</option>
                            <option>Computadora</option>
                            <option>Servidor</option>
                            <option>Red</option>
                            <option>Movil</option>
                            <option>Impresora</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-block" for="asset_status">Estado</label>
                        <select class="auth-select" id="asset_status">
                            <option selected disabled>Selecciona un estado</option>
                            <option>Operacional</option>
                            <option>Mantenimiento</option>
                            <option>Baja</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label class="form-label-block" for="asset_location">Ubicacion</label>
                        <input class="auth-input" id="asset_location" type="text">
                    </div>
                </div>

                <div class="form-actions">
                    <a class="soft-button" href="{{ route('capturist.assets') }}">Agregar activo</a>
                </div>
            </form>
        </div>
    </div>
@endsection
