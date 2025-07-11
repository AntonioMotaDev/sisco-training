# SISCO Training v1.2

Sistema de capacitaciones desarrollado en Laravel para la formación de técnicos y clientes a través de cursos estructurados con videos de YouTube y cuestionarios evaluativos.

## 📋 Descripción

SISCO Training es un sistema de gestión de capacitaciones que permite:
- **Administradores**: Crear y asignar cursos
- **Técnicos**: Empleados internos que toman cursos asignados
- **Clientes**: Personal de empresas externas que accede a capacitaciones

### Características principales:
- Cursos estructurados en secciones secuenciales
- Videos de YouTube integrados
- Sistema de evaluación con cuestionarios
- Acceso por usuario/contraseña o tokens temporales
- Seguimiento de progreso y calificaciones

## 🛠️ Requisitos del Sistema

### Verificar versiones instaladas:

```bash
# Verificar PHP (requiere 8.1+)
php --version

# Verificar Composer
composer --version

# Verificar extensiones PHP necesarias
php -m | grep -E "(openssl|pdo|mbstring|tokenizer|xml|ctype|json|bcmath|fileinfo)"
```

### Dependencias requeridas:
- **PHP**: 8.1 o superior
- **Composer**: 2.0 o superior
- **LAMPP/XAMPP**: Para Apache y MySQL
- **Extensiones PHP**: openssl, pdo, mbstring, tokenizer, xml, ctype, json, bcmath, fileinfo

## 🚀 Instalación Paso a Paso

### 1. Clonar e instalar dependencias

```bash
# Clonar el repositorio
git clone <url-del-repositorio> siscotraining_v1
cd siscotraining_v1

# Instalar dependencias de Composer
composer install

# Instalar dependencias de NPM (opcional, para frontend)
npm install
```

### 2. Configurar LAMPP

```bash
# Verificar estado de LAMPP
sudo /opt/lampp/xampp status

# Iniciar LAMPP si no está activo
sudo /opt/lampp/xampp start

# Verificar que Apache y MySQL estén funcionando
sudo /opt/lampp/xampp status
```

### 3. Configurar base de datos

```bash
# Crear base de datos usando MySQL de LAMPP
/opt/lampp/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS sisco_training CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Verificar que la base de datos se creó
/opt/lampp/bin/mysql -u root -e "SHOW DATABASES;" | grep sisco_training
```

### 4. Configurar archivo .env

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

**Configuración mínima del archivo `.env`:**

```env
APP_NAME="SISCO Training"
APP_ENV=local
APP_KEY=base64:.... # Se genera automáticamente
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=es
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=es_ES

# Configuración de base de datos para LAMPP
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisco_training
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Ejecutar migraciones y seeders

```bash
# Ejecutar migraciones para crear tablas
php artisan migrate

# Ejecutar seeders para datos iniciales
php artisan db:seed

# Verificar que todo se creó correctamente
php artisan migrate:status
```

### 6. Iniciar el servidor de desarrollo

```bash
# Iniciar servidor Laravel
php artisan serve

# El sistema estará disponible en: http://localhost:8000
```

## 👤 Credenciales por defecto

**Usuario Administrador:**
- **Usuario**: admin
- **Contraseña**: admin123
- **Email**: admin@siscotraining.com

## 🎨 Paleta de Colores

El sistema utiliza la siguiente paleta de colores corporativa:

```css
:root {
    --white: #ffffff;
    --tufts-blue: #4c8ec5;      /* Azul principal */
    --picton-blue: #2daee1;     /* Azul secundario */
    --yellow-green: #b7cf49;    /* Verde amarillento (acentos) */
    --olive: #878d29;           /* Oliva (texto oscuro) */
}
```

## 📊 Estructura de la Base de Datos

### Tablas principales:
- **roles**: Admin, Técnico, Cliente
- **users**: Usuarios del sistema con roles asignados
- **courses**: Cursos disponibles
- **topics**: Secciones dentro de cada curso
- **videos**: Videos de YouTube por sección
- **tests**: Evaluaciones por sección
- **questions**: Preguntas de los tests
- **answers**: Respuestas de las preguntas
- **attempts**: Intentos de evaluación de usuarios
- **attempt_answers**: Respuestas específicas de cada intento

## 🔧 Comandos útiles

### Desarrollo
```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Verificar configuración
php artisan config:show database

# Crear modelos, controladores, etc.
php artisan make:model NombreModelo
php artisan make:controller NombreController
php artisan make:migration create_nombre_table
```

### Base de datos
```bash
# Ejecutar migraciones específicas
php artisan migrate --path=/database/migrations/2025_07_11_181908_create_roles_table.php

# Rollback de migraciones
php artisan migrate:rollback

# Reset completo de base de datos
php artisan migrate:fresh --seed
```

## 🐛 Resolución de problemas

### Problema: Error de conexión a la base de datos
**Solución:**
1. Verificar que LAMPP esté funcionando: `sudo /opt/lampp/xampp status`
2. Verificar configuración en `.env`
3. Verificar que la base de datos exista

### Problema: Error de permisos en storage
**Solución:**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### Problema: APP_KEY no configurada
**Solución:**
```bash
php artisan key:generate
```

## 📁 Estructura del proyecto

```
siscotraining_v1/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Providers/           # Proveedores de servicios
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/             # Seeders de datos iniciales
├── docs/                    # Documentación del proyecto
│   ├── DataBase_Scheme.md   # Esquema de la base de datos
│   └── project_description.md
├── resources/
│   ├── css/                 # Estilos CSS con paleta de colores
│   ├── js/                  # JavaScript
│   └── views/               # Vistas Blade
└── routes/                  # Definición de rutas
```

## 👥 Tipos de Usuario

### Administrador
- Crear y gestionar cursos
- Asignar cursos a técnicos y clientes
- Ver reportes y estadísticas
- Gestionar usuarios

### Técnico
- Acceder a cursos asignados
- Tomar evaluaciones
- Ver progreso personal

### Cliente
- Acceder con tokens temporales
- Completar capacitaciones asignadas
- Obtener certificaciones

## 🔐 Sistema de Autenticación

- **Usuario/Contraseña**: Para todos los tipos de usuario
- **Tokens de acceso**: Para técnicos y clientes (duración limitada)
- **Renovación de tokens**: Sistema automático según configuración

## 📞 Soporte

Para soporte técnico o consultas sobre el sistema, contactar al equipo de desarrollo.

---

**SISCO Training v1.2** - Sistema desarrollado con Laravel 12 para capacitación técnica especializada.
