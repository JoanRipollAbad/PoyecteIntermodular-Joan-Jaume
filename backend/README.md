# JJ-Security — Tienda de Sistemas de Vigilancia

API REST para la tienda online JJ-Security, desarrollada con **Laravel 12** sobre Docker/Sail, con frontend **Vue 3** conectado mediante API y autenticación **Sanctum**.

---

## 📐 Arquitectura del Proyecto

```
┌──────────────────┐         HTTP/JSON          ┌──────────────────────┐
│                  │  ◄──────────────────────►   │                     │
│   Vue 3 (SPA)    │    Axios + Bearer Token     │   Laravel 12 API    │
│   Vite 7.3       │                             │   Sanctum Auth      │
│   Pinia Store    │                             │   Eloquent ORM      │
│   Puerto :5173   │                             │   Puerto :80        │
│                  │                             │                     │
└──────────────────┘                             └─────────┬───────────┘
                                                           │
                                                  ┌────────▼────────┐
                                                  │   MySQL 8.4     │
                                                  │   Puerto :3306  │
                                                  └────────┬────────┘
                                                           │
┌──────────────────┐                              ┌────────▼────────┐
│    n8n Bot       │ ◄────── Sail Network ──────► │  Docker (Sail)  │
│   Puerto :5678   │                              │  Red interna    │
└──────────────────┘                              └─────────────────┘
```

### Modelos y relaciones

```
User ──┐
       │ hasMany
       ▼
   Comment ──── belongsTo ──── Product ──── belongsTo ──── Categoria
       ▲                         │
       │                    hasMany │
       └─────────────────────────┘
```

---

## 🛠️ Stack Tecnológico

| Componente | Versión |
|---|---|
| Laravel | 12.46.0 |
| PHP | 8.4.17 |
| MySQL | 8.4 |
| Sanctum | 4.0 |
| Vue.js | 3.5.28 |
| Vite | 7.3.1 |
| Pinia | 3.0.4 |
| Vue Router | 5.0.2 |
| Axios | 1.13.5 |
| n8n | latest |

---

## 🚀 Instalación

### Requisitos previos
- Docker y Docker Compose
- Node.js ≥20.19 o ≥22.12

### Backend (Laravel + Sail)

```bash
# 1. Clonar el repositorio
git clone https://github.com/JoanRipollAbad/PoyecteIntermodular-Joan-Jaume.git
cd PoyecteIntermodular-Joan-Jaume

# 2. Copiar las variables de entorno
cp backend/.env.example backend/.env
# Editar backend/.env y configurar:
#   DB_HOST=mysql
#   DB_DATABASE=jj_security
#   DB_USERNAME=joan
#   DB_PASSWORD=password

# 3. Instalar dependencias y levantar Docker
cd backend
composer install
./vendor/bin/sail up -d

# 4. Generar clave y ejecutar migraciones
sail artisan key:generate
sail artisan migrate --seed
```

### Frontend (Vue 3)

```bash
cd frontend

# 1. Crear .env con la URL de la API
echo "VITE_API_URL=http://localhost/api" > .env

# 2. Instalar dependencias y arrancar
npm install
npm run dev
```

Accesible en `http://localhost:5173`

---

## 📡 Endpoints de la API

### Rutas públicas

| Método | Ruta | Descripción |
|---|---|---|
| `POST` | `/api/register` | Registrar usuario |
| `POST` | `/api/login` | Iniciar sesión (devuelve token) |
| `GET` | `/api/products` | Listar productos con categoría |
| `GET` | `/api/products/{id}` | Detalle de producto con comentarios |
| `GET` | `/api/categorias` | Listar categorías |
| `GET` | `/api/products/{id}/comments` | Listar comentarios de un producto |
| `POST` | `/api/contacto` | Enviar formulario de contacto |

### Rutas protegidas (requieren `Authorization: Bearer <token>`)

| Método | Ruta | Descripción |
|---|---|---|
| `POST` | `/api/logout` | Cerrar sesión |
| `POST` | `/api/products/{id}/comments` | Crear comentario |
| `DELETE` | `/api/comments/{id}` | Eliminar comentario (solo propietario) |

### Ejemplos de uso

#### Registro
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Joan","email":"joan@test.com","password":"123456"}'

# Respuesta:
# {"message":"Usuari creat correctament","token":"1|abc123...","user":{"id":1,"name":"Joan","email":"joan@test.com","rol":"usuario"}}
```

#### Login
```bash
curl -X POST http://localhost/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"joan@test.com","password":"123456"}'

# Respuesta:
# {"token":"2|xyz789...","user":{"id":1,"name":"Joan","email":"joan@test.com","rol":"usuario"}}
```

#### Crear comentario (autenticado)
```bash
curl -X POST http://localhost/api/products/1/comments \
  -H "Authorization: Bearer 2|xyz789..." \
  -H "Content-Type: application/json" \
  -d '{"text":"Gran producto!","puntuacio":5}'

# Respuesta (201):
# {"id":1,"text":"Gran producto!","puntuacio":5,"user":{"id":1,"name":"Joan"}}
```

---

## 🤖 Integración con n8n

El proyecto incluye un chatbot integrado mediante **n8n** que funciona como asistente de atención al cliente.

- **Puerto**: `5678` (en Docker)
- **Funcionalidad**: El bot responde preguntas sobre productos, ayuda a generar presupuestos personalizados y proporciona soporte técnico.
- **Implementación**: Widget de chat embebido en el frontend Vue, conectado al webhook de n8n a través de la red interna de Docker (Sail).
- **Usuario bot**: `bot@jjsecurity.com` (rol `bot`) con acceso a la API para consultar productos.

---

## 🧪 Tests

El proyecto incluye **23 tests automatizados** con **133 aserciones**:

| Suite | Tests | Cobertura |
|---|---|---|
| `AuthControllerTest` | 9 | Registro, login, logout, validaciones |
| `ProductControllerTest` | 5 | Listado, detalle, 404 |
| `CommentControllerTest` | 8 | CRUD, autenticación, propiedad |
| `ExampleTest` | 1 | Health check |

### Ejecutar tests

```bash
# Dentro del contenedor
sail artisan test

# Solo tests de Feature
sail artisan test --testsuite=Feature
```

---

## 📋 Sprints Completados

### Iteración 1 — Entorno, escaparate y contacto
- ✅ Entorno Docker configurado con Sail (Laravel + MySQL + n8n)
- ✅ Página inicial maquetada como escaparate de productos de seguridad
- ✅ Formulario de contacto con validación en cliente (JavaScript) y servidor (PHP)
- ✅ Despliegue de documentación con MkDocs en GitHub Pages
- ✅ Identificación de riesgos laborales (ergonomía, fatiga visual)

### Iteración 2 — Autenticación y gestión de productos
- ✅ Importación de productos desde datos generados (SKU con prefijo `SEC-`)
- ✅ Sistema de autenticación (registro/login) con contraseñas cifradas (bcrypt)
- ✅ Comentarios y valoraciones de productos (1-5 estrellas, solo usuarios autenticados)
- ✅ Categorías: Cámaras, Cerraduras, Sensores, Alarmas, Servicios
- ✅ Seeder con 20 productos, 5 categorías, 12 usuarios (admin + bot incluidos)

### Iteración 3 — Diseño responsivo y backend Laravel
- ✅ Reimplementación del backend con Laravel 12 (API REST, patrón MVC)
- ✅ Frontend Vue 3 conectado a la API con Axios y Pinia
- ✅ Autenticación con Sanctum (tokens Bearer)
- ✅ Tests PHPUnit de todas las funcionalidades (23 tests, 133 aserciones)
- ✅ Chatbot con n8n integrado para soporte al cliente
- ✅ Diseño responsivo con accesibilidad (skip-link, ARIA, semántica HTML5)
- ✅ CORS configurado para desarrollo en red local

### Transición pre-Laravel → Laravel
| Antes (Iteraciones 1-2) | Después (Iteración 3) |
|---|---|
| JSON Server / PHP vanilla | Laravel 12 con Eloquent ORM |
| Sesiones PHP simuladas | Sanctum (tokens Bearer) |
| HTML/CSS/JS estático | Vue 3 SPA con Pinia |
| Sin estructura MVC | Controladores, Modelos, Migraciones |

---

## 👥 Autores

- **Joan Ripoll Abad**
- **Jaume** (colaborador)

---

## 📄 Licencia

Proyecto académico — Módulo 2025-2026.
