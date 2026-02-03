# Guía de Despliegue y Solución de Error 404

El error **404 Not Found** en el servidor suele ocurrir por una de estas razones:
1.  **Ruta incorrecta:** Se está accediendo a `tu-dominio.com` pero el sitio real está en `tu-dominio.com/public`.
2.  **Archivo .env no detectado:** El archivo se llama `env` y debe renombrarse a `.env` en el servidor.
3.  **Configuración de Base de Datos:** CodeIgniter no conecta a la base de datos y falla silenciosamente o muestra 404.

## Pasos para un Despliegue Correcto (Shared Hosting / cPanel)

### Opción A: Estructura Estándar (Más Segura)
1.  Sube **todo el contenido del zip** a una carpeta **fuera** de `public_html` (ej: `/home/usuario/proyecto`).
2.  Mueve **SOLO** el contenido de la carpeta `public` (index.php, .htaccess, assets) dentro de `public_html`.
3.  Edita el archivo `public_html/index.php`:
    *   Busca la línea: `require FCPATH . '../app/Config/Paths.php';`
    *   Cámbiala para apuntar a tu carpeta de proyecto: `require FCPATH . '../proyecto/app/Config/Paths.php';`

### Opción B: Subir todo a public_html (Rápido, menos seguro)
1.  Sube todo el contenido del zip dentro de `public_html`.
2.  Accede a tu sitio web como `www.tu-dominio.com/public`.
3.  Si quieres entrar directo (sin `/public`), debes crear un `.htaccess` en la raíz de `public_html` que redirija a la carpeta `public`.

## Configuraciones Críticas

### 1. Renombrar `env` a `.env`
El archivo en el zip se llama `env` (sin punto).
*   **Renombralo a `.env`** en tu hosting.
*   Edítalo y asegúrate de configurar:
    ```ini
    CI_ENVIRONMENT = production
    app.baseURL = 'https://tu-dominio.com/'
    
    database.default.hostname = localhost
    database.default.database = tu_base_de_datos
    database.default.username = tu_usuario
    database.default.password = tu_contraseña
    database.default.DBDriver = MySQLi
    ```

### 2. Permisos
*   Asegúrate de que la carpeta `writable` y sus subcarpetas tengan permisos de escritura (generalmente 755 o 777 dependiendo del hosting).

## Solución al Error 404 Actual
Si ves "The resource requested could not be found on this server!":
*   Lo más probable es que **Apache no esté encontrando el index.php**.
*   Verifica que en la carpeta donde apunta tu dominio exista el archivo `index.php` o un `.htaccess` válido.
*   Si subiste todo tal cual: Intenta entrar a `www.tu-dominio.com/public`.

## Solución al Error 500 (Pantalla en blanco o "Esta página no funciona")
El error 500 oculta el problema real por seguridad. Para ver qué está fallando:

1.  **IMPORTANTE:** Asegúrate de haber renombrado el archivo `env` a `.env` en tu servidor.
2.  Edita el archivo `.env` y busca la línea:
    ```ini
    # CI_ENVIRONMENT = production
    ```
3.  Cámbiala a (quita el # y pon development):
    ```ini
    CI_ENVIRONMENT = development
    ```
4.  Recarga la página. Ahora verás un mensaje de error detallado en naranja/rojo.

### Causas comunes del Error 500:
1.  **Base de Datos:** Error de conexión. Revisa `database.default.hostname`, `username`, `password` y `database` en el archivo `.env`.
2.  **Permisos:** La carpeta `writable` **necesita permisos de escritura**. Si estás en cPanel, dale permisos 777 o 755 a la carpeta `writable` y sus subcarpetas.
3.  **Extensiones PHP:** CodeIgniter 4 requiere las extensiones `intl`, `mbstring` y `json`. Verifica en tu cPanel > "Select PHP Version" que estén activadas.
