<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from './stores/auth'
import { useCartStore } from './stores/cart'
import { useSettingsStore } from './stores/settings'
import { onMounted, ref, watch } from 'vue'

const authStore = useAuthStore()
const cartStore = useCartStore()
const settingsStore = useSettingsStore()
const isSidebarExpanded = ref(false)

onMounted(() => {
  // Apply initial theme
  settingsStore.applyTheme()

  // No longer creating style element here, consolidated in <style> block below

  // 2. Initialize Chat
  const script = document.createElement('script');
  script.type = 'module';
  script.innerHTML = `
    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';
    createChat({
    webhookUrl: 'http://localhost:5678/webhook/29f0abd7-0d17-4608-9b59-051cbd9e43ed/chat',
    initialMessages: [
      '¡Hola! 👋',
      'Bienvenido a JJ-Security. ¿En qué podemos ayudarte?'
    ],
    i18n: {
      en: {
        title: 'JJ-Security',
        subtitle: 'En línea 24/7',
        inputPlaceholder: 'Escribe tu duda aquí...',
      },
      es: {
        title: 'JJ-Security',
        subtitle: 'En línea 24/7',
        inputPlaceholder: 'Escribe tu duda aquí...',
      }
    }
  });
  `;
  document.body.appendChild(script);

  // 3. NUCLEAR: Shadow DOM support for the red bubble
  const forceStyles = () => {
    const applyToRoot = (root) => {
      // Color properties to override
      const styles = `
        :host { --n8n-chat-primary-color: #6bc7b5 !important; --n8n-chat-bubble-color: #6bc7b5 !important; }
        .n8n-chat-widget-bubble, [class*="chat-bubble"], .n8n-chat-button, [class*="launcher"] { 
          background-color: #6bc7b5 !important; 
          background: #6bc7b5 !important;
        }
        svg { fill: white !important; }
      `;

      // Try searching for el
      const bubbles = root.querySelectorAll('.n8n-chat-widget-bubble, [class*="chat-bubble"], .n8n-chat-button, [class*="launcher"]');
      bubbles.forEach(b => {
        b.style.setProperty('background-color', '#6bc7b5', 'important');
        b.style.setProperty('background', '#6bc7b5', 'important');
      });

      // Recurse into children's shadow roots
      root.querySelectorAll('*').forEach(el => {
        if (el.shadowRoot) applyToRoot(el.shadowRoot);
      });
    };

    applyToRoot(document);
  };

  setInterval(forceStyles, 500);
  const observer = new MutationObserver(forceStyles);
  observer.observe(document.body, { childList: true, subtree: true });
});

// Watch for language changes to reload chat or update UI if needed
// For now, simple reactivity in templates will handle most cases
</script>

<template>
  <div class="app-container" :class="{ 'dark-mode': settingsStore.darkMode }">
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
        <RouterLink to="/settings" class="icono-menu" aria-label="Ajustes">
          <div class="icono-contenido">
            <div class="icon-wrapper">
              <img src="/img/ajustes.jpg" alt="" aria-hidden="true" />
            </div>
            <span class="menu-text">{{ settingsStore.t('settings') }}</span>
          </div>
        </RouterLink>
        
        <RouterLink to="/filters" class="icono-menu" aria-label="Filtros y Categorías">
          <div class="icono-contenido">
            <div class="icon-wrapper">
              <img src="/img/filtros.jpg" alt="" aria-hidden="true" />
            </div>
            <span class="menu-text">{{ settingsStore.t('filters') }}</span>
          </div>
        </RouterLink>
        
        <RouterLink :to="authStore.isAuthenticated ? '/profile' : '/login'" class="icono-menu" aria-label="Mi Perfil">
          <div class="icono-contenido">
            <div class="icon-wrapper">
              <img src="/img/usuario.jpg" alt="" aria-hidden="true" />
            </div>
            <span class="menu-text">{{ authStore.isAuthenticated ? settingsStore.t('profile') : settingsStore.t('login') }}</span>
          </div>
        </RouterLink>

        <!-- Admin Link (Consolidated) -->
        <RouterLink v-if="authStore.isAdmin" to="/admin/products" class="icono-menu" aria-label="Administración">
          <div class="icono-contenido">
            <div class="icon-wrapper" style="background-color: #333;">
              <img src="/img/ajustes.jpg" alt="" aria-hidden="true" style="filter: invert(1);" />
            </div>
            <span class="menu-text">Admin</span>
          </div>
        </RouterLink>
      </div>

      <RouterLink to="/support" class="icono-menu ayuda" aria-label="Ayuda y Soporte">
        <div class="icono-contenido">
          <div class="icon-wrapper circle-btn">
            <img src="/img/mingcute_phone-fill.svg" alt="" aria-hidden="true" />
          </div>
          <span class="menu-text">{{ settingsStore.t('help') }}</span>
        </div>
      </RouterLink>
    </aside>

    <div class="main-content" :class="{ 'sidebar-expanded': isSidebarExpanded }">
      <header role="banner">
        <div class="header-container">
          <h1 class="brand-title">JJ-Security</h1>
          <nav v-if="!$route.path.includes('/login') && !$route.path.includes('/register')" class="header-icons" aria-label="Accesos rápidos">
            <RouterLink to="/cart" class="header-item" aria-label="Ver mi carrito">
              <div class="icon-circle cart-icon-wrapper">
                <img src="/img/carrito.jpg" alt="" aria-hidden="true">
                <span v-if="cartStore.totalItems > 0" class="cart-badge">{{ cartStore.totalItems }}</span>
              </div>
              <span>{{ settingsStore.t('cart') }}</span>
            </RouterLink>
            <RouterLink :to="authStore.isAuthenticated ? '/profile' : '/login'" class="header-item" aria-label="Acceder a mi perfil">
              <div class="icon-circle">
                <img src="/img/usuario.jpg" alt="" aria-hidden="true">
              </div>
              <span>{{ authStore.isAuthenticated ? settingsStore.t('profile') : settingsStore.t('login') }}</span>
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
            <RouterLink to="/sustainability" class="footer-contacto" aria-label="Compromiso ecológico - Política de Sostenibilidad" title="Ver Política de Sostenibilidad">
              <div class="contact-circle recycle-gradient">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                  <path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,8 17,8 17,8Z" />
                </svg>
              </div>
            </RouterLink>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<style>
/* 1. ESTILOS GLOBALES Y FUENTES */
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

  /* VARIABLES ESPECÍFICAS PARA EL CHATBOT DE N8N */
  --chat--color--primary: #6bc7b5;
  --chat--color--primary-shade-50: #5db0a0;
  --chat--color--primary-shade-100: #4f9689;
  --chat--header--background: #bcd9d6; /* Mismo color que tu header de la web */
  --chat--header--color: #222222;
  --chat--bubble--background: #6bc7b5;
  --chat--bubble--color: #ffffff;
}

/* 2. MODO OSCURO */
.dark-mode {
  --bg-color: #121212;
  --card-bg: #1e1e1e;
  --text-color: #e0e0e0;
  --secondary-color: #2c3e50;
}

.dark-mode body {
  background-color: var(--bg-color);
  color: var(--text-color);
}

.dark-mode header, .dark-mode .barra-lateral {
  background-color: #1e292d !important;
}

.dark-mode .brand-title {
  color: #ffffff;
  background: none;
  -webkit-text-fill-color: initial;
}

.dark-mode .menu-text, .dark-mode .header-item span {
  color: #e0e0e0;
}

.dark-mode .icon-circle, .dark-mode .icon-wrapper {
  background-color: #2c3e50;
  border-color: #2c3e50;
}

.dark-mode footer {
  background-color: #1a1a1a;
  border-top-color: #333;
}

/* 3. DISEÑO BASE Y ACCESIBILIDAD */
body {
  margin: 0;
  padding: 0;
  font-family: 'Lato', sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
  overflow-x: hidden;
}

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

.skip-link:focus { top: 0; }

:focus-visible {
  outline: 3px solid var(--primary-color) !important;
  outline-offset: 4px;
}

.app-container {
  display: flex;
  min-height: 100vh;
}

/* 4. BARRA LATERAL (SIDEBAR) */
.barra-lateral {
  width: var(--sidebar-width);
  background-color: #bcd9d6;
  display: flex;
  flex-direction: column;
  align-items: center; 
  padding: 20px 0;
  position: fixed;
  height: 100vh;
  z-index: 1000;
  transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

.barra-lateral.expanded { width: var(--sidebar-expanded-width); }

.logo-sidebar {
  width: 100%;
  display: flex;
  justify-content: center;
  margin-bottom: 50px;
}

.logo-img {
  width: 75px;
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
  color: #333;
  display: flex;
  justify-content: center;
}

.icono-contenido {
  display: flex;
  flex-direction: column;
  align-items: center;
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
  width: 55px;
  height: 55px;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  border: 2px solid white;
  flex-shrink: 0;
  overflow: hidden;
}

.icon-wrapper img { width: 100%; height: 100%; object-fit: cover; }

.menu-text {
  font-size: 13px;
  color: #222;
  font-weight: 700;
  margin-top: 6px;
  text-transform: capitalize;
  white-space: nowrap;
}

.barra-lateral.expanded .menu-text { font-size: 18px; margin-top: 0; }

.ayuda { margin-top: auto; margin-bottom: 20px; }

/* 5. CONTENIDO PRINCIPAL Y HEADER */
.main-content {
  flex: 1;
  margin-left: var(--sidebar-width);
  display: flex;
  flex-direction: column;
  transition: margin-left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
  min-height: 100vh;
}

header {
  background-color: #bcd9d6;
  height: var(--header-height);
  width: 100%;
  display: flex;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 900;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.dark-mode header {
  border-bottom-color: rgba(255, 255, 255, 0.05);
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
  font-size: 2.5rem;
  font-weight: 800;
  color: #000;
  letter-spacing: -1.5px;
  margin: 0;
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
}

.icon-circle {
  width: 50px;
  height: 50px;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  position: relative;
  overflow: visible;
}

.icon-circle img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.header-item span {
  font-size: 0.75rem;
  font-weight: 700;
  color: #333;
}

.cart-badge {
  position: absolute;
  top: -5px; right: -5px;
  background-color: #6bc7b5;
  color: white;
  font-size: 0.7rem;
  width: 20px; height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
}

.page-content { flex: 1; }

/* 6. FOOTER */
footer {
  background-color: #f8fcfb;
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
  transition: color 0.2s;
}

.footer-links a:hover {
  color: var(--primary-color);
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
  justify-content: center;
  background: white;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  cursor: pointer;
  border: 1px solid #e0f0ed;
  transition: transform 0.2s;
}

.footer-contacto:hover {
  transform: scale(1.1);
}

.contact-circle {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 35px;
  height: 35px;
  border-radius: 50%;
}

.recycle-gradient {
  background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
}

/* 7. PERSONALIZACIÓN DEL CHATBOT N8N (CORRECCIÓN DE COLORES) */

/* Contenedor principal y variables de respaldo */
#n8n-chat-widget-container, 
.n8n-chat-widget {
  --chat--color--primary: #6bc7b5 !important;
  --chat--header--background: #bcd9d6 !important;
}

/* Color de la burbuja flotante (launcher) - Elimina el rojo */
.n8n-chat-widget-bubble, 
[class*="chat-widget-bubble"],
.n8n-chat-button {
  background-color: #6bc7b5 !important;
  border: none !important;
}

/* Encabezado del chat - Color JJ-Security */
.n8n-chat-widget-header, 
[class*="chat-header"] {
  background-color: #bcd9d6 !important;
  color: #222 !important;
}

/* Títulos del chat */
.n8n-chat-widget-header-title,
.n8n-chat-widget-header-subtitle {
  color: #222 !important;
}

/* Burbujas de mensaje del USUARIO */
.n8n-chat-widget-message-bubble--user {
  background-color: #6bc7b5 !important;
  color: white !important;
}

/* Burbujas de mensaje del BOT (Nathan) */
.n8n-chat-widget-message-bubble--bot {
  background-color: #f0f7f6 !important;
  border: 1px solid #6bc7b5 !important;
  color: #333 !important;
}

/* Input y botón de enviar */
.n8n-chat-widget-input-container button svg {
  fill: #6bc7b5 !important;
}

/* Ventana del chat */
.n8n-chat-widget-window {
  border-radius: 15px !important;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1) !important;
  overflow: hidden;
}

/* Quitar el "Powered by n8n" si se desea un look más limpio */
.n8n-chat-widget-footer {
  display: none !important;
}

/* 8. RESPONSIVE */
@media (max-width: 992px) {
  .brand-title { font-size: 1.8rem; }
  .header-container { padding: 0 20px; }
  .header-icons { right: 20px; gap: 10px; }
}

@media (max-width: 768px) {
  :root {
    --sidebar-width: 70px;
  }
}

@media (max-width: 600px) {
  .header-container {
    justify-content: space-between;
    padding: 0 15px;
  }
  
  .brand-title {
    font-size: 1.4rem;
    letter-spacing: -0.5px;
  }

  .header-icons {
    position: static;
    gap: 8px;
  }

  .header-item span {
    display: none;
  }

  .icon-circle {
    width: 40px;
    height: 40px;
  }

  .logo-img {
    width: 45px;
    height: 45px;
  }

  .icon-wrapper {
    width: 45px;
    height: 45px;
  }

  .menu-text {
    font-size: 11px !important;
  }

  /* Bloquear expansión del aside en móvil */
  .barra-lateral.expanded {
    width: var(--sidebar-width) !important;
  }
  
  .main-content.sidebar-expanded {
    margin-left: var(--sidebar-width) !important;
  }

  .barra-lateral.expanded .icono-contenido {
    flex-direction: column !important;
    padding-left: 0 !important;
    justify-content: center !important;
    gap: 0 !important;
  }

  .barra-lateral.expanded .menu-text {
    font-size: 11px !important;
    margin-top: 6px !important;
  }
}
</style>
