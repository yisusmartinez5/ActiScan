

<?php $__env->startSection('content'); ?>
<div class="dashboard-page">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="<?php echo e(route('capturist.dashboard')); ?>" class="sidebar-btn">Dashboard</a>
            <a href="<?php echo e(route('capturist.assets')); ?>" class="sidebar-btn active">Activos</a>
            <a href="<?php echo e(route('capturist.categories')); ?>" class="sidebar-btn">Categoría Activos</a>
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
    <main class="dashboard-main qr-main">
        <div class="qr-page-container">

            <div class="qr-topbar">
                <a href="<?php echo e(route('capturist.assets.show')); ?>" class="asset-back-btn">Volver</a>
            </div>

            <div class="row g-4 align-items-start">

                <!-- Panel izquierdo -->
                <div class="col-lg-5">
                    <section class="qr-config-card">
                        <h2 class="qr-config-title">Personalizar Etiqueta</h2>
                        <p class="qr-config-subtitle">
                            Define qué información será legible mediante el escaneo del código QR.
                        </p>

                        <div class="qr-option-list">
                            <button type="button" class="qr-option-btn active">
                                <span class="qr-option-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                        <circle cx="8" cy="8" r="2.5"/>
                                    </svg>
                                </span>
                                <span>Nombre</span>
                            </button>

                            <button type="button" class="qr-option-btn active">
                                <span class="qr-option-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                        <circle cx="8" cy="8" r="2.5"/>
                                    </svg>
                                </span>
                                <span>ID Activo</span>
                            </button>

                            <button type="button" class="qr-option-btn active">
                                <span class="qr-option-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                        <circle cx="8" cy="8" r="2.5"/>
                                    </svg>
                                </span>
                                <span>N° Serie</span>
                            </button>

                            <button type="button" class="qr-option-btn active">
                                <span class="qr-option-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                        <circle cx="8" cy="8" r="2.5"/>
                                    </svg>
                                </span>
                                <span>Ubicación</span>
                            </button>
                        </div>

                        <div class="qr-size-block">
                            <p class="qr-size-label">MEDIDAS DE ETIQUETA</p>

                            <div class="qr-size-options">
                                <button type="button" class="qr-size-btn active">Estándar</button>
                                <button type="button" class="qr-size-btn">Pequeña</button>
                                <button type="button" class="qr-size-btn">Circular</button>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Panel derecho -->
                <div class="col-lg-7">
                    <div class="qr-preview-area">
                        <div class="qr-preview-frame">
                            <div class="qr-preview-card">
                                <div class="qr-code-mockup">
                                    <div class="qr-grid">
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                    </div>

                                    <div class="qr-center-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8.21.5a1 1 0 0 0-.42 0l-6 1.5A1 1 0 0 0 1 2.97v7.56a1 1 0 0 0 .79.97l6 1.5a1 1 0 0 0 .42 0l6-1.5a1 1 0 0 0 .79-.97V2.97a1 1 0 0 0-.79-.97l-6-1.5zM8 1.52 13.5 2.9 8 4.27 2.5 2.9 8 1.52zM2 3.72l5.5 1.38v6.38L2 10.1V3.72zm6.5 7.76V5.1L14 3.72v6.38l-5.5 1.38z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="qr-preview-text">
                                    <h4>LAPTOP ASUS ROG</h4>
                                    <h5>AST0001</h5>
                                    <p>SN: SN-99283 &nbsp;&nbsp;&nbsp; LOC: C-207</p>
                                </div>
                            </div>

                            <p class="qr-preview-caption">VISTA PREVIA DE IMPRESIÓN</p>
                        </div>

                        <div class="qr-print-wrapper">
                            <a href="#" class="qr-print-btn">
                                <span class="qr-print-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M2 7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4H2V7zm2-1a1 1 0 0 0-1 1v3h10V7a1 1 0 0 0-1-1H4z"/>
                                        <path d="M4 1h8v3H4V1zm1 1v1h6V2H5z"/>
                                        <path d="M4 11h8v4H4v-4zm1 1v2h6v-2H5z"/>
                                    </svg>
                                </span>
                                <span>IMPRIMIR AHORA</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
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

        const sizeButtons = document.querySelectorAll('.qr-size-btn');
        sizeButtons.forEach(button => {
            button.addEventListener('click', function () {
                sizeButtons.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/capturist/generate-qr.blade.php ENDPATH**/ ?>