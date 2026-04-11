# 🚀 Plantilla Base para APIs en Laravel

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-^8.2-blue.svg)](https://www.php.net/)
[![Passport](https://img.shields.io/badge/Auth-Passport-green.svg)](https://laravel.com/docs/passport)
[![Spatie Permission](https://img.shields.io/badge/Permissions-Spatie-yellow.svg)](https://spatie.be/docs/laravel-permission)
[![License](https://img.shields.io/badge/License-MIT-lightgrey.svg)](LICENSE)

Plantilla lista para construir **APIs en Laravel** con autenticación, autorización y consultas dinámicas.

---

## ✨ Características principales

- 🔑 **Autenticación con Laravel Passport (OAuth2, Bearer Tokens)**
- 🔒 **Roles y permisos con Spatie Laravel Permission**
- ⚡ **Modelo Api con filtros, orden, paginación, selección de campos e inclusión de relaciones**
- 📑 **Respuestas JSON uniformes (ApiResponse Trait)**

---

## 📂 Estructura del proyecto

```bash
app/
 ├── Http/
 │    ├── Controllers/
 │    │     └── Api/
 │    │           ├── AuthController.php
 │    │           ├── UserController.php
 │    │           └── BaseApiController.php
 │    ├── Requests/
 │    ├── Resources/
 │    └── Middleware/
 ├── Models/
 │      ├── Scopes/
 │      │    ├── FilterScope.php    # Filtros dinámicos
 │      │    ├── IncludeScope.php   # Inclusión de relaciones
 │      │    ├── SelectScope.php    # Selección de campos
 │      │    └── SortScope.php      # Orden dinámico
 │      ├── Api.php     # Modelo base dinámico
 │      └── User.php    # Modelo de usuario
 ├── Traits/
 │    └── ApiResponse.php
```

## ⚙️ Instalación

```bash
# 1. Clonar repositorio
git clone https://github.com/bycarmona141/base-api.git
cd base-api

# 2. Instalar dependencias
composer install

# 3. Configurar variables de entorno
cp .env.example .env
php artisan key:generate

# 4. Migraciones y seeders (incluye roles/permisos)
php artisan migrate --seed
```

## 🔑 Autenticación

La API usa Laravel Passport con Bearer Token.

```bash
# 6. Generar token
php artisan passport:client --password
```

### 📌 Endpoints principales

```bash
POST /api/register   # Registro de usuarios
POST /api/login      # Login con email/password
POST /api/logout     # Cerrar sesión (requiere token)
```

Ejemplo de login:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com", "password":"password"}'
```

Respuesta:

```json
{
  "status": "success",
  "token": "eyJ0eXAiOiJKV1QiLCJh...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@test.com"
  },
  "roles": ["admin"],
  "permissions": ["manage posts", "view reports"]
}
```

### ⚡ Consultas dinámicas con el modelo Api

El modelo Api.php permite:

🔍 Filtrar → ?filters[email][=]test@example.com

📑 Paginación → ?page=2&per_page=10

↕️ Ordenar → ?sort=name,-created_at

🎯 Seleccionar campos → ?select=id,name,email

🔗 Incluir relaciones → ?include=roles,posts

### 📑 Respuestas uniformes

Todas las respuestas usan un formato estándar:

```json
{
  "status": "success",
  "message": "Usuario creado correctamente",
  "data": {
    "id": 1,
    "name": "Carlos"
  }
}
```

## 🛠️ Comandos Artisan personalizados

Esta plantilla incluye comandos listos para tareas comunes en APIs productivas.

---

### ⚙️ Configuración inicial

Comando para generar configuración inicial de la API con soporte para:

- Generar .env desde .env.example
- Generar APP_KEY
- Generar claves de cifrado para Passport
- Limpieza de cachés
- Migraciones y seeders
- Enlace de storage

---

### ▶️ Uso básico

```bash
php artisan app:setup
```

---

### 💾 Backup de base de datos

Comando para generar backups automáticos de la base de datos con soporte para:

- MySQL, PostgreSQL y SQLite  
- Compresión (`gzip`)  
- Almacenamiento en discos Laravel (`local`, `s3`, `ftp`, `sftp`)  
- Envío a servidores remotos vía SSH (`scp`)  
- Notificación por correo  

---

### ▶️ Uso básico

```bash
php artisan db:backup
```

**Descripción del ejemplo:**

Este comando genera un dump de la base de datos y lo guarda en:

```
storage/app/backups/
```

---

### ⚙️ Ejemplo con opciones

```bash
php artisan db:backup --disk=s3 --path=prod/backups --compress --notify
```

**Descripción del ejemplo:**

- Guarda el backup en **S3**
- Dentro de la carpeta `prod/backups`
- Comprime el archivo en `.gz`
- Envía un correo al finalizar

---

### 🌐 Ejemplo enviando a servidor remoto

```bash
php artisan db:backup \
  --remote-host=user@192.168.1.10 \
  --remote-path=/var/backups \
  --remote-port=22 \
  --compress
```

**Descripción del ejemplo:**

- Genera el backup localmente  
- Lo comprime  
- Lo envía mediante **SCP (SSH)** al servidor remoto  
- Usa autenticación por **SSH keys (sin contraseña)**  

---

### 🧩 Opciones disponibles

| Opción            | Descripción                                  | Default   |
|-------------------|----------------------------------------------|-----------|
| `--disk=`         | Disco Laravel (`local`, `s3`, `ftp`, `sftp`) | `local`   |
| `--path=`         | Ruta dentro del disco                        | `backups` |
| `--compress`      | Comprime el archivo en `.gz`                 |           |
| `--notify`        | Envía correo al finalizar                    |           |
| `--remote-host=`  | Host remoto (`user@ip`)                      |           |
| `--remote-path=`  | Ruta en servidor remoto                      |           |
| `--remote-port=`  | Puerto SSH (default: 22)                     | `22`      |

---

### 📦 Ejemplo de salida

```bash
🔄 Iniciando backup de base de datos...
🗜️  Comprimiendo archivo...
💾 Guardando en disco [local]...

✅ Backup completado: backup_db_2026-04-07_14-00-00.sql.gz
   Disco: local
   Ruta:  backups/backup_db_2026-04-07_14-00-00.sql.gz
   Tamaño: 2.4 MB
📧 Notificación enviada correctamente.
```

**Descripción del ejemplo:**

Muestra el flujo completo:

1. Generación del dump  
2. Compresión  
3. Almacenamiento  
4. Notificación  



# 👨‍💻 Autor

Desarrollado por Bycarmona141

### 📜 Licencia MIT
