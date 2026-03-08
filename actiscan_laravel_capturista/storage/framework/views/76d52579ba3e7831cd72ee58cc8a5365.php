
<?php $__env->startSection('title', 'Cambio de Contraseña'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div class="card" style="width: 100%; max-width: 500px; text-align: center;">
        <h2 style="margin-bottom: 20px;">Cambio de contraseña</h2>
        
        <div style="text-align: left; margin-bottom: 15px;">
            <label style="font-size: 0.9rem; color: #555;">Introduce tu nueva contraseña:</label>
            <input type="password" id="pass1" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">
        </div>
        
        <div style="text-align: left; margin-bottom: 25px;">
            <label style="font-size: 0.9rem; color: #555;">Confirma tu nueva contraseña:</label>
            <input type="password" id="pass2" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">
        </div>

        <button class="btn" onclick="cambiarPassword()" style="width: 100%;">Confirmar</button>
    </div>
</div>

<script>
    function cambiarPassword() {
        // 1. Alerta de éxito y redirección automática
        Swal.fire({
            title: '¡Éxito!',
            text: 'Tu contraseña ha sido actualizada correctamente.',
            icon: 'success',
            confirmButtonText: 'Ir al Inicio',
            confirmButtonColor: '#2C4258'
        }).then((result) => {
            if (result.isConfirmed || result.isDismissed) {
                window.location.href = '/dashboard'; // Redirige al inicio
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/auth/passwords/reset.blade.php ENDPATH**/ ?>