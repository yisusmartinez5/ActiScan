@extends('layouts.auth')

@section('title', 'Cambiar contrase&ntilde;a')

@section('content')
    <div class="auth-card auth-mini-card">
        <div class="auth-mini-icon">
            <i class="fa-solid fa-lock"></i>
        </div>

        <h1 class="auth-mini-title">Cambiar contrase&ntilde;a</h1>
        <p class="auth-mini-text">
            Introduce tu nueva contrase&ntilde;a y confirma el cambio.
        </p>

        <label class="auth-label" for="new_password" style="text-align: left;">Nueva contrase&ntilde;a</label>
        <input class="auth-input" id="new_password" type="password" placeholder="........">

        <label class="auth-label" for="confirm_password" style="text-align: left; margin-top: 8px;">Confirmar contrase&ntilde;a</label>
        <input class="auth-input" id="confirm_password" type="password" placeholder="........">

        <div class="auth-actions">
            <a class="btn-primary" href="{{ route('login') }}">Guardar cambio</a>
        </div>
    </div>
@endsection
