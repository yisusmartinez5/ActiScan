@extends('layouts.auth')

@section('title', 'Login - ActiScan')

@section('content')
    <div class="auth-card login-card">
        <div class="login-left">
            <div class="logo-mark">
                <div class="logo-square">A</div>
                <div class="logo-square">&diams;</div>
                <div class="logo-square">S</div>
                <div class="logo-square icon"><i class="fa-solid fa-magnifying-glass"></i></div>
            </div>
            <h1 class="logo-title">ActiScan</h1>
            <p class="logo-subtitle">GESTION DE ACTIVOS</p>
        </div>

        <div class="login-right">
            <div class="auth-icon-circle">
                <i class="fa-regular fa-user"></i>
            </div>

            <h1 class="auth-title">Bienvenido</h1>
            <p class="auth-subtitle">Iniciar sesi&oacute;n</p>

            <form>
                <label class="auth-label" for="usuario">Usuario</label>
                <input class="auth-input" id="usuario" type="text" value="admin@actiscan.com">

                <label class="auth-label" for="password">Contrase&ntilde;a</label>
                <input class="auth-input" id="password" type="password" value="123456">

                <div class="auth-row">
                    <a class="auth-link" href="{{ route('forgot.password') }}">Olvid&eacute; contrase&ntilde;a</a>
                    <a class="btn-primary" href="{{ route('capturist.dashboard') }}">Entrar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
