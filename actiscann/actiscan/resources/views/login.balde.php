@extends('layouts.app')

@section('content')

<div class="row justify-content-center">

<div class="col-md-10">

<div class="login-card">

<div class="row align-items-center">

<!-- LOGO -->
<div class="col-md-6 text-center">

<img src="/img/actiscan.png" width="350">

</div>

<!-- FORM -->
<div class="col-md-6">

<div class="text-center mb-4">

<img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" width="80">

<h4 class="mt-2">Bienvenido</h4>
<p>Iniciar sesión</p>

</div>

<div class="mb-3">
<label>Usuario</label>
<input type="text" class="form-control input-custom">
</div>

<div class="mb-3">
<label>Contraseña</label>
<input type="password" class="form-control input-custom">
</div>

<a href="#" class="small">Olvide contraseña</a>

<div class="text-center mt-3">

<button class="btn btn-secondary px-4">
Entrar
</button>

</div>

</div>

</div>

</div>

</div>

</div>

@endsection