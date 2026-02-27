# Log de Proyecto - GrupJJ

## 🟢 1. RESUMEN DE INFRAESTRUCTURA (Sprint 4 - Completado)
**Objetivo:** Desplegar un servidor seguro en AWS con Web, FTP y Backups.

### Recuperación y Acceso SSH:
- Recuperamos el acceso a la instancia AWS mediante montaje de disco y corrección de `authorized_keys`.
- **Blindaje SSH:** Prohibido root, prohibido password, banner de bienvenida personalizado y corrección del parámetro `UsePAM yes`.

### Servidor Web (Apache + HTTPS):
- Configuramos 3 VirtualHosts: `app` (producción), `backup` (protegido con contraseña) y `test`.
- Solucionamos el problema de dominio usando DuckDNS (`grupjj-app.duckdns.org`, etc.).
- Generamos certificados SSL reales con Let's Encrypt (Certbot) para tener HTTPS y candado verde 🔒.

### Servidor FTP (Vsftpd):
- Configuramos FTP seguro (FTPS) sobre TLS.
- Habilitamos modo pasivo para atravesar el firewall de AWS (puertos 30000-30050).
- Creamos usuarios enjaulados (chroot) para que cada uno (`app`, `backup`, `test`) solo vea su carpeta.

### Automatización (Backups):
- Creamos un script Bash (`/usr/local/bin/backup_script.sh`) que comprime la web, le pone fecha y borra copias antiguas (>7 días).
- Programamos una tarea Cron para ejecutarlo automáticamente.

---

## 🟠 2. RESUMEN DE DESARROLLO (Sprint 5/6 - En curso)
**Objetivo:** Añadir funcionalidades avanzadas al Backend Laravel (OAuth2 y Swagger) y Frontend Vue.

### 📁 Archivos y Código Modificado

#### A. Dependencias instaladas
- `laravel/socialite`: Librería oficial para gestionar el Login con Google.
- `darkaonline/l5-swagger`: Librería para generar la documentación de la API automáticamente.

#### B. Base de Datos (Migraciones)
- **Nuevo archivo:** `database/migrations/2026_02_27_153405_add_google_id_to_users_table.php`
- **Cambio:** Añadida columna `google_id` y `avatar` a la tabla `users`.
- **Cambio:** Columna `password` hecha nullable para usuarios de Google.

#### C. Configuración (.env y Config)
- **Google OAuth:** Añadidos `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, y `GOOGLE_REDIRECT_URI`.
- **Frontend URL:** Configurado `FRONTEND_URL=http://localhost:5174` para evitar conflictos de puerto.
- **Robustez:** Movida la configuración de la URL a `config/services.php` para evitar problemas con `env()` y caché.

#### D. Rutas (routes/api.php)
- `GET /api/oauth/google/redirect`: Inicia el flujo con Google.
- `GET /api/oauth/google/callback`: Gestiona la vuelta de Google y emite el token Sanctum.
- `GET /api/me`: Documentado y funcional para obtener el perfil del usuario.

#### E. Controladores y Documentación
- **Google Controller:** Creado `app/Http/Controllers/Auth/GoogleController.php`.
- **Swagger Metadata:** Creado `app/SwaggerMetadata.php` para centralizar la configuración global de la API.
- **Atributos PHP 8:** Convertida toda la documentación Swagger al formato de Atributos de PHP 8 (necesario para Swagger-php 6.0).

#### F. Frontend (Vue.js)
- **Componente Login:** Creado `GoogleLoginButton.vue` e integrado en las vistas de Login y Registro.
- **Callback View:** Creada `AuthCallback.vue` para procesar el token, guardarlo en `localStorage` y redirigir al Home.
- **Estabilidad de Puerto:** Forzado el puerto **5174** en `vite.config.js` (`strictPort: true`) para evitar conflictos con Laravel Sail (Docker) que ocupa el 5173.

---

## 📝 Próximos pasos
1. **Docker:** Finalizar el `Dockerfile` para Vue y ajustar el `docker-compose.yml` para separar servicios de forma limpia.
2. **Validación:** Pruebas finales de flujo completo en entorno de desarrollo.
3. **Despliegue:** Preparar el paso de estos cambios al servidor AWS.
