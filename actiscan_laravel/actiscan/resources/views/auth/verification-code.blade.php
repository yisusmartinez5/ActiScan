@extends('layouts.auth')

@section('title', 'Verificar codigo')

@section('content')
    <div class="auth-card auth-mini-card">
        <div class="auth-mini-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        <p class="auth-mini-text" style="margin-bottom: 12px;">
            Hemos enviado un correo con un c&oacute;digo de verificaci&oacute;n. Introd&uacute;celo para continuar con el cambio de contrase&ntilde;a.
        </p>

        <div class="code-boxes">
            <input class="code-input" type="text" maxlength="1">
            <input class="code-input" type="text" maxlength="1">
            <input class="code-input" type="text" maxlength="1">
            <input class="code-input" type="text" maxlength="1">
            <input class="code-input" type="text" maxlength="1">
            <input class="code-input" type="text" maxlength="1">
        </div>

        <a class="btn-primary" href="{{ route('reset.password') }}" style="min-width: 150px;">Siguiente</a>
    </div>
@endsection
