@extends('layouts.auth')

@section('title', 'Login - ActiScan')
@section('page', 'login')

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

            <form id="loginForm">
                <label class="auth-label" for="usuario">Usuario</label>
                <input class="auth-input" id="usuario" type="text" value="admin@actiscan.com">

                <label class="auth-label" for="password">Contrase&ntilde;a</label>
                <input class="auth-input" id="password" type="password" value="123456">

                <div class="auth-row">
                    <a class="auth-link" href="{{ route('forgot.password') }}">Olvid&eacute; contrase&ntilde;a</a>
                    <button class="btn-primary" type="submit" id="loginButton">Entrar</button>
                </div>
                <p id="loginMessage" class="page-subtitle" style="margin-top: 12px; margin-bottom: 0;"></p>
            </form>
        </div>
    </div>
@endsection
