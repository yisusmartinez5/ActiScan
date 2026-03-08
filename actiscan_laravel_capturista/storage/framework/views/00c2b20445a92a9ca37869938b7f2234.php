

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
    <main class="dashboard-main create-asset-main">
        <div class="create-asset-container">

            <div class="create-asset-topbar">
                <a href="<?php echo e(route('capturist.assets')); ?>" class="asset-back-btn">Volver</a>
            </div>

            <section class="create-asset-card">
                <div class="row g-4 align-items-start">

                    <!-- Cargar foto -->
                    <div class="col-lg-4">
                        <div class="photo-upload-panel">
                            <button type="button" class="photo-upload-box">
                                <span class="photo-upload-icon">
                                    <img src="<?php echo e(asset('img/photo-upload-icon.png')); ?>" alt="Upload Icon" class="photo-upload-img">
                                </span>
                                <span class="photo-upload-text">Agregar foto</span>
                            </button>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <div class="col-lg-8">
                        <form class="create-asset-form">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="floating-label">Nombre:</label>
                                    <input type="text" class="create-asset-input">
                                </div>

                                <div class="col-md-6">
                                    <label class="floating-label">No. Serial:</label>
                                    <input type="text" class="create-asset-input">
                                </div>

                                <div class="col-md-6">
                                    <label class="floating-label">Marca:</label>
                                    <div class="select-wrapper">
                                        <select class="create-asset-select">
                                            <option selected disabled>Selecciona una marca</option>
                                            <option>ASUS</option>
                                            <option>Dell</option>
                                            <option>HP</option>
                                            <option>Lenovo</option>
                                            <option>Acer</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="floating-label">Categoría:</label>
                                    <div class="select-wrapper">
                                        <select class="create-asset-select">
                                            <option selected disabled>Selecciona una categoría</option>
                                            <option>Computadora</option>
                                            <option>Servidor</option>
                                            <option>Red</option>
                                            <option>Móvil</option>
                                            <option>Impresora</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="floating-label">Estado:</label>
                                    <div class="select-wrapper">
                                        <select class="create-asset-select">
                                            <option selected disabled>Selecciona un estado</option>
                                            <option>Operacional</option>
                                            <option>Mantenimiento</option>
                                            <option>Baja</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="floating-label">Ubicación:</label>
                                    <input type="text" class="create-asset-input">
                                </div>

                            </div>

                            <div class="create-asset-actions">
                                <a href="<?php echo e(route('capturist.assets')); ?>" class="btn create-asset-btn">
                                    Agregar activo
                                </a>
                            </div>
                        </form>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/capturist/create-asset.blade.php ENDPATH**/ ?>