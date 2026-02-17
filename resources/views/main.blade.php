@extends('layouts.app')

@section('title', 'JJ-Security - Sistemas de Seguridad')

@section('styles')
    <style>
        /* CONFIGURACIÓN DE LA PÁGINA (Sin afectar al chat) */
        .home-content-wrapper {
            background-color: #f4f7f6;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0; /* Padding vertical */
        }

        /* TUS ESTILOS DE ACCESIBILIDAD Y FOCO */
        :focus { outline: 3px solid #000000 !important; outline-offset: 5px; }
        :focus:not(:focus-visible) { outline: none !important; }

        /* SECCIONES */
        .seccion-blanca {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            width: 95%;
            max-width: 1200px;
            margin-bottom: 40px;
        }

        .titulo-destacado {
            text-align: center; font-weight: 700; font-size: 1.8rem; margin-bottom: 60px; position: relative;
        }
        .titulo-destacado::after {
            content: ""; position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%);
            width: 50px; height: 4px; background-color: #7ed9c7; border-radius: 2px;
        }

        /* PRODUCTOS */
        .grid-productos { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        .tarjeta-producto {
            background: #ffffff; border: 2px solid #e0f2f1; border-radius: 25px; padding: 25px;
            width: 300px; text-align: center; transition: all 0.3s ease; cursor: pointer;
        }
        .tarjeta-producto:hover, .tarjeta-producto:focus {
            border-color: #7ed9c7; box-shadow: 0 10px 20px rgba(126, 217, 199, 0.2); transform: translateY(-5px);
        }
        .img-producto-wrapper { width: 100%; height: 180px; border-radius: 15px; margin-bottom: 20px; overflow: hidden; }
        .img-producto-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .tarjeta-producto:hover img { transform: scale(1.1); }

        /* SERVICIOS */
        .grid-servicios { display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; }
        .tarjeta-servicio {
            background: #ffffff; border-radius: 20px; padding: 40px 30px; width: 340px;
            text-align: center; transition: all 0.3s ease; cursor: pointer;
        }
        .contenedor-logica-circular { width: 110px; height: 110px; margin: 0 auto 30px; position: relative; display: flex; align-items: center; justify-content: center; }
        .anillo-azul-fondo {
            position: absolute; width: 100px; height: 100px; border-radius: 50%; background-color: #fff;
            box-shadow: 0 0 0 6px #e1f5fe, 0 0 0 10px #b3e5fc; z-index: 1; transition: all 0.4s ease;
        }
        .contenedor-logica-circular img {
            width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
            position: relative; z-index: 2; border: 2px solid #fff; transition: all 0.4s ease;
        }
        .tarjeta-servicio:hover img { transform: scale(1.35); }
    </style>
@endsection

@section('content')
    <!-- USAMOS ESTE WRAPPER EN LUGAR DE MAIN PARA EL DISEÑO -->
    <div class="home-content-wrapper">
        
        <div class="seccion-blanca">
            <h2 class="titulo-destacado">Productos Destacados</h2>
            <div class="grid-productos">
                @php
                    $productos = [
                        ['tit' => 'Llaves Inteligentes', 'img' => 'cerraduras/cerraduraInteligente.jpg'],
                        ['tit' => 'Servicios', 'img' => 'servicios/servicio.jpg'],
                        ['tit' => 'Cámaras', 'img' => 'camaras/camaras.jpg']
                    ];
                @endphp
                @foreach($productos as $p)
                <div class="tarjeta-producto" tabindex="0">
                    <h3>{{ $p['tit'] }}</h3>
                    <div class="img-producto-wrapper"><img src="{{ asset('img/marcaAgua/'.$p['img']) }}" alt=""></div>
                    <p style="margin-bottom: 20px;">Agregar a lista de Deseados</p>
                    <a href="{{ url('/product') }}"><button style="background-color: #7ed9c7; color: white; border: none; padding: 10px 45px; border-radius: 30px; font-weight: bold; cursor: pointer;">COMPRAR</button></a>
                </div>
                @endforeach
            </div>
        </div>

        <div class="seccion-blanca">
            <h2 class="titulo-destacado">Nuestros Servicios de Vigilancia</h2>
            <div class="grid-servicios">
                @php
                    $servicios = [
                        ['tit' => 'Instalación Profesional', 'img' => 'instalacion.jpg', 'desc' => 'Expertos a tu disposición para una configuración impecable.'],
                        ['tit' => 'Monitoreo 24/7', 'img' => 'monitoreo.jpg', 'desc' => 'Equipo de seguridad dedicado supervisando las 24 horas.'],
                        ['tit' => 'Mantenimiento y Soporte', 'img' => 'mantenimiento.jpg', 'desc' => 'Garantía de funcionamiento continuo y asistencia técnica.']
                    ];
                @endphp
                @foreach($servicios as $s)
                <div class="tarjeta-servicio" tabindex="0">
                    <div class="contenedor-logica-circular">
                        <div class="anillo-azul-fondo"></div>
                        <img src="{{ asset('img/marcaAgua/'.$s['img']) }}" alt="">
                    </div>
                    <h3>{{ $s['tit'] }}</h3>
                    <p>{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection