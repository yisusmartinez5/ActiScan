

<?php $__env->startSection('content'); ?>
<div class="topbar">
    <div class="brand-box">
        <img src="<?php echo e(asset('img/favicon-actiscan.png')); ?>" alt="ActiScan icon" class="brand-icon">
        <div class="brand-text">
            <h1>ActiScan</h1>
            <p>Gestion de Activos</p>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="verification-card">
        <div class="verification-content">

            <div class="verification-icon">
                <img src="<?php echo e(asset('img/verification-icon.png')); ?>" alt="Verification Icon">
            </div>

            <form>
                <div class="code-inputs">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                </div>

                <p class="verification-text">
                    Hemos enviado un correo con un código de verificación por favor<br>
                    introducelo para el cambio de tu contraseña
                </p>

                <div class="verification-button-wrapper">
                    <a href="<?php echo e(route('reset.password')); ?>" class="btn next-btn">Siguiente</a>
                </div>
            </form>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/auth/verification-code.blade.php ENDPATH**/ ?>