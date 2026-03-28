
<div id="<?php echo e($id ?? 'loaderAnimado'); ?>"
    style="display: none; position: absolute; z-index: 1000; top: 0; left: 0; right: 0; bottom: 0; justify-content: center; align-items: center; background: rgba(255,255,255,0.8);">
    <div class="spinner-container">
        <div class="circle c1"></div>
        <div class="circle c2"></div>
        <div class="circle c3"></div>
        <div class="circle c4"></div>
    </div>
</div>

<style>
    /* Estilos del loader local */
    .overlay-local {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #F9FBFD;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    .spinner-container {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .circle {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        animation: float 1s infinite ease-in-out;
    }

    .c1 {
        background-color: #0A8CB3;
        animation-delay: 0s;
    }

    .c2 {
        background-color: #FF5A6A;
        animation-delay: 0.1s;
    }

    .c3 {
        background-color: #FFD700;
        animation-delay: 0.2s;
    }

    .c4 {
        background-color: #102656;
        animation-delay: 0.3s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }
</style>
<?php /**PATH /Users/ronaldoroblesromero/Herd/edukaSchool/resources/views/ccomponentes/loader.blade.php ENDPATH**/ ?>