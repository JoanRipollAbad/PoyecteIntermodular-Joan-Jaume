@extends('layouts.app')

@section('title', 'Cámara Ultra HD - JJ-Security')

@section('styles')
    <!-- Cargamos Bootstrap solo para esta página como ya hacías -->
    <link href="{{ asset('css/boostrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
<!-- No necesitamos main-wrapper ni header, ya los pone el layout -->
<div class="container-fluid py-5 px-md-5">
    <section class="tarjeta-principal-contenedor mx-auto shadow-lg p-4 p-md-5 rounded-4 bg-white">
        <h2 class="titulo-producto text-center mb-4">Cámara de Seguridad Interior Ultra HD</h2>

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
                                </video>
                            </div>
                        </div>

                        <!-- Slide 2: Imagen -->
                        <div class="carousel-item">
                            <div class="ratio ratio-16x9">
                                <img src="{{ asset('img/marcaAgua/camaras/camaras.jpg') }}" class="d-block w-100" style="object-fit: cover" alt="Cámara">
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
                    <span class="precio-destacado display-4 fw-bold d-block mb-2">129.99€</span>
                    <p class="text-warning mb-4 fs-5">★★★★★ <span class="text-muted small fs-6">(154 Reseñas)</span></p>

                    <ul class="list-unstyled mb-4">
                        <li class="mb-3 fs-5 text-dark">✅ Resolución 4K Ultra HD</li>
                        <li class="mb-3 fs-5 text-dark">✅ Visión Nocturna a Color</li>
                        <li class="mb-3 fs-5 text-dark">✅ Detección IA Avanzada</li>
                        <li class="mb-3 fs-5 text-dark">✅ Resistente al Agua IP66</li>
                    </ul>

                    <div class="d-grid gap-3">
                        <button class="btn-carrito btn btn-lg fw-bold text-white py-3 shadow-sm">AÑADIR AL CARRITO</button>
                        <button class="btn-deseados btn btn-outline-dark btn-sm py-2">❤️ Agregar a Deseados</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DESCRIPCIÓN -->
        <div class="mt-5 pt-4 border-top">
            <h4 class="fw-bold mb-3">Descripción del Producto</h4>
            <p class="text-muted lh-lg">La Cámara de Seguridad Exterior Ultra HD de JJ-Security ofrece una vigilancia inigualable para tu hogar o negocio. Con una resolución 4K impresionante, cada detalle se captura con la máxima claridad.</p>
        </div>
    </section>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/boostrap.min.js') }}"></script>
@endsection