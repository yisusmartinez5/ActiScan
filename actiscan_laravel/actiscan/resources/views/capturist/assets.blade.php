@extends('layouts.capturist')

@section('title', 'Inventario de Activos')
@section('page', 'assets')
@section('nav_assets', 'active')

@section('content')
    <div class="page-toolbar">
        <div>
            <h1 class="page-title">Inventario de Activos</h1>
            <p class="page-subtitle">Control total de la infraestructura f&iacute;sica.</p>
        </div>

        <a class="soft-button" href="{{ route('capturist.assets.create') }}">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo activo</span>
        </a>
    </div>

    <div class="table-card">
        <div class="table-tools compact">
            <input class="auth-input" id="assetsSearchInput" type="text" placeholder="Filtrar por nombre, serie o IP..." style="margin: 0;">
            <button class="filter-btn" id="assetsSearchButton" type="button">Aplicar filtro</button>
        </div>

        <table class="simple-table">
            <thead>
                <tr>
                    <th>Activo</th>
                    <th>Categoria</th>
                    <th>Estado</th>
                    <th>Ubicacion</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody id="assetsTableBody"></tbody>
        </table>
    </div>
@endsection
