@extends('layouts.capturist')

@section('title', 'Dashboard')
@section('page', 'dashboard')
@section('nav_dashboard', 'active')

@section('content')
    <div class="page-toolbar">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Vista general del inventario y accesos r&aacute;pidos para el capturista.</p>
        </div>
        <div class="dropdown-wrapper">
            <button class="soft-button" id="createMenuBtn" type="button">
                <i class="fa-solid fa-plus"></i>
                <span>Crear</span>
            </button>
            <div class="dropdown-menu-panel" id="createMenuDropdown">
                <a href="{{ route('capturist.assets.create') }}"><i class="fa-solid fa-box"></i> Activo</a>
                <a href="{{ route('capturist.categories.create') }}"><i class="fa-solid fa-layer-group"></i> Categoria</a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-title"><span id="dashboardTotalAssets">0</span> Total de activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-circle-info"></i></div>
            <div class="stat-title"><span id="dashboardAssetsObs">0</span> Activos con observaciones</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-tags"></i></div>
            <div class="stat-title"><span id="dashboardCategories">0</span> Categorias activas</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-tools">
            <input class="auth-input" id="dashboardSearchInput" type="text" placeholder="Buscar activo..." style="margin: 0;">
            <select class="auth-select" id="dashboardCategoryFilter">
                <option value="">Categoria</option>
            </select>
            <select class="auth-select" id="dashboardStatusFilter">
                <option value="">Estado</option>
            </select>
            <button class="btn-primary" type="button" id="dashboardApplyFilter">Aplicar filtro</button>
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
            <tbody id="dashboardAssetsTableBody"></tbody>
        </table>
    </div>
@endsection
