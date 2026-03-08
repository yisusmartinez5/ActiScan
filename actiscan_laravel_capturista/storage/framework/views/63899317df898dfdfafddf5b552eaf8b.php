
<?php $__env->startSection('title', 'Registrar nuevo activo'); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
    
    <div style="text-align: center;">
        <input type="file" id="foto-input" style="display: none;" accept="image/*" onchange="previsualizarFoto(event)">
        <div id="foto-container" onclick="document.getElementById('foto-input').click()" style="width: 100%; max-width: 300px; height: 300px; border: 2px dashed #ccc; border-radius: var(--radius); display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; margin: 0 auto; background-color: #fafafa; transition: 0.3s;">
            <span style="font-size: 3rem; color: #aaa;">📷</span>
            <p style="color: #666; margin-top: 10px;">Haz clic para agregar foto</p>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 15px;">
        <input type="text" placeholder="Nombre" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        <input type="text" placeholder="No. Serial" style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        <select style="padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
            <option>Marca</option>
            <option>ASUS</option>
        </select>
        <button class="btn" style="align-self: flex-start; margin-top: 10px;" onclick="guardarActivo()">Guardar activo</button>
    </div>
</div>

<script>
    function previsualizarFoto(event) {
        const container = document.getElementById('foto-container');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.style.border = 'none';
                container.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius);">`;
            }
            reader.readAsDataURL(file);
        }
    }

    function guardarActivo() {
        mostrarNotificacion('Activo guardado correctamente en el sistema');
        setTimeout(() => window.location.href = '/dashboard', 1500);
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Yahir\actiscan\actiscan\resources\views/activos/create.blade.php ENDPATH**/ ?>