@extends('layouts.app')

@section('content')
<div class="dashboard-page">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="{{ route('capturist.dashboard') }}" class="sidebar-btn">Dashboard</a>
            <a href="{{ route('capturist.assets') }}" class="sidebar-btn active">Activos</a>
            <a href="{{ route('capturist.categories') }}" class="sidebar-btn">Categoría Activos</a>
            <a href="{{ route('login') }}" class="sidebar-btn">Cerrar sesión</a>
        </div>
    </aside>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="header-center">
            <div class="brand-box">
                <img src="{{ asset('img/favicon-actiscan.png') }}" alt="ActiScan icon" class="brand-icon">
                <div class="brand-text">
                    <h1>ActiScan</h1>
                    <p>Gestion de Activos</p>
                </div>
            </div>
        </div>

        <div class="header-right">
            <div class="user-pill">
                <div class="user-avatar">GM</div>
                <span class="user-name">Gael Jesus Martinez</span>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="dashboard-main assets-main">
        <div class="assets-page-container">

            <div class="assets-topbar">
                <div>
                    <h2 class="assets-title">Inventario de Activos</h2>
                    <p class="assets-subtitle">Control total de la infraestructura física.</p>
                </div>

                <div>
                    <a href="{{ route('capturist.assets.create') }}" class="new-asset-btn">
                        <span class="new-asset-plus">+</span>
                        <span>NUEVO ACTIVO</span>
                    </a>
                </div>
            </div>

            <section class="assets-card">
                <div class="assets-toolbar">
                    <div class="assets-search-box">
                        <span class="assets-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.398 1.398h-.001l3.85 3.85.707-.707-3.85-3.85zm-5.242.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                            </svg>
                        </span>
                        <input type="text" placeholder="Filtrar por nombre, serie o IP...">
                    </div>

                    <button type="button" class="assets-advanced-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 10.117V16l4-2v-3.883l4.447-5.34A1 1 0 0 0 13.68 3H2.32a1 1 0 0 0-.768 1.777L6 10.117zM2.32 4h11.36L9 9.617V13.5l-2 1V9.617L2.32 4z"/>
                        </svg>
                        FILTROS AVANZADOS
                    </button>
                </div>

                <div class="assets-table-wrapper">
                    <table class="assets-table">
                        <thead>
                            <tr>
                                <th>ACTIVO</th>
                                <th>CATEGORÍA</th>
                                <th>ESTADO</th>
                                <th>UBICACIÓN</th>
                                <th>OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="asset-info-cell">
                                    <div class="asset-info">
                                        <div class="asset-icon-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 3h10a1 1 0 0 1 1 1v6H2V4a1 1 0 0 1 1-1zm0-1a2 2 0 0 0-2 2v7h14V4a2 2 0 0 0-2-2H3z"/>
                                                <path d="M0 12h16v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-1z"/>
                                            </svg>
                                        </div>
                                        <div class="asset-text">
                                            <h4>Laptop Asus ROG</h4>
                                            <p>AST0001</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="asset-category-badge">Computadora</span>
                                </td>
                                <td>
                                    <span class="asset-status operational">
                                        <span class="status-dot"></span>
                                        OPERACIONAL
                                    </span>
                                </td>
                                <td class="asset-location">C-207</td>
                                <td>
                                    <a href="{{ route('capturist.assets.show') }}" class="asset-view-btn" title="Ver activo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td class="asset-info-cell">
                                    <div class="asset-info">
                                        <div class="asset-icon-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 3h10a1 1 0 0 1 1 1v6H2V4a1 1 0 0 1 1-1zm0-1a2 2 0 0 0-2 2v7h14V4a2 2 0 0 0-2-2H3z"/>
                                                <path d="M0 12h16v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-1z"/>
                                            </svg>
                                        </div>
                                        <div class="asset-text">
                                            <h4>Laptop Dell XPS</h4>
                                            <p>AST0002</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="asset-category-badge">Computadora</span>
                                </td>
                                <td>
                                    <span class="asset-status operational">
                                        <span class="status-dot"></span>
                                        OPERACIONAL
                                    </span>
                                </td>
                                <td class="asset-location">B-207</td>
                                <td>
                                    <a href="#" class="asset-view-btn" title="Ver activo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td class="asset-info-cell">
                                    <div class="asset-info">
                                        <div class="asset-icon-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 3h10a1 1 0 0 1 1 1v6H2V4a1 1 0 0 1 1-1zm0-1a2 2 0 0 0-2 2v7h14V4a2 2 0 0 0-2-2H3z"/>
                                                <path d="M0 12h16v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-1z"/>
                                            </svg>
                                        </div>
                                        <div class="asset-text">
                                            <h4>Laptop MSI</h4>
                                            <p>AST0003</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="asset-category-badge">Computadora</span>
                                </td>
                                <td>
                                    <span class="asset-status maintenance">
                                        <span class="status-dot"></span>
                                        MANTENIMIENTO
                                    </span>
                                </td>
                                <td class="asset-location">A-207</td>
                                <td>
                                    <a href="#" class="asset-view-btn" title="Ver activo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (menuToggle && sidebar && overlay) {
            menuToggle.addEventListener('click', function () {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            });

            overlay.addEventListener('click', function () {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        }
    });
</script>
@endsection