@extends('layouts.app')

@section('content')
<div class="topbar">
    <div class="brand-box">
        <img src="{{ asset('img/favicon-actiscan.png') }}" alt="ActiScan icon" class="brand-icon">
        <div class="brand-text">
            <h1>ActiScan</h1>
            <p>Gestion de Activos</p>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="forgot-card">
        <div class="forgot-content">

            <div class="forgot-icon-box">
                <img src="{{ asset('img/forgot-password-icon.png') }}" alt="Forgot Password Icon" class="forgot-icon">
            </div>

            <h2 class="forgot-title">¿Olvidaste tu contraseña?</h2>

            <p class="forgot-text">
                Introduce tu correo de la empresa para enviarte un<br>
                código de seguridad
            </p>

            <form>
                <div class="forgot-form-row">
                    <label for="email" class="forgot-label">Email:</label>
                    <input type="email" id="email" class="form-control forgot-input">
                </div>

                <div class="forgot-buttons">
                    <a href="{{ route('verification.code') }}" class="btn action-btn">Continuar</a>
                    <a href="{{ route('login') }}" class="btn action-btn return-btn">Regresar</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection