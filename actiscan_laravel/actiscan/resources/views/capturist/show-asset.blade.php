@extends('layouts.capturist')

@section('title', 'Detalles del Activo')
@section('page', 'show-asset')
@section('nav_assets', 'active')

@section('content')
    <div class="page-toolbar">
        <div>
            <h1 class="page-title">Detalles del activo</h1>
            <p class="page-subtitle">Consulta rapida de la informacion del equipo y accesos relacionados.</p>
        </div>

        <a class="ghost-button" href="{{ route('capturist.assets') }}">Volver</a>
    </div>

    <div class="content-card">
        <div class="detail-grid">
            <div>
                <div class="detail-list" id="assetDetailList"></div>

                <div class="quick-actions" style="margin-top: 24px; margin-bottom: 0;">
                    <a class="soft-button" id="assetQrLink" href="{{ route('capturist.assets.qr') }}">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Generar QR</span>
                    </a>
                </div>
            </div>

            <div class="preview-box dark" id="assetPreviewBox">
                <div class="preview-placeholder" style="color: #d6dee5;">
                    <i class="fa-solid fa-laptop"></i>
                    <p id="assetPreviewTitle">Vista del activo</p>
                    <small id="assetPreviewSubtitle">Imagen o referencia visual del equipo</small>
                </div>
            </div>
        </div>
    </div>
@endsection
