@extends('layouts.capturist')

@section('title', 'Generar QR')
@section('page', 'generate-qr')
@section('nav_assets', 'active')

@section('content')
    <div class="page-toolbar">
        <div>
            <h1 class="page-title">Personalizar etiqueta QR</h1>
            <p class="page-subtitle">Define que informacion sera visible al escanear el activo.</p>
        </div>

        <a class="ghost-button" href="{{ route('capturist.assets.show') }}">Volver</a>
    </div>

    <div class="content-card">
        <div class="qr-layout">
            <div>
                <div class="qr-option-list" data-toggle-group="qr-fields">
                    <button class="qr-option-btn active" type="button" data-toggle-option><i class="fa-regular fa-circle-dot"></i><span>Nombre</span></button>
                    <button class="qr-option-btn active" type="button" data-toggle-option><i class="fa-regular fa-circle-dot"></i><span>ID activo</span></button>
                    <button class="qr-option-btn active" type="button" data-toggle-option><i class="fa-regular fa-circle-dot"></i><span>No. serie</span></button>
                    <button class="qr-option-btn active" type="button" data-toggle-option><i class="fa-regular fa-circle-dot"></i><span>Ubicacion</span></button>
                </div>

                <h2 class="section-title" style="margin-top: 24px;">Medidas de etiqueta</h2>
                <div class="qr-size-options" data-toggle-group="qr-size">
                    <button class="size-chip active" type="button" data-toggle-option>Estandar</button>
                    <button class="size-chip" type="button" data-toggle-option>Pequena</button>
                    <button class="size-chip" type="button" data-toggle-option>Circular</button>
                </div>

                <div class="quick-actions" style="margin-top: 24px; margin-bottom: 0;">
                    <button class="soft-button" id="generateQrButton" type="button"><i class="fa-solid fa-print"></i><span>Generar QR</span></button>
                </div>
            </div>

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
                            <i class="fa-solid fa-box"></i>
                        </div>
                    </div>

                    <div class="qr-preview-text">
                        <h4 id="qrPreviewName">ACTIVO</h4>
                        <h5 id="qrPreviewCode">CODIGO</h5>
                        <p id="qrPreviewMeta">SERIE Y UBICACION</p>
                    </div>
                </div>

                <p class="qr-preview-caption">VISTA PREVIA DE IMPRESION</p>
            </div>
        </div>
    </div>
    <p id="generateQrMessage" class="page-subtitle" style="margin-top: 12px; margin-bottom: 0;"></p>
@endsection
