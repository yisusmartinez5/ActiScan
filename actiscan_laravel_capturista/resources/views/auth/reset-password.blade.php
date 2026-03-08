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
    <div class="reset-card">
        <div class="reset-content">

            <div class="reset-title-box">
                Cambio de contraseña
            </div>

            <div class="row align-items-center g-4 reset-body">
                <div class="col-lg-7">
                    <form class="reset-form">
                        <div class="mb-3">
                            <label for="new_password" class="reset-label">Introduce tu nueva contraseña:</label>
                            <div class="password-field-wrapper">
                                <input type="password" id="new_password" class="form-control reset-input">
                                <span class="password-eye">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="reset-label">Confirma tu nueva contraseña:</label>
                            <div class="password-field-wrapper">
                                <input type="password" id="confirm_password" class="form-control reset-input">
                                <span class="password-eye">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.12 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="reset-button-wrapper">
                            <a href="{{ route('login') }}" class="btn confirm-reset-btn">Confirmar</a>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5 d-flex justify-content-center">
                    <div class="reset-illustration">
                        <img src="{{ asset('img/reset-password-illustration.png') }}" alt="Reset Password Illustration" class="reset-illustration-img">

                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection