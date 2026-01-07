# Manual de Usuario - Sistema de Gestión

Bienvenido al Manual de Usuario del Sistema de Gestión. Este documento le guiará a través de las funcionalidades del sistema para administrar su negocio de manera eficiente.

## Tabla de Contenidos

1. [Acceso al Sistema](#acceso-al-sistema)
2. [Tablero Principal (Dashboard)](#tablero-principal-dashboard)
3. [Gestión de Inventario](#gestión-de-inventario)
    - [Productos](#productos)
    - [Categorías](#categorías)
    - [Control de Stock](#control-de-stock)
4. [Ventas (Punto de Venta)](#ventas-punto-de-venta)
5. [Gestión de Personas](#gestión-de-personas)
    - [Clientes](#clientes)
    - [Proveedores](#proveedores)
6. [Cuentas Corrientes](#cuentas-corrientes)
7. [Reportes](#reportes)
8. [Administración del Sistema](#administración-del-sistema)

---

## 1. Acceso al Sistema

Para ingresar al sistema, diríjase a la página de inicio de sesión.
- Ingrese su **Correo Electrónico** y **Contraseña**.
- Presione el botón **Ingresar**.
- Si desea salir, haga clic en el botón **Cerrar Sesión** ubicado en el munú superior.

## 2. Tablero Principal (Dashboard)

Al iniciar sesión, verá el Tablero Principal. Aquí encontrará un resumen rápido del estado de su negocio, incluyendo alertas de stock bajo y accesos directos a las funciones más utilizadas.

## 3. Gestión de Inventario

### Productos
Diríjase al menú **Productos** para ver el listado de todos los artículos.
- **Crear Producto**: Haga clic en "Nuevo Producto", complete los datos (Nombre, Código, Precio, Stock, Categoría) y guarde.
- **Editar**: Haga clic en el ícono de lápiz de un producto para modificar sus datos.
- **Eliminar**: Use el ícono de papelera para quitar un producto (si el sistema lo permite dependiendo de las ventas asociadas).
- **Importar/Exportar**: Utilice las opciones para cargar productos masivamente desde CSV o descargar su listado.

### Categorías
Organice sus productos en familias.
- Vaya a **Categorías** para agregar nuevas clasificaciones (ej: "Bebidas", "Limpieza").
- Esto facilita la búsqueda en el punto de venta.

### Control de Stock
El sistema descuenta automáticamente el stock al realizar una venta.
- **Ajuste Manual**: Puede realizar ajustes de stock (entradas o salidas manuales por averías o compras) desde la sección correspondiente.
- **Historial**: Consulte `Stock Logs` para ver los movimientos históricos de cada artículo.

## 4. Ventas (Punto de Venta)

El módulo de **Ventas** es el corazón de la facturación.
1. Haga clic en **Nueva Venta**.
2. **Buscar Producto**: Escanee el código de barras o escriba el nombre del producto.
3. **Agregar al Carrito**: El producto se sumará a la lista.
4. **Cliente**: Seleccione un cliente registrado o "Consumidor Final" por defecto.
5. **Completar Venta**:
    - Verifique el total.
    - Seleccione el método de pago.
    - Confirme la operación para generar el ticket y descontar stock.

## 5. Gestión de Personas

### Clientes
Administre su base de datos de clientes.
- Ideal para mantener cuentas corrientes o historial de compras.
- Puede importar/exportar la lista de clientes.

### Proveedores
Registre a sus proveedores para gestionar compras y cuentas corrientes de pago.

## 6. Cuentas Corrientes

Este módulo permite ver las deudas de clientes o saldos a favor con proveedores.
- Seleccione un Cliente o Proveedor.
- Verá el detalle de movimientos (Ventas, Pagos).
- **Registrar Pago**: Ingrese pagos parciales o totales para saldar la cuenta.

## 7. Reportes

Obtenga información valiosa para la toma de decisiones.
- **Caja Diaria**: Resumen de ingresos del día.
- **Facturas**: Listado detallado de todas las ventas realizadas.
- **Exportación**: La mayoría de los reportes se pueden descargar en CSV/Excel.

## 8. Administración del Sistema

### Usuarios y Roles
- **Usuarios**: Cree cuentas para sus empleados.
- **Roles**: Asigne permisos (Administrador, Vendedor) para restringir el acceso a ciertas áreas sensibles (como la eliminación de productos o configuraciones).

### Copias de Seguridad (Backup)
Proteja sus datos.
- Vaya a **Admin > Backup** para generar y descargar una copia segura de toda la base de datos. Se recomienda hacer esto periódicamente.
