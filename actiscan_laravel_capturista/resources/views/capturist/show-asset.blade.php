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
                    <p>Gestión de Activos</p>
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
    <main class="dashboard-main asset-detail-main">
        <div class="asset-detail-container">

            <div class="asset-detail-topbar">
                <h2 class="asset-detail-title">Detalles del activo</h2>

                <a href="{{ route('capturist.assets') }}" class="asset-back-btn">Volver</a>
            </div>

            <section class="asset-detail-card">
                <div class="row g-4 align-items-stretch">

                    <!-- Info izquierda -->
                    <div class="col-lg-6">
                        <div class="asset-detail-info">
                            <div class="asset-detail-list">
                                <p><strong>Nombre:</strong> Laptop Asus ROG</p>
                                <p><strong>No. Serial:</strong> AST0001</p>
                                <p><strong>Marca:</strong> ASUS</p>
                                <p><strong>Categoría:</strong> Computadora</p>
                                <p><strong>Estado:</strong> Operacional</p>
                                <p><strong>Ubicación:</strong> B-207</p>
                            </div>

                            <div class="asset-detail-actions">
                                <a href="{{ route('capturist.assets.qr') }}" class="generate-qr-btn">
                                    <span class="qr-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2 2h3v3H2V2zm1 1v1h1V3H3zM2 11h3v3H2v-3zm1 1v1h1v-1H3zM11 2h3v3h-3V2zm1 1v1h1V3h-1z"/>
                                            <path d="M6 0h1v1H6V0zm2 0h1v1H8V0zm2 0h1v1h-1V0zM0 6h1v1H0V6zm0 2h1v1H0V8zm0 2h1v1H0v-1zm15-4h1v1h-1V6zm0 2h1v1h-1V8zm0 2h1v1h-1v-1zM6 15h1v1H6v-1zm2 0h1v1H8v-1zm2 0h1v1h-1v-1z"/>
                                            <path d="M6 2h1v1H6V2zm1 1h1v1H7V3zm-1 1h1v1H6V4zm4 2h1v1h-1V6zm1 1h1v1h-1V7zm-1 1h1v1h-1V8zm-4 2h1v1H6v-1zm1 1h1v1H7v-1zm1-1h1v1H8v-1zm3 1h1v1h-1v-1zm1 1h1v1h-1v-1zm-2 1h1v1h-1v-1z"/>
                                        </svg>
                                    </span>
                                    <span>Generar QR</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen derecha -->
                    <div class="col-lg-6">
                        <div class="asset-image-panel">
                            <div class="asset-image-frame">
                                <img src="{{ asset('img/qr-code-icon.png') }}" alt="Laptop Asus ROG" class="asset-detail-image">
                            </div>
                        </div>
                    </div>

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