<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JJ-Security - @yield('title')</title>
    
    <!-- 1. Cargamos el CSS común que controla la estructura (Sidebar, Header, Footer) -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    
    <!-- 2. Cargamos la fuente Lato -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet" />
    
    <!-- 3. Aquí se cargarán los CSS específicos de cada página (login.css, contacto.css, etc.) -->
    @yield('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="barra-lateral">
        <div class="logo-sidebar">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" />
            </a>
        </div>
        
        <div class="icono-menu">
            <div class="icono-contenido">
                <img src="{{ asset('img/ajustes.jpg') }}" alt="Ajustes" />
                <span>Ajustes</span>
            </div>
        </div>
        
        <div class="icono-menu">
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
        
        <div class="icono-menu ayuda">
            <div class="icono-contenido">
                <img src="{{ asset('img/mingcute_phone-fill.svg') }}" alt="Ayuda" />
                <span>Ayuda</span>
            </div>
        </div>
    </aside>

    <!-- HEADER: Ahora el título JJ-SECURITY aparecerá en todas las páginas automáticamente -->
    <header>
        <h1>JJ-SECURITY</h1>
    </header>

    <!-- MAIN: Este contenedor envuelve el contenido específico de cada página -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="contenido-footer">
            <div class="footer-links">
                <a href="#">Condiciones de uso</a> | 
                <a href="#">Aviso legal</a> | 
                <a href="#">Cookies</a>
            </div>
            
            <div class="footer-info">
                <div class="copyright">© 2025 JJ-Security. Todos los derechos reservados.</div>
                <div class="footer-contacto">
                    <img src="{{ asset('img/mingcute_phone-fill.svg') }}" alt="Teléfono" />
                    <span>24/7</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    @yield('scripts')
</body>
</html>