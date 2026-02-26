# 🚀 Planificación de Sprints y Entregas - JJ-Security

Este documento detalla el cumplimiento de los requisitos del cliente a lo largo de las distintas iteraciones del proyecto, vinculando las metas académicas con la implementación técnica real.

---

## 🛠️ Iteración 1: Entorno, escaparate i contacte
**Estado:** Finalizado ✅

### Requisitos del cliente:
- **Configuración inicial:** Preparación de entorno local (PHP, IDE) y control de versiones en GitHub.
- **Metodología de trabajo:** Definición de estrategias para evitar duplicidad de trabajo.
- **Planificación:** Creación de tablero Kanban y cronograma.
- **Prevención de riesgos:** Identificación de riesgos laborales (ver [riesgosLaborales.md](riesgosLaborales.md)).
- **Maquetación básica:** Página de inicio atractiva y corporativa (HTML/CSS/JS).
- **Formulario de contacto:** Validación en cliente (JS/Regex) y servidor.
- **Despliegue inicial:** Hosting compartido accesible online.

### Implementación Técnia:
- Estructura base del repositorio creada.
- Identificación de 7 riesgos clave y plan de prevención (ver [planRiesgosLaborales.md](planRiesgosLaborales.md)).
- Diseño de la identidad corporativa "JJ-Security".

---

## 🔐 Iteración 2: Autenticación i gestió de productes
**Estado:** Finalizado ✅

### Requisitos del cliente:
- **Importación de productos:** Carga masiva desde Excel/JSON (ver [LOG.md](LOG.md#2026-02-23)).
- **Registro y Login:** Sistema seguro con perfiles de usuario y protección de contraseñas.
- **Interacción:** Sistema de comentarios y valoraciones dinámicas.
- **Backups:** Transferencia segura mediante SFTP y copias periódicas.
- **Arquitectura e-commerce:** Navegación clara, buscador, filtros y carrito visible.

### Implementación Técnica:
- Integración de `Maatwebsite/Excel` en el backend para importaciones.
- Implementación de `authStore` con Pinia para gestión de sesiones.
- Desarrollo de `Checkout.vue` dinámico que diferencia entre invitados y registrados.

---

## 📱 Iteración 3: Disseny responsiu i backend Laravel
**Estado:** Finalizado ✅

### Requisitos del cliente:
- **Diseño Responsivo:** Uso de CSS Grid y Media Queries para tablets y móviles.
- **Backend Robusto:** Reimplementación total con Laravel (Patrón MVC + API REST).
- **Garantía de Calidad:** Tests básicos de funcionalidad y documentación del código.

### Implementación Técnica:
- Migración exitosa a Laravel 11.
- Diseño "Mobile First" aplicado en componentes core como el Header y la Sidebar.
- Documentación técnica inicial de la API.

---

## ⚙️ Iteración 4: Client SPA amb Vue i control de rols
**Estado:** Finalizado ✅

### Requisitos del cliente:
- **Vue.js SPA:** Navegación fluida sin recarga de página (Single Page Application).
- **Integración API:** Uso de Axios para comunicación asíncrona y gestión de tokens.
- **Control de Roles:** Diferenciación entre Admin y Usuario (ver [rols.md](rols.md)).

### Implementación Técnica:
- Frontend reconstruido con Vue 3 y Vite.
- Middleware `CheckAdmin` en Laravel para proteger rutas críticas.
- Panel de administración dinámico para gestión de stock.

---

## 🌐 Iteración 5: Integracions externes i processos asíncrons
**Estado:** En Progreso 🔄

### Requisitos del cliente:
- **Integración Externa:** Autenticación OAuth2 (Google/Facebook).
- **Swagger Documentation:** API documentada de forma interactiva.
- **Procesos Asíncronos:** Uso de Laravel Queues para tareas pesadas (emails, informes).
- **Mejoras Frontend:** Filtros avanzados, paginación y validación con Vee-Validate/Yup.
- **Docker Centralizado:** Uso de `docker-compose` para orquestar la App.

### Implementación Técnica:
- Configuración de `compose.yaml` unificado (Backend, Frontend, MySQL, n8n).
- Integración de Chatbot mediante n8n (Asistente Virtual).
- Implementación de **Modo Oscuro** global y persistente.
- Filtros de categorías dinámicos en la vista de productos.

---

## 🚀 Iteración 6: Desplegament final i lliurament del producte
**Estado:** Planificado (Última Fase) 📋

### Requisitos del cliente:
- **Excelencia Visual:** Consistencia, accesibilidad profesional e imágenes optimizadas.
- **Digitalización e IA:** Implementación de recomendaciones inteligentes (datos).
- **Sostenibilidad:** Integración de criterios ASG y ecodiseño (ver [Sustainability.vue](../frontend/src/views/Sustainability.vue)).
- **Despliegue Producción:** DNS propio (`pigrupox.ddaw.es`), CI/CD y entornos aislados.
- **Seguridad Máxima:** Configuración de HTTPS con certificados SSL (Let's Encrypt).
- **Manual y Soporte:** Manual de usuario y herramientas de ayuda contextual.

### Implementación Técnica realizada:
- Vista de Sostenibilidad completada con criterios ecológicos.
- Asistente virtual configurado con respuesta inteligente.
- Optimización de carga estética y UI JJ-Security.

---
*Última actualización: 2026-02-25*
