<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JJ-Security - @yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />

    <style>
        /* --- ESTILOS BASE DEL LAYOUT --- */
        body { margin: 0; padding: 0; }
        header { background-color: #bcd9d6; height: 90px; display: flex; align-items: center; justify-content: center; position: relative; padding: 0 30px; }
        header h1 { margin: 0; font-size: 2rem; color: #000; }
        .header-icons { position: absolute; right: 30px; display: flex; gap: 20px; }
        .header-item { display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #000; }
        .icon-circle { width: 45px !important; height: 45px !important; background-color: #fff; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; margin-bottom: 3px; }
        .icon-circle img { width: 100% !important; height: 100% !important; object-fit: cover; }
        .header-item span { font-size: 0.75rem; font-weight: bold; }

        /* --- CSS NUCLEAR PARA EL CHATBOT --- */
        /* Forzamos que el widget ignore el diseño de la página */
        .n8n-chat-widget {
            position: fixed !important;
            bottom: 0 !important;
            right: 0 !important;
            z-index: 2147483647 !important;
        }

        .n8n-chat-widget-window {
            position: fixed !important;
            bottom: 100px !important;
            right: 20px !important;
            width: 380px !important;
            height: 550px !important;
            max-height: 80vh !important;
            box-shadow: 0 10px 50px rgba(0,0,0,0.4) !important;
            background: white !important;
            border-radius: 12px !important;
            overflow: hidden !important;
        }

        /* ELIMINAMOS EL BANNER AZUL QUE TE ESTÁ HUNDIENDO EL CHAT */
        .n8n-chat-widget-window-header-welcome {
            display: none !important;
        }

        .n8n-chat-widget-button {
            position: fixed !important;
            bottom: 30px !important;
            right: 30px !important;
            z-index: 2147483647 !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <aside class="barra-lateral">
        <div class="logo-sidebar"><a href="{{ url('/') }}"><img src="{{ asset('img/logo.jpg') }}" alt="Logo" /></a></div>
        <div class="icono-menu" tabindex="0"><div class="icono-contenido"><img src="{{ asset('img/ajustes.jpg') }}" alt="" /><span>Ajustes</span></div></div>
        <div class="icono-menu" tabindex="0"><div class="icono-contenido"><img src="{{ asset('img/filtros.jpg') }}" alt="" /><span>Filtros</span></div></div>
        <div class="icono-menu"><a href="{{ url('/login') }}" style="text-decoration:none; color:inherit;"><div class="icono-contenido"><img src="{{ asset('img/usuario.jpg') }}" alt="" /><span>Perfil</span></div></a></div>
        <div class="icono-menu ayuda" tabindex="0"><div class="icono-contenido"><img src="{{ asset('img/mingcute_phone-fill.svg') }}" alt="" /><span>Ayuda</span></div></div>
    </aside>

    <header>
        <h1>JJ-SECURITY</h1>
        @if(!Request::is('login') && !Request::is('registro'))
            <div class="header-icons">
                <a href="#" class="header-item"><div class="icon-circle"><img src="{{ asset('img/carrito.jpg') }}" alt=""></div><span>Carrito</span></a>
                <a href="{{ url('/login') }}" class="header-item"><div class="icon-circle"><img src="{{ asset('img/usuario.jpg') }}" alt=""></div><span>Usuario</span></a>
            </div>
        @endif
    </header>

    <!-- EL MAIN AHORA ES NEUTRO -->
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

    <!-- SCRIPT DE n8n -->
    <script type="module">
        import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';
        createChat({
            webhookUrl: 'http://localhost:5678/webhook/29f0abd7-0d17-4608-9b59-051cbd9e43ed/chat',
            title: 'Soporte JJ-Security',
            welcomeMessage: '¡Hola! 👋 ¿En qué puedo ayudarte?',
            backgroundColor: '#ffffff',
            mainColor: '#6bc7b5',
            bubbleColor: '#6bc7b5',
            showWelcomeLeave: false, 
        });
    </script>
    @yield('scripts')
</body>
</html>