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

## [2026-02-23] - Gestión de Productos (Administración)

### Backend (Laravel)
- **CheckAdmin Middleware:** Nuevo middleware para restringir el acceso a rutas sensibles solo a usuarios con rol `admin`.
- **AdminProductController:** Implementada la lógica para:
  - Crear productos manualmente (generación automática de SKU).
  - Importar productos masivamente desde archivos **JSON** y **Excel** (.xlsx, .csv).
- **ProductImport:** Integración con la librería `Maatwebsite/Excel` para el mapeo de columnas de archivos externos a la base de datos.
- **Rutas API:** Definición de nuevos endpoints protegidos por `auth:sanctum` y el nuevo middleware `admin`.

### Frontend (Vue.js)
- **Store de Autenticación:** Añadido getter `isAdmin` para verificar permisos de administrador en toda la aplicación.
- **Vue Router:** Configurada una nueva ruta protegida `/admin/products` con navegación restringida mediante guards.
- **Barra Lateral (App.vue):** Añadido un nuevo enlace directo al panel de administración, visible únicamente para usuarios con rol `admin`.
- **Vista de Administración (ProductManagement.vue):** Nueva interfaz independiente que incluye:
  - Formulario de creación manual (Nombre, Precio, Descripción, Categoría, Imagen).
  - Zona de carga de archivos con soporte para arrastrar y soltar (JSON/Excel).
  - Visualización de estados de carga y mensajes de éxito/error.

### Mejoras y Correcciones (Debugging)
- **Mapeo Automático de Categorías:** Se ha mejorado la lógica de importación para que acepte el nombre de la categoría (texto) en lugar de solo el ID numérico. El sistema ahora busca la categoría por nombre y, si no existe, la crea automáticamente.
- **Validación de Datos en Importaciones:** Se ha reforzado el sistema de importación para evitar datos inconsistentes. Ahora se validan el precio y el stock, descartando automáticamente cualquier fila o elemento con valores negativos (preu < 0 o estoc < 0) y generando una advertencia en los logs del sistema.
- **Soporte de Formatos de Archivo:** El sistema de importación ahora acepta archivos **JSON**, **Excel (.xlsx)**, **CSV** y **LibreOffice Calc (.ods)**.
- **Edición de Productos por Administradores:**
  - Se ha añadido un botón de **"EDITAR PRODUCTO"** en la vista de detalle (`ProductDetail.vue`), visible solo para administradores.
  - El botón redirige al panel de administración cargando automáticamente los datos actuales en el formulario.
  - Se han implementado los endpoints `GET /admin/products/{id}` y `PUT /admin/products/{id}` para la obtención y actualización segura de datos.

