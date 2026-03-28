@extends('layouts.capturist')

@section('title', 'Nuevo Activo')
@section('page', 'create-asset')
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

            <form id="createAssetForm">
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
                            <option selected disabled value="">Selecciona una marca</option>
                            <option value="ASUS">ASUS</option>
                            <option value="Dell">Dell</option>
                            <option value="HP">HP</option>
                            <option value="Lenovo">Lenovo</option>
                            <option value="Acer">Acer</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-block" for="asset_category">Categoria</label>
                        <select class="auth-select" id="asset_category" required>
                            <option selected disabled value="">Selecciona una categoria</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label-block" for="asset_status">Estado</label>
                        <select class="auth-select" id="asset_status" required>
                            <option selected disabled value="">Selecciona un estado</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label class="form-label-block" for="asset_location">Ubicacion</label>
                        <select class="auth-select" id="asset_location" required>
                            <option selected disabled value="">Selecciona una ubicacion</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="soft-button" type="submit">Agregar activo</button>
                </div>
                <p id="createAssetMessage" class="page-subtitle" style="margin-top: 12px; margin-bottom: 0;"></p>
            </form>
        </div>
    </div>
@endsection
