# Registro de Cambios - JJ-Security

Este archivo documenta los cambios realizados durante el desarrollo y las pruebas de las funcionalidades de login, registro y perfil.

## [2026-02-20] - Mejoras en Autenticación y Checkout

### Backend (Laravel)
- **AuthController.php:**
  - Se ha actualizado la validación del `register` para que el email sea más estricto (`email:rfc,dns`), evitando registros con formatos inválidos como `usuario@gmail`.
  - Se han implementado los métodos `me()` (para obtener datos del usuario actual) y `updateProfile()` (para permitir la edición de datos del perfil).
  - Se ha añadido lógica para actualizar la contraseña solo si se proporciona en el request.

### Frontend (Vue.js)
- **Checkout.vue:**
  - Se ha hecho dinámica la vista de pago. Ahora detecta si el usuario está autenticado usando el `authStore.isAuthenticated`.
  - Si el usuario está registrado, muestra sus datos y un botón de confirmación directa.
  - Si el usuario es invitado, muestra el formulario de datos de envío y pago.
  - Se ha eliminado el botón de simulación "Alternar simulación" para usar el estado real de la aplicación.

---

## Cómo funciona la comunicación con la API

### 1. Configuración Base (frontend/src/api/index.js)
Usamos **Axios** como cliente HTTP. En el archivo `index.js`, configuramos la `baseURL` (que apunta a `http://localhost/api`) y añadimos **interceptores**:
- **Peticiones:** Si hay un token guardado en `localStorage`, se añade automáticamente a la cabecera `Authorization: Bearer <token>`.
- **Respuestas:** Si el servidor devuelve un error 401 (no autorizado), limpiamos el token y redirigimos al login.

### 2. Tienda de Autenticación (frontend/src/stores/auth.js)
Usamos **Pinia** para gestionar el estado global. El `authStore` tiene acciones como `login` y `register` que llaman a `api.post('/login', ...)` y `api.post('/register', ...)`.
- Al recibir una respuesta exitosa, guardamos el token y los datos del usuario.

### 3. Rutas del Backend (backend/routes/api.php)
El backend define las rutas API que llaman al `AuthController`:
- `Route::post('/register', ...)`
- `Route::post('/login', ...)`
- Rutas protegidas dentro del middleware `auth:sanctum` como `/me` y `/profile`.
