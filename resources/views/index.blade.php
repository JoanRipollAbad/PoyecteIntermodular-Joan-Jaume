@extends('layouts.app')

@section('title', 'Inicio - PI Frontend')

@section('styles')
    <style>
        /* Estilos rápidos por si quieres darle formato a las secciones */
        section {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-top: 10px;
        }
        h1 { color: #333; }
        h2 { color: #666; }
    </style>
@endsection

@section('content')
    <div class="container-inicio" style="padding: 40px;">
        <h1>Bienvenido PI Frontend</h1>
        <h2>Estoy editando en mi rama personal</h2>
        <h3>Cambios antes de ayudar a mi compañero</h3>
        
        <section>
            Esto es una sección
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        console.log("Página de inicio cargada correctamente");
    </script>
@endsection