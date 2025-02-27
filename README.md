# Task Management API

Este proyecto es una API RESTful para la gestión de tareas, desarrollada con Laravel y Docker.

## Requisitos previos

- Entorno Docker WSL
- Ubuntu subsystem.
- Composer

## Stack

**Cliente:** Javascript

**Servidor:** PHP v.8.2 with Laravel v.11

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

## Ejecutar Tests

Para ejecutar las pruebas unitarias y de integración.

```bash
  docker-compose exec app php artisan test
```

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
