@extends('layouts.app')

@section('title', 'Registro - JJ-Security')

@section('styles')
    {{-- Vinculamos el CSS de registro que ya tienes --}}
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
@endsection

@section('content')
    {{-- 
       El Sidebar, Header y Footer ya están en layouts.app, 
       así que solo ponemos el contenido principal aquí. 
    --}}
    <div class="contenido-fondo login-page-container">
        <div class="login-box">
            <h2 class="login-title">Registro</h2>

            <form action="{{ url('/registro') }}" method="POST">
                @csrf {{-- Token de seguridad obligatorio --}}

                <!-- Campo NOMBRE -->
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre completo" required />
                </div>

                <!-- Campo CORREO -->
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required />
                </div>

                <!-- Campo TELÉFONO -->
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="600 000 000" required />
                </div>

                <!-- Campo CONTRASEÑA -->
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="********" required />
                </div>

                <!-- Campo CONFIRMAR CONTRASEÑA -->
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="********" required />
                </div>

                <div class="legal-text">
                    <p>Al continuar, aceptas las <a href="#">Condiciones de uso</a> y el <a href="#">Aviso de privacidad</a> de JJ-Security</p>
                </div>

                <div class="botones-accion">
                    {{-- El botón ATRÁS ahora redirige a la ruta /login de Laravel --}}
                    <button type="button" class="btn btn-atras" onclick="window.location.href='{{ url('/login') }}'">ATRÁS</button>
                    
                    {{-- El botón CONTINUAR envía el formulario --}}
                    <button type="submit" class="btn btn-continuar">CONTINUAR</button>
                </div>
            </form>
        </div>
    </div>
@endsection