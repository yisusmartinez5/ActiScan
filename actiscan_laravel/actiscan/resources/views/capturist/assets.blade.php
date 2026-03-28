@extends('layouts.capturist')

@section('title', 'Inventario de Activos')
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
            <input class="auth-input" type="text" placeholder="Filtrar por nombre, serie o IP..." style="margin: 0;">
            <button class="filter-btn" type="button">Filtros avanzados</button>
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
            <tbody>
                <tr>
                    <td>
                        <div class="asset-name">Laptop Asus ROG</div>
                        <div class="asset-code">AST0001</div>
                    </td>
                    <td><span class="badge-soft">Computadora</span></td>
                    <td>
                        <span class="status-text success">
                            <span class="status-dot"></span>
                            OPERACIONAL
                        </span>
                    </td>
                    <td>C-207</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.assets.show') }}" title="Ver activo"><i class="fa-regular fa-eye"></i></a>
                        <a href="{{ route('capturist.assets.qr') }}" title="QR"><i class="fa-solid fa-qrcode"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="asset-name">Laptop Dell XPS</div>
                        <div class="asset-code">AST0002</div>
                    </td>
                    <td><span class="badge-soft">Computadora</span></td>
                    <td>
                        <span class="status-text success">
                            <span class="status-dot"></span>
                            OPERACIONAL
                        </span>
                    </td>
                    <td>B-207</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.assets.show') }}" title="Ver activo"><i class="fa-regular fa-eye"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="asset-name">Laptop MSI</div>
                        <div class="asset-code">AST0003</div>
                    </td>
                    <td><span class="badge-soft">Computadora</span></td>
                    <td>
                        <span class="status-text warning">
                            <span class="status-dot"></span>
                            MANTENIMIENTO
                        </span>
                    </td>
                    <td>A-207</td>
                    <td class="option-icons">
                        <a href="{{ route('capturist.assets.show') }}" title="Ver activo"><i class="fa-regular fa-eye"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
