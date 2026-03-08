
<?php $__env->startSection('title', 'Principal'); ?>

<?php $__env->startSection('content'); ?>
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="card" style="text-align: center; cursor: pointer; transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <h3 style="margin: 0; font-size: 1.2rem;">Total de activos</h3>
        <p style="font-size: 2rem; font-weight: bold; margin: 10px 0 0 0; color: var(--primary);">120</p>
    </div>
    
    <div class="card" onclick="window.location.href='<?php echo e(route('categorias.create')); ?>'" style="text-align: center; cursor: pointer; transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <h3 style="margin: 0; font-size: 1.2rem;">Configurar Categorías</h3>
        <p style="font-size: 0.9rem; margin-top: 10px; color: #666;">Gestionar colores y tipos</p>
    </div>

    <div class="card" onclick="window.location.href='<?php echo e(route('activos.create')); ?>'" style="text-align: center; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: var(--secondary); transition: 0.3s;">
        <span style="font-size: 2rem; font-weight: bold;">+</span>
        <span style="font-size: 0.8rem; font-weight: 500; margin-top: 5px; text-transform: uppercase;">Agregar activo</span>
    </div>
</div>

<div class="card">
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <input type="text" placeholder="Buscar producto..." style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        <button class="btn" onclick="mostrarNotificacion('Búsqueda simulada activada')">Aplicar Filtro</button>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="border-bottom: 2px solid #eee;">
            <tr>
                <th style="padding: 12px; color: #555; font-size: 0.9rem;">Folio</th>
                <th style="padding: 12px; color: #555; font-size: 0.9rem;">Fecha</th>
                <th style="padding: 12px; color: #555; font-size: 0.9rem;">Estatus</th>
                <th style="padding: 12px; color: #555; font-size: 0.9rem;">Acción</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px;">DR-030</td>
                <td style="padding: 12px;">02/10/2025</td>
                <td style="padding: 12px;"><span style="background: #EAF0F6; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; color: var(--primary); font-weight: 600;">Enviado</span></td>
                <td style="padding: 12px;">
                    <button onclick="window.location.href='<?php echo e(route('activos.qr')); ?>'" style="border:none; background:none; cursor:pointer; font-size: 1.1rem; margin-right: 8px;" title="Ver QR">👁️</button>
                    <button onclick="simularEdicion()" style="border:none; background:none; cursor:pointer; font-size: 1.1rem;" title="Editar">✏️</button>
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px;">AS-092</td>
                <td style="padding: 12px;">05/10/2025</td>
                <td style="padding: 12px;"><span style="background: #fef3c7; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; color: #92400e; font-weight: 600;">Pendiente</span></td>
                <td style="padding: 12px;">
                    <button onclick="window.location.href='<?php echo e(route('activos.qr')); ?>'" style="border:none; background:none; cursor:pointer; font-size: 1.1rem; margin-right: 8px;">👁️</button>
                    <button onclick="simularEdicion()" style="border:none; background:none; cursor:pointer; font-size: 1.1rem;">✏️</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    function simularEdicion() {
        Swal.fire({
            title: 'Editar Activo',
            text: '¿Deseas modificar la información de este activo?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2C4258',
            confirmButtonText: 'Sí, editar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir a la pantalla de crear (reutilizada como edición para el prototipo)
                window.location.href = '<?php echo e(route('activos.create')); ?>';
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/dashboard.blade.php ENDPATH**/ ?>