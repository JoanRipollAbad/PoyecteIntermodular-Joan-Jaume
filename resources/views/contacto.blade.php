@extends('layouts.app')

@section('title', 'Formulario de Contacto')

@section('styles')
    {{-- Cargamos el CSS desde la carpeta public/css/ --}}
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>Contáctanos</h1>

    {{-- Div para mostrar los mensajes de éxito o error --}}
    <div id="mensaje" class="alert hidden"></div>

    <form id="contactForm">
        @csrf {{-- Token de seguridad obligatorio en Laravel --}}
        
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="asunto">Asunto *</label>
            <input type="text" id="asunto" name="asunto" required>
        </div>

        <div class="form-group">
            <label for="mensaje">Mensaje *</label>
            <textarea id="mensajeTexto" name="mensaje" required></textarea>
        </div>

        <button type="submit">Enviar Mensaje</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('contactForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        try {
            // Utilizamos la URL de Laravel definida en tus rutas
            const response = await fetch("{{ url('/contacto') }}", {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    // Incluimos el token CSRF para que Laravel acepte la petición
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
            });

            const result = await response.json();
            const msgDiv = document.getElementById('mensaje');

            // Limpiamos clases previas y mostramos el mensaje
            msgDiv.classList.remove('hidden', 'success', 'error');
            msgDiv.textContent = result.message;

            if (result.success) {
                msgDiv.classList.add('success');
                this.reset(); // Limpiar formulario si hubo éxito
            } else {
                msgDiv.classList.add('error');
            }

        } catch (err) {
            const msgDiv = document.getElementById('mensaje');
            msgDiv.textContent = 'Error de conexión con el servidor.';
            msgDiv.classList.remove('hidden', 'success');
            msgDiv.classList.add('error');
        }
    });
</script>
@endsection