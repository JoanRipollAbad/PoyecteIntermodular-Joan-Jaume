# JJ-Security: Sistema de Gestión de Tienda Online de Seguridad

Este proyecto es una aplicación web integral para la venta y gestión de productos de seguridad (cámaras, sensores, etc.), desarrollada como parte del Proyecto Intermodular 2DAW 2025-2026.

## 🏗️ Arquitectura del Sistema

El proyecto sigue una arquitectura desacoplada:
- **Frontend:** Vue.js 3 + Vite + Tailwind CSS.
- **Backend:** Laravel 11 (API REST) + MySQL.
- **Automatización:** n8n (Integración de chatbots y flujos de trabajo).
- **Documentación:** Swagger (OpenAPI) generada dinámicamente.

## 🐳 Entorno de Desarrollo (Docker)

La aplicación está completamente dockerizada para garantizar la paridad entre entornos.

### Requisitos
- Docker y Docker Compose instalados.

### Cómo arrancar
1. Clona el repositorio.
2. Copia el archivo de entorno: `cp .env.example .env` (en root, frontend y backend).
3. Levanta los servicios:
   ```bash
   docker compose up -d
   ```
4. El frontend estará disponible en `http://localhost:5174`.
5. El backend estará disponible en `http://localhost:8000`.
6. Swagger estará disponible en `http://localhost:8000/api/documentation`.

## 🚀 Entornos

- **Desarrollo:** Gestionado mediante Docker Compose local.
- **Producción:** Desplegado en AWS (Instancia EC2 con Apache, SSL y Backups automáticos).
- **Acceso:** Mediante DNS dinámico (ej: projecteXX.ddaw.es).

## 📄 Documentación Ténica

- **API:** Consulta `/api/documentation` para ver los endpoints CRUD, autenticación Sanctum y OAuth2 con Google.
- **Logs:** El historial detallado de cambios se encuentra en `docs/LOG.md`.
- **Manual:** Consulta `docs/USER_MANUAL.md` para una guía de uso de la tienda.
