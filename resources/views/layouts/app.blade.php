<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JJ-Security - @yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet" />
    
    <style>
        /* --- ARREGLO PARA QUE LOS BOTONES NO SE VEAN RAROS --- */
        header {
            background-color: #bcd9d6;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 0 30px;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            color: #000;
        }

        .header-icons {
            position: absolute;
            right: 30px;
            display: flex;
            gap: 20px;
        }

        .header-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #000;
        }

        /* Bloqueamos el tamaño del círculo para que la imagen no crezca */
        .icon-circle {
            width: 45px !important;
            height: 45px !important;
            background-color: #fff;
            border-radius: 50%;
            overflow: hidden; /* Esto corta la imagen si es muy grande */
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            margin-bottom: 3px;
        }

        /* Forzamos a la imagen a ocupar solo el círculo */
        .icon-circle img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover; /* Mantiene la proporción sin deformar */
        }

        .header-item span {
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* --- TABULACIÓN EN NEGRO --- */
        [tabindex="0"]:focus, a:focus {
            outline: 3px solid #000 !important;
            outline-offset: 5px;
        }

        :focus:not(:focus-visible) {
            outline: none !important;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <aside class="barra-lateral">
        <div class="logo-sidebar">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" />
            </a>
        </div>
        
        <div class="icono-menu" tabindex="0">
            <div class="icono-contenido">
                <img src="{{ asset('img/ajustes.jpg') }}" alt="Ajustes" />
                <span>Ajustes</span>
            </div>
        </div>
        
        <div class="icono-menu" tabindex="0">
            <div class="icono-contenido">
                <img src="{{ asset('img/filtros.jpg') }}" alt="Filtros" />
                <span>Filtros</span>
            </div>
        </div>
        
        <div class="icono-menu">
            <a href="{{ url('/login') }}" style="text-decoration:none; color:inherit;">
                <div class="icono-contenido">
                    <img src="{{ asset('img/usuario.jpg') }}" alt="Perfil" />
                    <span>Perfil</span>
                </div>
            </a>
        </div>
        
        <div class="icono-menu ayuda" tabindex="0">
            <div class="icono-contenido">
                <img src="{{ asset('img/mingcute_phone-fill.svg') }}" alt="Ayuda" />
                <span>Ayuda</span>
            </div>
        </div>
    </aside>

    <header>
        <h1>JJ-SECURITY</h1>
        
        @if(!Request::is('login') && !Request::is('registro'))
            <div class="header-icons">
                <a href="#" class="header-item" tabindex="0">
                    <div class="icon-circle">
                        <img src="{{ asset('img/carrito.jpg') }}" alt="">
                    </div>
                    <span>Carrito</span>
                </a>
                <a href="{{ url('/login') }}" class="header-item" tabindex="0">
                    <div class="icon-circle">
                        <img src="{{ asset('img/usuario.jpg') }}" alt="">
                    </div>
                    <span>Usuario</span>
                </a>
            </div>
        @endif
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="contenido-footer">
            <div class="footer-links">
                <a href="#">Condiciones de uso</a> | 
                <a href="#">Aviso legal</a> | 
                <a href="#">Cookies</a>
            </div>
            
            <div class="footer-info">
                <div class="copyright">© 2025 JJ-Security. Todos los derechos reservados.</div>
                <div class="footer-contacto" tabindex="0">
                    <img src="{{ asset('img/mingcute_phone-fill.svg') }}" alt="Teléfono" />
                    <span>24/7</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elementosTabulables = document.querySelectorAll('[tabindex="0"]');
            elementosTabulables.forEach(el => {
                el.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        this.click();
                        const link = this.querySelector('a');
                        if (link) window.location.href = link.href;
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>