# Task Management API

Este proyecto es una API RESTful de gestión de tareas, construida con Laravel. Incluye autenticación de usuarios, asignación de tareas, actualizaciones de estado, base de datos MySQL, entorno Dockerizado, pruebas unitarias y un frontend simple en JavaScript/Bootstrap.

## Requisitos previos

- Entorno Docker WSL
- Ubuntu subsystem.
- Composer

## Stack

**Cliente:** Javascript y Bootstrap

**Servidor:** PHP 8.2 y Laravel 11

## Configuración del entorno

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tu-usuario/task-management-api.git
   cd task-management-api
2. **Construir y levantar los contenedores:**
   ```bash
   docker-compose up -d --build
3. **Instalar dependencias de Composer:**
   ```bash
   docker-compose exec app composer install
4. **Ejecutar migraciones:**
   ```bash
   docker-compose exec app php artisan migrate
5. **Instalar Laravel Sanctum:** Necesario para la autenticación de usuarios
   ```bash
   docker-compose exec app composer require laravel/sanctum
   docker-compose exec app php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   docker-compose exec app php artisan migrate
6. **Acceder a la aplicación:**
   - La API estará disponible en http://localhost:8000.
   - La base de datos MySQL estará disponible en localhost:3306.

## Variables de entorno

El archivo .env debe contener las siguientes variables:

```bash
  DB_CONNECTION=mysql
  DB_HOST=db
  DB_PORT=3306
  DB_DATABASE=task_management
  DB_USERNAME=root
  DB_PASSWORD=password
```

## Esquema de la Base de Datos

El esquema de la base de datos se encuentra en la raíz del proyecto en un archivo llamado **database.sql**. Este archivo contiene el script SQL necesario para crear las tablas y relaciones en la base de datos.

## Ejecutar Tests

Para ejecutar las pruebas unitarias y de integración.

```bash
  docker-compose exec app php artisan test
```

## Frontend

El proyecto incluye un frontend simple desarrollado con JavaScript y Bootstrap. Este frontend permite a los usuarios registrarse, iniciar sesión, crear, actualizar y eliminar tareas. Para acceder al frontend, asegúrate de que Docker esté corriendo con los contenedores levantados y luego abre tu navegador y visita http://localhost:8000.

## Referencia de la API

### Endpoints de la API

#### Autenticación

##### Registrar un nuevo usuario
```http
POST /api/auth/register
```
_No requiere autenticación._

##### Iniciar sesión
```http
POST /api/auth/login
```
_No requiere autenticación._

##### Cerrar sesión
```http
POST /api/auth/logout
```
_Requiere autenticación (Bearer Token)_

### Usuario

##### Obtener información del usuario
```http
GET /api/user
```
_Requiere autenticación (Bearer Token)_

### Tareas

##### Obtener todas las tareas
```http
GET /api/tasks
```
_Requiere autenticación (Bearer Token)_

##### Crear una nueva tarea
```http
POST /api/tasks
```
_Requiere autenticación (Bearer Token)_

##### Actualizar una tarea
```http
PUT /api/tasks/{id}
```
_Requiere autenticación (Bearer Token)_

##### Eliminar una tarea
```http
DELETE /api/tasks/{id}
```
_Requiere autenticación (Bearer Token)_

## Ejemplo de Respuestas

##### Registro de Usuario (POST /api/auth/register)
```json
{
    "message": "Usuario registrado correctamente"
}
```

##### Login de Usuario (POST /api/auth/login)
```json
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

##### Obtener Tareas (GET /api/tasks)
```json
[
    {
        "id": 1,
        "title": "Tarea 1",
        "description": "Descripción de la tarea 1",
        "status": "pending",
        "user_id": 1
    }
]
```

## Ejemplos de Llamadas API

##### Registro de Usuario
```bash
curl -X POST http://localhost:8000/api/auth/register \
-H "Content-Type: application/json" \
-d '{
    "name": "Pablo",
    "email": "pablo@gmail.com",
    "password": "12345678"
}'
```

##### Login de Usuario
```bash
curl -X POST http://localhost:8000/api/auth/login \
-H "Content-Type: application/json" \
-d '{
    "email": "pablo@gmail.com",
    "password": "12345678"
}'
```

##### Obtener Tareas
```bash
curl -X GET http://localhost:8000/api/tasks \
-H "Authorization: Bearer <token>"
```

##### Crear Tarea
```bash
curl -X POST http://localhost:8000/api/tasks \
-H "Content-Type: application/json" \
-H "Authorization: Bearer <token>" \
-d '{
    "title": "Nueva Tarea",
    "description": "Descripción de la nueva tarea",
    "status": "pending"
}'
```

##### Actualizar Tarea
```bash
curl -X PUT http://localhost:8000/api/tasks/1 \
-H "Content-Type: application/json" \
-H "Authorization: Bearer <token>" \
-d '{
    "title": "Tarea Actualizada",
    "description": "Descripción actualizada",
    "status": "in_progress"
}'
```

##### Eliminar Tarea
```bash
curl -X DELETE http://localhost:8000/api/tasks/1 \
-H "Authorization: Bearer <token>"
```

---
Gracias por probar Task Management! Si tienes alguna pregunta o sugerencia, no dudes en contactar conmigo :smile: