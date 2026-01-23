@extends('layouts.app')

@section('title', 'Registro - JJ-Security')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
@endsection

@section('content')
<div class="contenido-fondo registro-page-container">
    <div class="login-box">
        <h2 class="login-title">Registro de Usuario</h2>
        
        <form action="{{ url('/registro') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" placeholder="email@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" placeholder="600 000 000" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="********" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="********" required>
            </div>

            <div class="legal-text">
                <p>Al registrarte, aceptas las <a href="#">Condiciones de uso</a> de JJ-Security</p>
            </div>

            <div class="botones-accion">
                <button type="button" class="btn btn-atras" onclick="window.location.href='{{ url('/login') }}'">ATRÁS</button>
                <button type="submit" class="btn btn-continuar">CONTINUAR</button>
            </div>
        </form>
    </div>
</div>
@endsection