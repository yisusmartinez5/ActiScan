@extends('layouts.capturist')

@section('title', 'Nueva Categoria')
@section('page', 'create-category')
@section('nav_categories', 'active')

@section('content')
    <h1 class="page-title">Nueva Categoria</h1>
    <p class="page-subtitle">Crear una nueva categoria de activos fijos.</p>

    <div class="content-card">
        <form id="createCategoryForm">
            <div class="form-grid">
                <div>
                    <label class="form-label-block" for="category_name">Nombre de la categoria</label>
                    <input class="auth-input" id="category_name" type="text" placeholder="Computadora">
                </div>

                <div>
                    <label class="form-label-block" for="category_description">Descripcion</label>
                    <input class="auth-input" id="category_description" type="text" placeholder="Equipos de computo portatiles y de escritorio">
                </div>

                <div class="full-width">
                    <h2 class="section-title">Seleccionar icono</h2>
                    <div class="selection-grid" data-toggle-group="category-icon">
                        <button class="icon-option active" type="button" data-toggle-option><i class="fa-solid fa-laptop"></i><span>Laptop</span></button>
                        <button class="icon-option" type="button" data-toggle-option><i class="fa-solid fa-desktop"></i><span>Monitor</span></button>
                        <button class="icon-option" type="button" data-toggle-option><i class="fa-solid fa-print"></i><span>Impresora</span></button>
                        <button class="icon-option" type="button" data-toggle-option><i class="fa-solid fa-mobile-screen"></i><span>Movil</span></button>
                        <button class="icon-option" type="button" data-toggle-option><i class="fa-solid fa-server"></i><span>Servidor</span></button>
                        <button class="icon-option" type="button" data-toggle-option><i class="fa-solid fa-network-wired"></i><span>Red</span></button>
                    </div>
                </div>

                <div>
                    <h2 class="section-title">Seleccionar color</h2>
                    <div class="color-palette" data-toggle-group="category-color">
                        <button class="color-option active" type="button" data-toggle-option style="background-color: #4a90e2;"></button>
                        <button class="color-option" type="button" data-toggle-option style="background-color: #7b68ee;"></button>
                        <button class="color-option" type="button" data-toggle-option style="background-color: #ff6b6b;"></button>
                        <button class="color-option" type="button" data-toggle-option style="background-color: #57d163;"></button>
                        <button class="color-option" type="button" data-toggle-option style="background-color: #f4cd3d;"></button>
                        <button class="color-option" type="button" data-toggle-option style="background-color: #28c7a3;"></button>
                    </div>
                </div>

                <div>
                    <h2 class="section-title">Vista previa</h2>
                    <div class="preview-inline">
                        <div class="preview-icon-box">
                            <i class="fa-solid fa-laptop"></i>
                        </div>
                        <div class="preview-text">
                            <h4>Nombre de la categoria</h4>
                            <p>Descripcion de la categoria</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a class="ghost-button" href="{{ route('capturist.categories') }}">Cancelar</a>
                <button class="soft-button" type="submit">Crear categoria</button>
            </div>
            <p id="createCategoryMessage" class="page-subtitle" style="margin-top: 12px; margin-bottom: 0;"></p>
        </form>
    </div>
@endsection
