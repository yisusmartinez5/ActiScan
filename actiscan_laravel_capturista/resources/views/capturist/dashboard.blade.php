@extends('layouts.app')

@section('content')
<div class="dashboard-page">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="#" class="sidebar-btn active">Dashboard</a>
            <a href="{{ route('capturist.assets') }}" class="sidebar-btn">Activos</a>
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
    <main class="dashboard-main">
        <div class="dashboard-container">

            <!-- Cards -->
            <section class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon">
                        <img src="{{ asset('img/total-assets-icon.png') }}" alt="Total Assets Icon">
                    </div>
                    <h3>Total de<br>activos</h3>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <img src="{{ asset('img/assets-with-observations-icon.png') }}" alt="Assets with Observations Icon">
                    </div>
                    <h3>Activos con<br>observaciones</h3>
                </div>

                <div class="summary-card">
                    <div class="summary-icon add-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a.5.5 0 0 1 .5.5v6h6a.5.5 0 0 1 0 1h-6v6a.5.5 0 0 1-1 0v-6h-6a.5.5 0 0 1 0-1h6v-6A.5.5 0 0 1 8 1z"/>
                        </svg>
                    </div>
                    <h3>Agregar activo</h3>
                </div>
            </section>

            <!-- Filter Bar -->
            <section class="filter-wrapper">
                <div class="filter-bar">
                    <input type="text" class="filter-input" placeholder="Buscar producto..">

                    <select class="filter-select">
                        <option>Categoría</option>
                    </select>

                    <select class="filter-select small-select">
                        <option>Todo</option>
                    </select>

                    <button type="button" class="filter-btn">Aplicar Filtro</button>
                </div>
            </section>

            <!-- Table -->
            <section class="table-section">
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Estatus</th>
                                    <th>Artículos</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>DR-030</td>
                                    <td>02/10/2025</td>
                                    <td>Enviado</td>
                                    <td>5</td>
                                    <td>
                                        <a href="#" class="table-action" title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DR-029</td>
                                    <td>28/09/2025</td>
                                    <td>Surtido</td>
                                    <td>3</td>
                                    <td>
                                        <a href="#" class="table-action" title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DR-028</td>
                                    <td>25/09/2025</td>
                                    <td>Recibido</td>
                                    <td>4</td>
                                    <td>
                                        <a href="#" class="table-action" title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DR-027</td>
                                    <td>20/09/2025</td>
                                    <td>Cancelado</td>
                                    <td>2</td>
                                    <td>
                                        <a href="#" class="table-action" title="Ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    });
</script>
@endsection