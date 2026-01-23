@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="contenido-fondo login-page-container">
    <div class="login-box">
        <h2 class="login-title">Inicia Sesión</h2>
        
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="********" required>
            </div>

            <div class="legal-text">
                <p>Al continuar, aceptas las <a href="#">Condiciones de uso</a> y el <a href="#">Aviso de privacidad</a> de JJ-Security</p>
            </div>

            <div class="botones-accion">
                <button type="button" class="btn btn-atras" onclick="window.location.href='{{ url('/') }}'">ATRÁS</button>
                <button type="submit" class="btn btn-continuar">CONTINUAR</button>
            </div>

            <div class="boton-registro">
                <button type="button" class="btn btn-registro" onclick="window.location.href='{{ url('/registro') }}'">REGISTRO</button>
            </div>
        </form>
    </div>
</div>
@endsection