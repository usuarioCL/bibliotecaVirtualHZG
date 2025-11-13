# Biblioteca Virtual HZG

**Sistema de Gestión de Biblioteca Virtual** desarrollado con CodeIgniter 4 y MySQL.

## Descripción

Sistema completo para la gestión de una biblioteca virtual que permite administrar recursos bibliográficos (físicos y digitales), préstamos, usuarios, y favoritos. Diseñado para instituciones educativas que requieren un control eficiente de su material bibliográfico.

## Características Principales

### Gestión de Recursos
- Recursos físicos y digitales (libros, revistas, etc.)
- Clasificación por categorías y subcategorías
- Control de stock y disponibilidad
- Gestión de autores y editoriales
- Ejemplares físicos con códigos únicos

### Sistema de Préstamos
- Solicitud y gestión de préstamos
- Control de fechas de vencimiento
- Renovación de préstamos
- Historial completo de préstamos
- Sistema de sanciones automáticas
- Notificaciones de vencimiento

### Gestión de Usuarios
- Roles: Administrador, Docente, Estudiante
- Autenticación y autorización
- Perfil de usuario personalizado
- Matrículas para estudiantes
- Importación masiva de usuarios

### Favoritos y Catálogo
- Sistema de favoritos personalizado
- Catálogo interactivo con filtros avanzados
- Búsqueda por título, autor, ISBN
- Vista de detalles con modal
- Recursos digitales con lector PDF integrado
- Compartir recursos

## Tecnologías

- **Backend:** CodeIgniter 4 (PHP 8.x)
- **Base de Datos:** MySQL 8.0+
- **Frontend:** Bootstrap 5, JavaScript 
- **Librerías:** 
  - Dompdf (generación de PDFs)
  - PhpSpreadsheet (importación de Excel)
  - SweetAlert2 (alertas interactivas)
  - FontAwesome (iconos)


## Módulos del Sistema

### 1. **Catálogo** 
Exploración y búsqueda de recursos bibliográficos con filtros avanzados y vista de detalles.

### 2. **Préstamos** 
Gestión completa del ciclo de vida de los préstamos, desde solicitud hasta devolución.

### 3. **Favoritos** 
Biblioteca personal donde los usuarios pueden guardar sus libros preferidos.

### 4. **Administración** 
Panel de control para gestión de recursos, usuarios, y configuración del sistema.

### 5. **Recursos** 
CRUD completo de libros y materiales bibliográficos.

### 6. **Usuarios** 
Gestión de cuentas, roles y permisos.

## Características de UI/UX

- Diseño responsive (móvil, tablet y escritorio)
- Modales para acciones rápidas
- Tablas interactivas con ordenamiento
- Badges de estado visual
- Componentes reutilizables
- Animaciones suaves
- Mensajes de confirmación intuitivos
- Accesibilidad mejorada (ARIA)

## Seguridad

- Filtros de autenticación (AuthFilter, AdminFilter)
- Validación de datos en servidor y cliente
- Control de acceso basado en roles

## Requisitos del Sistema

- PHP 8.0 o superior
- MySQL 8.0 o superior
- Apache/Nginx con mod_rewrite
- Composer

##  Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/usuarioCL/bibliotecaVirtualHZG.git
cd bibliotecaVirtualHZG
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar base de datos**
- Crear una base de datos MySQL
- Copiar `env.exampl` a `.env`
- Configurar credenciales de base de datos en `.env`

4. **Importar base de datos**
```bash
mysql -u usuario -p nombre_bd < app/Database/biblioteca_virtual.sql
```

5. **Configurar permisos**
```bash
chmod -R 777 writable/
chmod -R 777 public/uploads/
```

6. **Ejecutar el servidor**
```bash
php spark serve
```

El sistema estará disponible en: `http://localhost:8080`

## 📖 Uso del Sistema

### Para Usuarios
1. Iniciar sesión con credenciales
2. Explorar el catálogo de recursos
3. Agregar libros a favoritos
4. Solicitar préstamos
5. Ver historial personal

### Para Administradores
1. Gestionar recursos bibliográficos
2. Aprobar/rechazar solicitudes de préstamo
3. Administrar usuarios
4. Generar reportes
5. Configurar el sistema

