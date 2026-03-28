@extends('layouts.auth')

@section('title', 'Recuperar contrase&ntilde;a')

@section('content')
    <div class="auth-card auth-mini-card">
        <div class="auth-mini-icon">
            <i class="fa-solid fa-arrow-rotate-left"></i>
        </div>

        <h1 class="auth-mini-title">&iquest;Olvidaste tu contrase&ntilde;a?</h1>
        <p class="auth-mini-text">
            Introduce tu correo de la empresa para enviarte un c&oacute;digo de seguridad.
        </p>

        <label class="auth-label" for="email" style="text-align: left;">Email</label>
        <input class="auth-input" id="email" type="email" placeholder="ejemplo@empresa.com">

        <div class="auth-actions">
            <a class="btn-secondary" href="{{ route('login') }}">Regresar</a>
            <a class="btn-primary" href="{{ route('verification.code') }}">Continuar</a>
        </div>
    </div>
@endsection
