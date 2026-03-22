# Guía para Desarrolladores — JJ-Security

Guía técnica completa para la configuración, desarrollo y mantenimiento del proyecto JJ-Security.

---

## 📋 Requisitos Previos

Asegúrate de tener instalados los siguientes componentes antes de empezar:
- **Docker & Docker Compose**: Para el entorno de contenedores (Sail).
- **Git**: Para el control de versiones.
- **Node.js (v20+ o v22+)**: Necesario para el frontend con Vite.
- **PHP 8.2+**: (Opcional, pero recomendado tenerlo localmente para comandos rápidos fuera de Docker).
- **Composer**: Gestor de dependencias de PHP.

---

## 🚀 Instalación y Configuración

### 1. Clonar el Proyecto
```bash
git clone https://github.com/JoanRipollAbad/PoyecteIntermodular-Joan-Jaume.git
cd PoyecteIntermodular-Joan-Jaume
```

### 2. Configuración del Backend (Laravel)
```bash
cd backend
# Copiar variables de entorno
cp .env.example .env

# Editar .env y asegurar estos valores para Docker:
# DB_HOST=mysql
# DB_DATABASE=jj_security
# DB_USERNAME=joan
# DB_PASSWORD=password

# Instalar dependencias
composer install

# Levantar el entorno con Laravel Sail
./vendor/bin/sail up -d

# Generar clave de aplicación, ejecutar migraciones y poblar la base de datos
sail artisan key:generate
sail artisan migrate:fresh --seed
```

### 3. Configuración del Frontend (Vue 3)
```bash
cd ../frontend

# Instalar dependencias
npm install

# Configurar variables de entorno (API local)
echo "VITE_API_URL=http://localhost/api" > .env

# Arrancar servidor de desarrollo
npm run dev
```

---

## 🛠️ Comandos Habituales

| Acción | Comando |
|---|---|
| Levantar contenedores | `sail up -d` |
| Detener contenedores | `sail down` |
| Reiniciar base de datos | `sail artisan migrate:fresh --seed` |
| Ejecutar todos los tests | `sail artisan test` |
| Ver lista de rutas API | `sail artisan route:list` |
| Abrir terminal interactivo (Tinker) | `sail artisan tinker` |

---

## 🧪 Pruebas (Tests)

El proyecto utiliza **PHPUnit** para asegurar la calidad del código. Los tests se encuentran en `backend/tests/Feature/`.

```bash
# Ejecutar todos los tests (Auth, Product, Comment)
sail artisan test

# Ejecutar un test específico
sail artisan test --filter=AuthControllerTest
```

---

## 📂 Estructura de Carpetas Principal

```
PILaravel/
├── backend/                    # Proyecto Laravel 12
│   ├── app/
│   │   ├── Http/Controllers/Api/ # Controladores REST
│   │   └── Models/             # Modelos (User, Product, Comment...)
│   ├── database/               # Migraciones, factories y seeders
│   ├── routes/api.php          # Definición de rutas de la API
│   ├── tests/Feature/          # Pruebas automatizadas
│   └── docs/                   # Documentación técnica y de usuario
├── frontend/                   # Proyecto Vue.js 3
│   ├── src/
│   │   ├── api/                # Cliente Axios
│   │   ├── views/              # Vistas de la aplicación
│   │   └── stores/             # Gestión de estado (Pinia)
│   └── .env                    # Configuración de URL de la API
└── compose.yaml                # Orquestación de Docker
```

---

## ❓ Solución de Problemas Comunes

### Error: "Connection Refused" en el Frontend
- **Causa**: La URL en `frontend/.env` no apunta a la IP correcta o el puerto 80 está bloqueado.
- **Solución**: Verifica que `VITE_API_URL` sea `http://localhost/api` (si estás en la misma máquina) o la IP de tu compañero. Reinicia `npm run dev` tras cambiar el `.env`.

### El compañero no puede acceder a mi API
- **Causa**: El firewall bloquea la conexión o CORS no permite su IP.
- **Solución**: Tu compañero debe usar tu IP de red local en su `.env`. Verifica `backend/config/cors.php` para asegurar que su rango de IP está permitido (por defecto aceptamos `172.16.*` y `192.168.*`).

### Cambios en el .env de Laravel no surten efecto
- **Causa**: La caché de configuración está activa.
- **Solución**: Ejecuta `sail artisan config:clear`.

### Database tables not found
- **Causa**: No se han ejecutado las migraciones.
- **Solución**: Ejecuta `sail artisan migrate`.
