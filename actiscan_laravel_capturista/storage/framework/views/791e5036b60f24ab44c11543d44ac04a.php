

<?php $__env->startSection('content'); ?>
<div class="dashboard-page">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="<?php echo e(route('capturist.dashboard')); ?>" class="sidebar-btn">Dashboard</a>
            <a href="#" class="sidebar-btn">Activos</a>
            <a href="<?php echo e(route('capturist.categories')); ?>" class="sidebar-btn active">Categoría Activos</a>
            <a href="<?php echo e(route('login')); ?>" class="sidebar-btn">Cerrar sesión</a>
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
                <img src="<?php echo e(asset('img/favicon-actiscan.png')); ?>" alt="ActiScan icon" class="brand-icon">
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
    <main class="dashboard-main category-main">
        <div class="category-page-container">

            <div class="category-topbar">
                <div>
                    <h2 class="category-title">Categorías de Activos</h2>
                    <p class="category-subtitle">Gestión y clasificación de tipos de activos fijos.</p>
                </div>

                <div>
                    <a href="<?php echo e(route('capturist.categories.create')); ?>" class="new-category-btn">+ Nueva Categoria</a>
                </div>
            </div>

            <section class="category-card">
                <div class="category-toolbar">
                    <div class="search-box">
                        <span class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.398 1.398h-.001l3.85 3.85.707-.707-3.85-3.85zm-5.242.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                            </svg>
                        </span>
                        <input type="text" placeholder="Filtrar por nombre o descripción">
                    </div>

                    <button type="button" class="advanced-filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 10.117V16l4-2v-3.883l4.447-5.34A1 1 0 0 0 13.68 3H2.32a1 1 0 0 0-.768 1.777L6 10.117zM2.32 4h11.36L9 9.617V13.5l-2 1V9.617L2.32 4z"/>
                        </svg>
                        FILTROS AVANZADOS
                    </button>
                </div>

                <div class="category-table-wrapper">
                    <table class="category-table">
                        <thead>
                            <tr>
                                <th>ICONO</th>
                                <th>CATEGORÍA</th>
                                <th>DESCRIPCIÓN</th>
                                <th>TOTAL ACTIVOS</th>
                                <th>OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="mini-icon-card icon-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M13 5H3a1 1 0 0 0-1 1v5h12V6a1 1 0 0 0-1-1zM3 4h10a2 2 0 0 1 2 2v6H1V6a2 2 0 0 1 2-2z"/>
                                            <path d="M3 13h10v1H3z"/>
                                        </svg>
                                    </div>
                                </td>
                                <td><span class="category-badge">Computadora</span></td>
                                <td class="category-description">● Equipos de cómputo portátiles y de escritorio</td>
                                <td><span class="total-badge">55</span></td>
                                <td>
                                    <a href="#" class="category-action view-action" title="Ver categoría">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="mini-icon-card icon-yellow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M1.5 3A1.5 1.5 0 0 1 3 1.5h10A1.5 1.5 0 0 1 14.5 3v10A1.5 1.5 0 0 1 13 14.5H3A1.5 1.5 0 0 1 1.5 13V3zM3 2.5a.5.5 0 0 0-.5.5v2h11V3a.5.5 0 0 0-.5-.5H3zm10.5 3.5h-11V13a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V6z"/>
                                            <path d="M4 8h8v1H4V8zm0 2h6v1H4v-1z"/>
                                        </svg>
                                    </div>
                                </td>
                                <td><span class="category-badge">Servidor</span></td>
                                <td class="category-description">● Servidores y equipos de almacenamiento</td>
                                <td><span class="total-badge">43</span></td>
                                <td>
                                    <a href="#" class="category-action view-action" title="Ver categoría">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="mini-icon-card icon-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 12.5a.5.5 0 0 1-.354-.146l-2-2 .708-.708L8 11.293l1.646-1.647.708.708-2 2A.5.5 0 0 1 8 12.5z"/>
                                            <path d="M8 10a3 3 0 1 0-2.995-2.824.5.5 0 1 1-.998-.076A4 4 0 1 1 8 11a.5.5 0 0 1 0-1z"/>
                                            <path d="M3.05 6.5a.5.5 0 0 1-.497-.44 6 6 0 0 1 10.894-3.028.5.5 0 0 1-.832.554 5 5 0 0 0-9.08 2.523.5.5 0 0 1-.485.391z"/>
                                        </svg>
                                    </div>
                                </td>
                                <td><span class="category-badge">Red</span></td>
                                <td class="category-description">● Equipos de networking y comunicaciones</td>
                                <td><span class="total-badge">22</span></td>
                                <td>
                                    <a href="#" class="category-action view-action" title="Ver categoría">
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/capturist/categories.blade.php ENDPATH**/ ?>