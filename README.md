# Sistema de Gestión para Negocio

Sistema de gestión completo desarrollado con CodeIgniter 4 y Bootstrap 5, diseñado para administrar ventas, stock y clientes de manera eficiente.

## Características Principales

*   **Punto de Venta (POS)**: Interfaz rápida para ventas minoristas y mayoristas.
*   **Gestión de Stock**: Alertas de stock bajo y ajuste manual de inventario.
*   **Categorías Jerárquicas**: Organización de productos en categorías y subcategorías.
*   **Reportes**: Generación de comprobantes de venta en PDF.
*   **Seguridad**: Autenticación de usuarios y protección de rutas.
*   **Respaldo**: Descarga de copia de seguridad de la base de datos completa.

## Instalación y Ejecución

Este proyecto está contenerizado con Docker para facilitar su ejecución.

1.  **Requisitos**:
    *   Docker Desktop instalado y corriendo.
    *   Git (opcional, para clonar).

2.  **Iniciar la Aplicación**:
    Abra una terminal en la carpeta del proyecto y ejecute:
    ```bash
    docker-compose up -d
    ```

3.  **Acceso**:
    *   **Sistema Web**: [http://localhost:8080](http://localhost:8080)
    *   **Base de Datos (PhpMyAdmin)**: [http://localhost:8081](http://localhost:8081)

## Credenciales por Defecto

**Administrador del Sistema**:
*   **Email**: `admin@admin.com`
*   **Contraseña**: `password`

## Configuración de Producción

Para entornos de producción, asegúrese de configurar el archivo `.env`:
```ini
CI_ENVIRONMENT = production
```
Esto desactivará la barra de depuración y ocultará errores detallados.

## Soporte

Para soporte técnico, contacte al desarrollador.
