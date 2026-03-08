

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
    <main class="dashboard-main create-category-main">
        <div class="create-category-container">

            <div class="create-category-header">
                <h2 class="create-category-title">Nueva Categoría</h2>
                <p class="create-category-subtitle">Crear una nueva categoría de activos fijos</p>
            </div>

            <section class="create-category-card">
                <form>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <label class="create-label">Nombre de la categoría</label>
                            <input type="text" class="create-input">
                        </div>

                        <div class="col-lg-6">
                            <label class="create-label">Descripción</label>
                            <input type="text" class="create-input">
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-lg-6">
                            <label class="create-label">Seleccionar ícono</label>

                            <div class="icon-grid">
                                <button type="button" class="icon-option active">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M13 5H3a1 1 0 0 0-1 1v5h12V6a1 1 0 0 0-1-1zM3 4h10a2 2 0 0 1 2 2v6H1V6a2 2 0 0 1 2-2z"/>
                                            <path d="M3 13h10v1H3z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Laptop</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3 3h10a1 1 0 0 1 1 1v6H2V4a1 1 0 0 1 1-1zm0-1a2 2 0 0 0-2 2v7h14V4a2 2 0 0 0-2-2H3z"/>
                                            <path d="M0 12h16v1a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-1z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Monitor</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2 7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4H2V7zm2-1a1 1 0 0 0-1 1v3h10V7a1 1 0 0 0-1-1H4z"/>
                                            <path d="M4 3h8v1H4zM5 12h6v1H5z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Impresora</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5 1h6a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm0 1v12h6V2H5z"/>
                                            <path d="M7 12h2v1H7z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Móvil</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v6H2V4zm2-1a1 1 0 0 0-1 1v5h10V4a1 1 0 0 0-1-1H4z"/>
                                            <path d="M1 11h14v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Servidor</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 13a.5.5 0 0 1-.5-.5V8.707L5.354 10.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 8.707V12.5A.5.5 0 0 1 8 13z"/>
                                            <path d="M2 6.5a5.5 5.5 0 0 1 10.74-1.745A3.5 3.5 0 1 1 12.5 12H4a2 2 0 1 1 .46-3.946A5.48 5.48 0 0 1 2 6.5z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Red</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8.21.5a1 1 0 0 0-.42 0l-6 1.5A1 1 0 0 0 1 2.97v7.56a1 1 0 0 0 .79.97l6 1.5a1 1 0 0 0 .42 0l6-1.5a1 1 0 0 0 .79-.97V2.97a1 1 0 0 0-.79-.97l-6-1.5zM8 1.52 13.5 2.9 8 4.27 2.5 2.9 8 1.52zM2 3.72l5.5 1.38v6.38L2 10.1V3.72zm6.5 7.76V5.1L14 3.72v6.38l-5.5 1.38z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Caja</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8.165 15.803c-.12.06-.26.06-.38 0l-3.5-1.75a.5.5 0 0 1-.276-.447V9.618l-3.5-1.75A.5.5 0 0 1 0 7.421v-.842a.5.5 0 0 1 .276-.447l3.5-1.75V2.394a.5.5 0 0 1 .276-.447l3.5-1.75a.5.5 0 0 1 .448 0l3.5 1.75a.5.5 0 0 1 .276.447v1.988l3.5 1.75a.5.5 0 0 1 .276.447v.842a.5.5 0 0 1-.276.447l-3.5 1.75v3.988a.5.5 0 0 1-.276.447l-3.5 1.75z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Herramienta</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4 9a1 1 0 0 1 1-1h6a1 1 0 0 1 .8.4l1.8 2.4A1 1 0 0 1 12.8 12H3.2a1 1 0 0 1-.8-1.6L4.2 8.4A1 1 0 0 1 5 8h6"/>
                                            <path d="M3 7V5a2 2 0 0 1 2-2h1V2h4v1h1a2 2 0 0 1 2 2v2H3z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Vehículo</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2 2h12v12H2V2zm1 1v10h10V3H3z"/>
                                            <path d="M5 5h2v2H5V5zm4 0h2v2H9V5zM5 9h2v2H5V9zm4 0h2v2H9V9z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Edificio</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3 3h10v8H3V3zm1 1v6h8V4H4z"/>
                                            <path d="M2 12h12v1H2z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Mueble</span>
                                </button>

                                <button type="button" class="icon-option">
                                    <span class="icon-option-svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4 1h6l3 3v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm5 1.5V5h2.5L9 2.5zM4 2v12h8V6H8V2H4z"/>
                                        </svg>
                                    </span>
                                    <span class="icon-option-text">Documento</span>
                                </button>
                            </div>

                            <div class="preview-block">
                                <label class="create-label">Vista Previa</label>

                                <div class="preview-card">
                                    <div class="preview-icon-box">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M13 5H3a1 1 0 0 0-1 1v5h12V6a1 1 0 0 0-1-1zM3 4h10a2 2 0 0 1 2 2v6H1V6a2 2 0 0 1 2-2z"/>
                                            <path d="M3 13h10v1H3z"/>
                                        </svg>
                                    </div>

                                    <div class="preview-text">
                                        <h4>Nombre de la categoría</h4>
                                        <p>Descripción de la categoría</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="create-label">Seleccionar Color</label>

                            <div class="color-palette">
                                <button type="button" class="color-option active" style="background-color: #4a90e2;"></button>
                                <button type="button" class="color-option" style="background-color: #7b68ee;"></button>
                                <button type="button" class="color-option" style="background-color: #ff6b6b;"></button>
                                <button type="button" class="color-option" style="background-color: #57d163;"></button>
                                <button type="button" class="color-option" style="background-color: #f4cd3d;"></button>
                                <button type="button" class="color-option" style="background-color: #28c7a3;"></button>

                                <button type="button" class="color-option" style="background-color: #ff914d;"></button>
                                <button type="button" class="color-option" style="background-color: #e24b90;"></button>
                                <button type="button" class="color-option" style="background-color: #a946d1;"></button>
                                <button type="button" class="color-option" style="background-color: #4ca3f0;"></button>
                                <button type="button" class="color-option" style="background-color: #5fcb74;"></button>
                                <button type="button" class="color-option" style="background-color: #f7a53a;"></button>
                            </div>
                        </div>
                    </div>

                    <div class="create-actions">
                        <a href="<?php echo e(route('capturist.categories')); ?>" class="btn form-action-btn cancel-btn">Cancelar</a>
                        <a href="<?php echo e(route('capturist.categories')); ?>" class="btn form-action-btn create-btn">Crear categoria</a>
                    </div>
                </form>
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

        const iconOptions = document.querySelectorAll('.icon-option');
        iconOptions.forEach(option => {
            option.addEventListener('click', function () {
                iconOptions.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', function () {
                colorOptions.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/capturist/create-category.blade.php ENDPATH**/ ?>