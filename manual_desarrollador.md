# Manual del Desarrollador - Sistema de Gestión

## Introducción Técnica
Este sistema está construido sobre el framework **CodeIgniter 4**, utilizando una arquitectura MVC (Modelo-Vista-Controlador). Está diseñado para ser ligero, rápido y fácil de extender.

### Stack Tecnológico
- **Lenguaje**: PHP 8.1+
- **Framework**: CodeIgniter 4
- **Base de Datos**: MySQL / MariaDB
- **Frontend**: Bootstrap 5 (HTML5, CSS3, JS)
- **Gestor de Paquetes**: Composer

---

## Requisitos del Sistema

Para ejecutar este proyecto en un entorno de desarrollo o producción, asegúrese de contar con:
- PHP >= 8.1
- Extensiones PHP requeridas: `intl`, `mbstring`, `json`, `mysql`, `xml`, `curl`.
- Servidor Web (Apache/Nginx) o servidor interno de CodeIgniter (`spark`).
- MySQL 5.7+ o MariaDB 10.3+

---

## Instalación y Configuración

### 1. Clonar o Descomprimir
Extraiga los archivos del proyecto en su servidor web.

### 2. Instalar Dependencias
Ejecute el siguiente comando en la raíz del proyecto para descargar las librerías necesarias:
```bash
composer install
```

### 3. Configuración de Entorno (.env)
Copie el archivo `env` a `.env` y configure sus credenciales de base de datos y la URL base:
```ini
CI_ENVIRONMENT = development # o production

app.baseURL = 'http://localhost/sistema/'

database.default.hostname = localhost
database.default.database = nombre_db
database.default.username = usuario
database.default.password = contraseña
database.default.DBDriver = MySQLi
```

### 4. Base de Datos
Corra las migraciones y seeders para estructurar la base de datos e insertar datos iniciales (admin, roles, configuración básica):
```bash
php spark migrate
php spark db:seed MainSeeder
```
*(Nota: Verifique si existe un `MainSeeder` o ejecute seeds específicos como `ConsumidorFinalSeeder`).*

---

## Estructura del Proyecto

El proyecto sigue la estructura estándar de CodeIgniter 4:

- **app/**: Núcleo de la aplicación.
    - **Config/**: Archivos de configuración (`Routes.php`, `Database.php`).
    - **Controllers/**: Lógica de negocio (`SaleController`, `ProductController`).
    - **Models/**: Interacción con la base de datos (`ProductModel`, `SaleModel`).
    - **Views/**: Plantillas HTML (`products/`, `sales/`).
- **public/**: Raíz pública (CSS, JS, imágenes). Apunte su servidor web aquí.
- **writable/**: Directorio de escritura para logs, cache y sesiones. Debe tener permisos de escritura.

---

## Módulos Principales

### Auth
Maneja el inicio de sesión y protección de rutas. Utiliza un `AuthFilter` configurado en `app/Config/Filters.php` y asignado en `Routes.php`.

### Ventas y Stock
La lógica de ventas descuenta stock automáticamente.
- **Controlador**: `SaleController`
- **Modelos**: `SaleModel`, `SaleDetailModel`, `ProductModel`.
- **Flujo**: Al crear una venta (`store`), se recorren los items, se crea el registro en `sales`, los detalles en `sale_details` y se actualiza `products.stock`.

### Reportes
Genera vistas basadas en consultas agregadas.
- **Controlador**: `ReportController`
- **Vistas**: `reports/daily_cash`, `reports/invoice`.

---

## Añadir Nuevas Funcionalidades

1. **Crear Ruta**: Defina el endpoint en `app/Config/Routes.php`.
2. **Crear Controlador**: Use `php spark make:controller NombreController`.
3. **Crear Modelo**: Si requiere nueva tabla, `php spark make:model NombreModel`.
4. **Crear Vista**: En `app/Views`.

---

## Comandos Útiles

- Iniciar servidor de desarrollo:
  ```bash
  php spark serve
  ```
- Ver rutas registradas:
  ```bash
  php spark routes
  ```
- Crear nueva migración:
  ```bash
  php spark migrate:create nombre_tabla
  ```
