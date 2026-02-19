<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { onMounted, ref } from 'vue'

const authStore = useAuthStore()
const isSidebarExpanded = ref(false)

onMounted(() => {
  // 1. Force CSS variables in the primary stylesheet
  const style = document.createElement('style');
  style.id = 'jj-chatbot-overrides';
  style.innerHTML = `
    :root {
      --chat-primary-color: #6bc7b5 !important;
      --chat-bubble-color: #6bc7b5 !important;
      --chat-button-background: #6bc7b5 !important;
      --n8n-chat-primary-color: #6bc7b5 !important;
      --n8n-chat-bubble-color: #6bc7b5 !important;
    }
    .n8n-chat-widget { z-index: 9999 !important; }
    .n8n-chat-widget-bubble, [class*="chat-widget-bubble"], button[class*="chat-bubble"] {
      background-color: #6bc7b5 !important;
    }
  `;
  document.head.appendChild(style);

  // 2. Initialize Chat
  const script = document.createElement('script');
  script.type = 'module';
  script.innerHTML = `
    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';
    createChat({
      webhookUrl: 'http://localhost:5678/webhook/29f0abd7-0d17-4608-9b59-051cbd9e43ed/chat',
      title: 'JJ-Security',
      subtitle: 'Asistente Virtual 24/7',
      welcomeMessage: '¡Hola! 👋 Soy Nathan, tu asistente de seguridad. ¿En qué puedo ayudarte hoy?',
      backgroundColor: '#ffffff',
      mainColor: '#6bc7b5',
      bubbleColor: '#6bc7b5',
      bubbleAvatarUrl: '/img/logo.jpg',
      locale: 'es',
      i18n: {
        es: {
          title: 'JJ-Security',
          subtitle: 'En línea - Nathan',
          welcomeMessage: '¡Hola! 👋 ¿En qué puedo ayudarte hoy?',
          inputPlaceholder: 'Escribe tu consulta aquí...',
          sendButtonText: 'Enviar',
          getStartedText: 'Empezar chat',
        }
      }
    });
  `;
  document.body.appendChild(script);

  // 3. NUCLEAR: Continuous observer to force button color
  const observer = new MutationObserver((mutations) => {
    const bubble = document.querySelector('.n8n-chat-widget-bubble') || 
                   document.querySelector('[class*="chat-widget-bubble"]') ||
                   document.querySelector('button[class*="chat-bubble"]');
    if (bubble) {
      bubble.style.setProperty('background-color', '#6bc7b5', 'important');
      const svg = bubble.querySelector('svg');
      if (svg) svg.style.setProperty('fill', '#ffffff', 'important');
    }
  });
  observer.observe(document.body, { childList: true, subtree: true });
});
</script>

<template>
  <div class="app-container">
    <!-- Skip link for accessibility -->
    <a href="#contenido-principal" class="skip-link" aria-label="Saltar al contenido principal">Saltar al contenido principal</a>

    <aside 
      class="barra-lateral" 
      role="navigation" 
      aria-label="Menú lateral"
      :class="{ 'expanded': isSidebarExpanded }"
      @mouseenter="isSidebarExpanded = true"
      @mouseleave="isSidebarExpanded = false"
    >
      <div class="logo-sidebar">
        <RouterLink to="/" aria-label="Ir a la página de inicio">
          <img src="/img/logo.jpg" alt="Logo de JJ-Security" class="logo-img" />
        </RouterLink>
      </div>
      
      <div class="menu-items">
        <button class="icono-menu" aria-label="Ajustes">
          <div class="icono-contenido">
            <div class="icon-wrapper">
              <img src="/img/ajustes.jpg" alt="" aria-hidden="true" />
            </div>
            <span class="menu-text">Ajustes</span>
          </div>
        </button>
        
        <RouterLink to="/filters" class="icono-menu" aria-label="Filtros y Categorías">
          <div class="icono-contenido">
            <div class="icon-wrapper">
              <img src="/img/filtros.jpg" alt="" aria-hidden="true" />
            </div>
            <span class="menu-text">Filtros</span>
          </div>
        </RouterLink>
        
        <RouterLink to="/login" class="icono-menu" aria-label="Mi Perfil">
          <div class="icono-contenido">
            <div class="icon-wrapper">
              <img src="/img/usuario.jpg" alt="" aria-hidden="true" />
            </div>
            <span class="menu-text">Perfil</span>
          </div>
        </RouterLink>
      </div>

      <div class="icono-menu ayuda" aria-label="Ayuda y Soporte">
        <div class="icono-contenido">
          <div class="icon-wrapper circle-btn">
            <img src="/img/mingcute_phone-fill.svg" alt="" aria-hidden="true" />
          </div>
          <span class="menu-text">Ayuda</span>
        </div>
      </div>
    </aside>

    <div class="main-content" :class="{ 'sidebar-expanded': isSidebarExpanded }">
      <header role="banner">
        <div class="header-container">
          <h1 class="brand-title">JJ-Security</h1>
          <nav v-if="!$route.path.includes('/login') && !$route.path.includes('/register')" class="header-icons" aria-label="Accesos rápidos">
            <RouterLink to="/checkout" class="header-item" aria-label="Ver mi carrito">
              <div class="icon-circle">
                <img src="/img/carrito.jpg" alt="" aria-hidden="true">
              </div>
              <span>Carrito</span>
            </RouterLink>
            <RouterLink to="/login" class="header-item" aria-label="Acceder a mi perfil">
              <div class="icon-circle">
                <img src="/img/usuario.jpg" alt="" aria-hidden="true">
              </div>
              <span>Usuario</span>
            </RouterLink>
          </nav>
        </div>
      </header>

      <main class="page-content" id="contenido-principal">
        <RouterView />
      </main>

      <footer role="contentinfo">
        <div class="footer-container">
          <div class="footer-top">
            <nav class="footer-links" aria-label="Enlaces legales">
              <a href="#" aria-label="Condiciones de uso">Condiciones de uso</a>
              <span class="divider" aria-hidden="true">|</span>
              <a href="#" aria-label="Aviso legal">Aviso legal</a>
              <span class="divider" aria-hidden="true">|</span>
              <a href="#" aria-label="Cookies">Cookies</a>
            </nav>
          </div>
          
          <div class="footer-bottom">
            <div class="copyright" aria-label="© 2025 JJ-Security. Todos los derechos reservados.">© 2025 JJ-Security. Todos los derechos reservados.</div>
            <div class="footer-contacto" tabindex="0" aria-label="Soporte 24/7">
              <div class="contact-circle">
                <img src="/img/mingcute_phone-fill.svg" alt="" aria-hidden="true" />
              </div>
              <span class="contact-text">24/7</span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<style>
/* Global styles */
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap');
@import 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css';

:root {
  --primary-color: #6bc7b5;
  --secondary-color: #bcd9d6;
  --bg-color: #f4f7f6;
  --card-bg: #ffffff;
  --text-color: #333;
  --sidebar-width: 100px;
  --sidebar-expanded-width: 240px;
  --header-height: 80px;
  --transition-speed: 0.3s;
}

body {
  margin: 0;
  padding: 0;
  font-family: 'Lato', sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
  overflow-x: hidden;
}

/* Skip link for accessibility */
.skip-link {
  position: absolute;
  top: -100px;
  left: 0;
  background: var(--primary-color);
  color: #000;
  padding: 10px;
  z-index: 2000;
  transition: top 0.3s;
}

.skip-link:focus {
  top: 0;
}

/* Accessibility Focus style */
:focus {
  outline: 3px solid black !important;
  outline-offset: 4px;
}

.app-container {
  display: flex;
  min-height: 100vh;
}

/* Sidebar Styling - Clean & Modern */
.barra-lateral {
  width: var(--sidebar-width);
  background-color: #bcd9d6; /* Matches screenshots */
  display: flex;
  flex-direction: column;
  align-items: center; 
  padding: 20px 0;
  border-right: none;
  position: fixed;
  height: 100vh;
  z-index: 1000;
  transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

.barra-lateral.expanded {
  width: var(--sidebar-expanded-width); /* Slightly wider when active */
}

.logo-sidebar {
  width: 100%;
  display: flex;
  justify-content: center;
  margin-bottom: 50px;
  transition: all var(--transition-speed);
}

.logo-img {
  width: 75px; /* Larger logo as requested */
  height: 75px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  border: 3px solid white;
}

.menu-items {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 25px;
}

.icono-menu {
  width: 100%;
  cursor: pointer;
  text-decoration: none;
  background: none;
  border: none;
  color: #333;
  display: flex;
  justify-content: center;
  padding: 0;
}

.icono-contenido {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  transition: all var(--transition-speed);
}

.barra-lateral.expanded .icono-contenido {
  flex-direction: row;
  padding-left: 20px;
  justify-content: flex-start;
  gap: 20px;
}

.icon-wrapper {
  width: 55px; /* Smaller menu icons as requested */
  height: 55px;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border: 2px solid white;
  flex-shrink: 0;
  overflow: hidden; /* Added to ensure images don't bleed out of circles */
}

.icono-menu:hover .icon-wrapper {
  transform: scale(1.05);
  box-shadow: 0 5px 12px rgba(0,0,0,0.15);
  background-color: #f9f9f9;
}

.icon-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Changed from contain to cover to fill circle */
}

.menu-text {
  font-size: 13px;
  color: #222;
  font-weight: 700;
  margin-top: 6px;
  text-transform: capitalize;
  transition: all var(--transition-speed);
  white-space: nowrap;
}

.barra-lateral.expanded .menu-text {
  font-size: 18px;
  margin-top: 0;
}

/* Ayuda stays centered at bottom */
.ayuda {
  margin-top: auto;
  margin-bottom: 20px;
}

.ayuda .icon-wrapper {
  width: 55px;
  height: 55px;
}

.ayuda .icon-wrapper img {
  width: 30px;
  height: 30px;
}

/* Main Content Area */
.main-content {
  flex: 1;
  margin-left: var(--sidebar-width);
  display: flex;
  flex-direction: column;
  transition: margin-left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
  min-height: 100vh;
}

/* Header Refinement */
header {
  background-color: #bcd9d6;
  height: var(--header-height);
  width: 100%;
  display: flex;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 900;
}

.header-container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 0 40px;
}

.brand-title {
  margin: 0;
  font-size: 1.8rem;
  color: #222;
  font-weight: 700;
  letter-spacing: 1px;
}

.header-icons {
  position: absolute;
  right: 40px;
  display: flex;
  gap: 20px;
}

.header-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: #222;
  transition: transform 0.2s;
}

.icon-circle {
  width: 50px;
  height: 50px;
  background-color: white;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  margin-bottom: 4px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.icon-circle img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.header-item span {
  font-size: 0.75rem;
  font-weight: 700;
  color: #333;
}

/* Page Content Padding */
.page-content {
  flex: 1;
  background-color: var(--bg-color);
}

/* Footer Refinement */
footer {
  background-color: #f8fcfb;
  color: #333;
  padding: 40px 0 20px;
  border-top: 1px solid #e0f0ed;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.footer-top {
  display: flex;
  justify-content: center;
  margin-bottom: 25px;
}

.footer-links {
  display: flex;
  align-items: center;
  gap: 15px;
}

.footer-links a {
  color: #444;
  text-decoration: none;
  font-weight: 600;
  font-size: 1rem;
}

.divider {
  color: #ccc;
  margin: 0 5px;
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.copyright {
  color: #888;
  font-size: 0.9rem;
}

.footer-contacto {
  display: flex;
  align-items: center;
  gap: 12px;
  background: white;
  padding: 10px 20px;
  border-radius: 30px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  cursor: pointer;
  border: 1px solid #e0f0ed;
}

.contact-circle {
  width: 35px;
  height: 35px;
  background-color: #7ed9c7;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.contact-circle img {
  width: 18px;
  height: 18px;
  filter: brightness(0) invert(1);
}

.contact-text {
  font-weight: 800;
  color: #333;
  font-size: 1rem;
}

/* Response Widget Styling Overrides */
.n8n-chat-widget {
  z-index: 2000 !important;
  --chat-primary-color: #6bc7b5 !important;
  --chat-bubble-color: #6bc7b5 !important;
  --chat-button-background: #6bc7b5 !important;
}

/* Forzar el color del botón flotante y el icono interior */
[class*="n8n-chat"] {
  --n8n-chat-primary-color: #6bc7b5 !important;
  --n8n-chat-bubble-color: #6bc7b5 !important;
}

.n8n-chat-widget-bubble,
[class*="chat-widget-bubble"],
[class*="chat-bubble"],
.n8n-chat-button {
  background-color: #6bc7b5 !important;
}

/* Forzar la cabecera */
.n8n-chat-widget-header,
[class*="chat-widget-header"],
[class*="chat-header"] {
  background-color: #2c3e50 !important;
}

.n8n-chat-widget-bubble svg,
[class*="chat-bubble"] svg {
  fill: white !important;
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .brand-title {
    font-size: 1.5rem;
  }
}
</style>

