@extends('layouts.app')

@section('content')
<div class="topbar">
    <div class="brand-box">
        <img src="{{ asset('img/favicon-actiscan.png') }}" alt="ActiScan icon" class="brand-icon">
        <div class="brand-text">
            <h1>ActiScan</h1>
            <p>Gestión de Activos</p>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="login-card container-fluid">
        <div class="row g-0 align-items-center h-100">

            <!-- Lado izquierdo -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center left-panel">
                <div class="logo-panel">
                    <img src="{{ asset('img/actiscan-logo.png') }}" alt="Logo ActiScan" class="main-logo">
                </div>
            </div>

            <!-- Lado derecho -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center right-panel">
                <div class="login-box">
                    <div class="user-icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="105" height="105" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                    </div>

                    <h2 class="welcome-title">Bienvenido</h2>
                    <p class="subtitle">Iniciar sesión</p>

                    <form>
                        <div class="mb-3">
                            <label for="usuario" class="form-label custom-label">Usuario</label>
                            <input type="text" class="form-control custom-input" id="usuario" placeholder="">
                        </div>

                        <div class="mb-2 position-relative">
                            <label for="password" class="form-label custom-label">Contraseña</label>
                            <input type="password" class="form-control custom-input pe-5" id="password" placeholder="">
                            <span class="eye-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                </svg>
                            </span>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('forgot.password') }}" class="forgot-link">Olvide contraseña</a>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('capturist.dashboard') }}" class="btn login-btn">Entrar</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection