@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('styles')
    {{-- Cargamos el CSS específico de login --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    {{-- 
       No incluimos ni Sidebar, ni Header ni Footer aquí, 
       porque ya están en layouts.app 
    --}}
    <div class="contenido-fondo login-page-container">
        <div class="login-box">
            <h2 class="login-title">Inicia Sesión</h2>

            <form action="{{ url('/login') }}" method="POST">
                @csrf {{-- Protección obligatoria de Laravel para formularios --}}
                
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required />
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="legal-text">
                    <p>Al continuar, aceptas las <a href="#">Condiciones de uso</a> y el <a href="#">Aviso de privacidad</a> de JJ-Security</p>
                </div>

                <div class="botones-accion">
                    {{-- Usamos url('/') para volver al inicio --}}
                    <button type="button" class="btn btn-atras" onclick="window.location.href='{{ url('/') }}'">ATRÁS</button>
                    <button type="submit" class="btn btn-continuar">CONTINUAR</button>
                </div>

                <div class="boton-registro">
                    {{-- Usamos url('/registro') para ir a la página de registro --}}
                    <button type="button" class="btn btn-registro" onclick="window.location.href='{{ url('/registro') }}'">REGISTRO</button>
                </div>
            </form>
        </div>
    </div>
@endsection