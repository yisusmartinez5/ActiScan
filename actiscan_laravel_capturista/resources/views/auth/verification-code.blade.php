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
    <div class="verification-card">
        <div class="verification-content">

            <div class="verification-icon">
                <img src="{{ asset('img/verification-icon.png') }}" alt="Verification Icon">
            </div>

            <form>
                <div class="code-inputs">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                    <input type="text" maxlength="1" class="code-box">
                </div>

                <p class="verification-text">
                    Hemos enviado un correo con un código de verificación por favor<br>
                    introducelo para el cambio de tu contraseña
                </p>

                <div class="verification-button-wrapper">
                    <a href="{{ route('reset.password') }}" class="btn next-btn">Siguiente</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection