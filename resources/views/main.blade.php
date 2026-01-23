@extends('layouts.app')

@section('title', 'Sistemas de Seguridad')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
@endsection

@section('content')
    <!-- Primera Tarjeta: Productos Destacados -->
    <section class="tarjeta-principal-contenedor" aria-labelledby="prod-destacados">
        <h2 id="prod-destacados" class="titulo-seccion">Productos Destacados</h2>
        <section class="productos">
            @php
                $destacados = [
                    ['tit' => 'Llaves Inteligentes', 'img' => 'cerraduras/cerraduraInteligente.jpg'],
                    ['tit' => 'Servicios', 'img' => 'servicios/servicio.jpg'],
                    ['tit' => 'Cámaras', 'img' => 'camaras/camaras.jpg']
                ];
            @endphp
            
            @foreach($destacados as $p)
            <article class="tarjeta-productos visible">
                <h3>{{ $p['tit'] }}</h3>
                <div class="imagen-circulo">
                    <img src="{{ asset('img/marcaAgua/'.$p['img']) }}" alt="{{ $p['tit'] }}">
                </div>
                <p>Agregar a lista de Deseados</p>
                <a href="{{ url('/product') }}">
                    <button type="button" aria-label="Comprar {{ $p['tit'] }}">COMPRAR</button>
                </a>
            </article>
            @endforeach
        </section>
    </section>

    <!-- Segunda Tarjeta: Nuestros Servicios de Vigilancia -->
    <section id="servicios" class="tarjeta-principal-contenedor" aria-labelledby="serv-vigilancia">
        <h2 id="serv-vigilancia" class="titulo-seccion">Nuestros Servicios de Vigilancia</h2>
        <div class="service-grid">
            <article class="service-card" tabindex="0">
                <div class="service-icon">
                    <img src="{{ asset('img/marcaAgua/instalacion.jpg') }}" alt="">
                </div>
                <h3>Instalación Profesional</h3>
                <p>Expertos a tu disposición para una configuración impecable de tus sistemas de seguridad.</p>
            </article>

            <article class="service-card" tabindex="0">
                <div class="service-icon">
                    <img src="{{ asset('img/marcaAgua/monitoreo.jpg') }}" alt="">
                </div>
                <h3>Monitoreo 24/7</h3>
                <p>Equipo de seguridad dedicado supervisando tus propiedades las 24 horas del día.</p>
            </article>

            <article class="service-card" tabindex="0">
                <div class="service-icon">
                    <img src="{{ asset('img/marcaAgua/mantenimiento.jpg') }}" alt="">
                </div>
                <h3>Mantenimiento y Soporte</h3>
                <p>Garantía de funcionamiento continuo y asistencia técnica especializada.</p>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animación sutil al hacer scroll para las tarjetas
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.tarjeta-productos, .service-card').forEach((card) => {
                observer.observe(card);
            });
        });
    </script>
@endsection