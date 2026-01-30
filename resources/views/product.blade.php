@extends('layouts.app')

@section('title', 'Producto - JJ-Security')

@section('styles')
    <!-- Cargamos Bootstrap y el CSS de producto -->
    <link href="{{ asset('css/boostrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/product.css') }}" />
    
    <style>
        /* Estilos para asegurar que el video y el carrusel se vean bien */
        .carousel-item video, .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        /* Ajuste de color manual si las variables CSS no cargan */
        .btn-comprar-ya {
            background-color: #7ed9c7; /* Tu color turquesa */
            border: 2px solid #2c3e50;
            border-radius: 30px;
            color: white;
            transition: transform 0.2s;
        }
        .btn-comprar-ya:hover {
            transform: scale(1.02);
            color: white;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-5 px-md-5">
    
    <!-- MIGAS DE PAN (BREADCRUMBS) -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="#">Cámaras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cámara de Seguridad Interior</li>
        </ol>
    </nav>

    <section class="tarjeta-principal-contenedor mx-auto shadow-lg bg-white p-4 p-md-5 rounded-4">
        <h2 class="titulo-seccion text-center mb-4">Cámara de Seguridad Interior Ultra HD</h2>

        <div class="row g-5 align-items-center">
            <!-- COLUMNA IZQUIERDA: CARRUSEL -->
            <div class="col-12 col-lg-7">
                <div id="productCarousel" class="carousel slide shadow rounded-4 overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner bg-dark">
                        <!-- Slide 1: Video -->
                        <div class="carousel-item active">
                            <div class="ratio ratio-16x9">
                                <video autoplay muted loop>
                                    <source src="{{ asset('videos/videoCamara.mp4') }}" type="video/mp4" />
                                    Tu navegador no soporta vídeos.
                                </video>
                            </div>
                        </div>

                        <!-- Slide 2: Imagen -->
                        <div class="carousel-item">
                            <div class="ratio ratio-16x9">
                                <img src="{{ asset('img/marcaAgua/camaras/camaras.jpg') }}" class="d-block w-100" alt="Cámara de seguridad">
                            </div>
                        </div>
                    </div>

                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <!-- COLUMNA DERECHA: INFORMACIÓN -->
            <div class="col-12 col-lg-5">
                <div class="ps-lg-3">
                    <span class="display-4 fw-bold d-block mb-2" style="color: #7ed9c7">129.99€</span>
                    <p class="text-warning mb-4 fs-5">★★★★★ <span class="text-muted small fs-6">(154 Reseñas)</span></p>

                    <ul class="list-unstyled mb-4">
                        <li class="mb-3 fs-5 text-dark">✅ Resolución 4K Ultra HD</li>
                        <li class="mb-3 fs-5 text-dark">✅ Visión Nocturna a Color</li>
                        <li class="mb-3 fs-5 text-dark">✅ Detección IA Avanzada</li>
                        <li class="mb-3 fs-5 text-dark">✅ Resistente al Agua IP66</li>
                    </ul>

                    <div class="d-grid gap-3">
                        <!-- BOTÓN COMPRAR YA: Vinculado a la ruta de checkout -->
                        <a href="{{ url('/checkout') }}" class="btn btn-lg fw-bold text-white py-3 shadow-sm d-flex align-items-center justify-content-center btn-comprar-ya" aria-label="Comprar ahora"> 
                            COMPRAR YA 
                        </a>

                        <!-- BOTÓN AÑADIR AL CARRITO -->
                        <button type="button" class="btn fw-bold text-white py-2 shadow-sm" style="background-color: #8faeb0; border-radius: 30px; border: 2px solid #2c3e50">
                            Añadir al carrito
                        </button>

                        <!-- BOTÓN DESEADOS -->
                        <button type="button" class="btn btn-outline-dark btn-sm py-2" style="border-radius: 30px">
                            <span aria-hidden="true">❤️</span> Agregar a Deseados
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DESCRIPCIÓN -->
        <div class="mt-5 pt-4 border-top">
            <h4 class="fw-bold mb-3">Descripción del Producto</h4>
            <p class="text-muted lh-lg">
                La Cámara de Seguridad Exterior Ultra HD de JJ-Security ofrece una vigilancia inigualable para tu hogar o negocio. 
                Con una resolución 4K impresionante, cada detalle se captura con la máxima claridad. 
                Equipada con sensores inteligentes y visión nocturna, garantiza tranquilidad total las 24 horas.
            </p>
        </div>
    </section>
</div>
@endsection

@section('scripts')
    <!-- Cargamos el JS de Bootstrap necesario para el carrusel -->
    <script src="{{ asset('js/boostrap.min.js') }}"></script>
@endsection