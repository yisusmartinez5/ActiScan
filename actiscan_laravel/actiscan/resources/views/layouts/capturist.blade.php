@extends('layouts.app')

@section('body_class', 'admin-body')

@section('app_content')
    <div class="admin-layout">
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav">
                <a href="{{ route('capturist.dashboard') }}" class="sidebar-link @yield('nav_dashboard')">Dashboard</a>
                <a href="{{ route('capturist.assets') }}" class="sidebar-link @yield('nav_assets')">Activos</a>
                <a href="{{ route('capturist.categories') }}" class="sidebar-link @yield('nav_categories')">Categoria Activos</a>

                <div class="sidebar-bottom">
                    <a href="{{ route('login') }}" class="logout-link">Cerrar sesion</a>
                </div>
            </nav>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <section class="main-panel">
            <header class="topbar">
                <button class="menu-icon" id="menuToggle" type="button" aria-label="Abrir menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="brand">
                    <div class="brand-logo">
                        <div class="mini-box">A</div>
                        <div class="mini-box">+</div>
                        <div class="mini-box">S</div>
                        <div class="mini-box"><i class="fa-solid fa-magnifying-glass"></i></div>
                    </div>
                    <div class="brand-text">
                        <h1>ActiScan</h1>
                        <p>Gestion de Activos</p>
                    </div>
                </div>

                <div class="user-pill">
                    <span class="user-badge">GM</span>
                    <span>Gael Jesus Martinez</span>
                </div>
            </header>

            <div class="panel-content">
                @yield('content')
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ui.js') }}"></script>
@endsection
