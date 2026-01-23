@extends('layouts.app')

@section('title', 'Contáctanos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
@endsection

@section('content')
<div class="container-contacto">
    <h2>Formulario de Contacto</h2>
    <div id="mensaje" class="alert hidden"></div>
    
    <form id="contactForm">
        @csrf
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" placeholder="Tu nombre completo" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="ejemplo@correo.com" required>
        </div>
        <div class="form-group">
            <label>Asunto *</label>
            <input type="text" name="asunto" placeholder="Motivo de la consulta" required>
        </div>
        <div class="form-group">
            <label>Mensaje *</label>
            <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
        </div>
        <button type="submit" class="btn-enviar">Enviar Mensaje</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('contactForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const response = await fetch("{{ url('/contacto') }}", {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(new FormData(this))),
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value 
            }
        });
        const result = await response.json();
        const msgDiv = document.getElementById('mensaje');
        msgDiv.textContent = result.message;
        msgDiv.classList.remove('hidden');
        msgDiv.classList.add(result.success ? 'alert-success' : 'alert-error');
        
        if(result.success) this.reset();
    });
</script>
@endsection