@extends('layouts.capturist')

@section('title', 'Dashboard')
@section('nav_dashboard', 'active')

@section('content')
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Vista general del inventario y accesos r&aacute;pidos para el capturista.</p>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-title">Total de activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-circle-info"></i></div>
            <div class="stat-title">Activos con observaciones</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-qrcode"></i></div>
            <div class="stat-title">Etiquetas listas para imprimir</div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="{{ route('capturist.assets.create') }}">+ Activo</a>
        <a href="{{ route('capturist.categories.create') }}">+ Categoria</a>
        <a href="{{ route('capturist.assets.qr') }}">+ QR</a>
    </div>

    <div class="table-card">
        <div class="table-tools">
            <input class="auth-input" type="text" placeholder="Buscar producto..." style="margin: 0;">
            <select class="auth-select">
                <option>Categoria</option>
            </select>
            <select class="auth-select">
                <option>Todo</option>
            </select>
            <button class="btn-primary" type="button">Aplicar filtro</button>
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
            <tbody>
                <tr>
                    <td>DR-030</td>
                    <td>02/10/2025</td>
                    <td>Enviado</td>
                    <td>5</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.assets.show') }}" title="Ver activo"><i class="fa-regular fa-eye"></i></a>
                        <a href="{{ route('capturist.assets.qr') }}" title="Generar QR"><i class="fa-solid fa-qrcode"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>DR-029</td>
                    <td>28/09/2025</td>
                    <td>Surtido</td>
                    <td>3</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.assets') }}" title="Ver listado"><i class="fa-regular fa-eye"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>DR-028</td>
                    <td>25/09/2025</td>
                    <td>Recibido</td>
                    <td>4</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.assets') }}" title="Ver listado"><i class="fa-regular fa-eye"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
