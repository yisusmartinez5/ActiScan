@extends('layouts.capturist')

@section('title', 'Dashboard')
@section('page', 'dashboard')
@section('nav_dashboard', 'active')

@section('content')
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Vista general del inventario y accesos r&aacute;pidos para el capturista.</p>

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
            <div class="stat-icon"><i class="fa-solid fa-qrcode"></i></div>
            <div class="stat-title"><span id="dashboardCategories">0</span> Categorias activas</div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="{{ route('capturist.assets.create') }}">+ Activo</a>
        <a href="{{ route('capturist.categories.create') }}">+ Categoria</a>
        <a href="{{ route('capturist.assets.qr') }}">+ QR</a>
    </div>

    <div class="table-card">
        <div class="table-tools">
            <input class="auth-input" id="dashboardSearchInput" type="text" placeholder="Buscar producto..." style="margin: 0;">
            <select class="auth-select" id="dashboardCategoryFilter">
                <option>Categoria</option>
            </select>
            <select class="auth-select" id="dashboardStatusFilter">
                <option>Todo</option>
            </select>
            <button class="btn-primary" type="button" id="dashboardApplyFilter">Aplicar filtro</button>
        </div>

        <table class="simple-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Articulos</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody id="dashboardAssetsTableBody"></tbody>
        </table>
    </div>
@endsection
