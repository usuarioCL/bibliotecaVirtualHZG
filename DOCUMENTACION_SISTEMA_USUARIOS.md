# Sistema de Validación de Usuarios - Biblioteca Virtual Escolar

## Descripción General

Este sistema implementa la validación automática para la creación de usuarios basándose en las tablas de matrículas, personas y grupos. Garantiza que solo estudiantes matriculados o docentes que trabajen en la institución educativa puedan registrarse como usuarios.

## Estructura de Validación

### 1. Modelos Implementados

#### MatriculaModel
- **Archivo**: `app/Models/MatriculaModel.php`
- **Funciones principales**:
  - `personaEstaMatriculada($idpersona)`: Verifica si una persona tiene matrícula activa
  - `getMatriculaActiva($idpersona)`: Obtiene información completa de la matrícula
  - `esDocente($idpersona)`: Verifica si una persona es docente

#### GrupoModel
- **Archivo**: `app/Models/GrupoModel.php`
- **Funciones principales**:
  - `getGruposActivos($anio)`: Obtiene grupos del año lectivo
  - `getGruposPorNivel($nivel, $anio)`: Filtra grupos por nivel educativo

#### UsuarioModel (Actualizado)
- **Archivo**: `app/Models/UsuarioModel.php`
- **Funciones principales**:
  - `validarElegibilidadUsuario($idpersona, $nivelacceso)`: Valida si puede crear usuario
  - `crearUsuarioConValidacion($data)`: Crea usuario con validación automática
  - `getUsuarioCompleto($idusuario)`: Información completa del usuario
  - `getMatriculaUsuario($idusuario)`: Matrícula del usuario si es estudiante

### 2. Controlador Actualizado

#### UsuarioController
- **Archivo**: `app/Controllers/UsuarioController.php`
- **Endpoints disponibles**:
  - `POST /usuarios/crear`: Crear usuario con validación
  - `GET /usuarios/verificar-elegibilidad`: Verificar si puede crear usuario
  - `GET /usuarios/info-matricula/{id}`: Información de matrícula
  - `GET /usuarios/listar`: Listar usuarios
  - `GET /usuarios/obtener/{id}`: Obtener usuario específico

## Reglas de Validación

### Para Estudiantes (`nivelacceso = 'estudiante'`)
1. La persona DEBE existir en la tabla `personas`
2. La persona DEBE tener matrícula activa en la tabla `matriculas`
3. La matrícula debe tener `estadomatricula = true`
4. La persona NO debe tener ya un usuario asignado

### Para Docentes (`nivelacceso = 'docente'`)
1. La persona DEBE existir en la tabla `personas`
2. No requiere matrícula como estudiante
3. La persona NO debe tener ya un usuario asignado
4. *(Puedes agregar validaciones adicionales como tabla de empleados)*

### Para Administradores (`nivelacceso = 'admin'`)
1. Requiere permisos especiales para crear
2. Solo otros administradores deberían poder crearlos
3. Implementación de seguridad adicional requerida

## Uso del Sistema

### Ejemplo Básico de Creación

```php
use App\Models\UsuarioModel;

$usuarioModel = new UsuarioModel();

$datosUsuario = [
    'nomuser' => 'juan.perez',
    'passuser' => 'password123',
    'nivelacceso' => 'estudiante',
    'idpersona' => 1
];

$resultado = $usuarioModel->crearUsuarioConValidacion($datosUsuario);

if ($resultado['exito']) {
    echo "Usuario creado: ID " . $resultado['id'];
} else {
    echo "Error: " . $resultado['mensaje'];
}
```

### Verificación Previa

```php
$validacion = $usuarioModel->validarElegibilidadUsuario($idpersona, $nivelacceso);

if ($validacion['valido']) {
    // Proceder con la creación
} else {
    // Mostrar mensaje de error
    echo $validacion['mensaje'];
}
```

### Desde el Controlador (API)

```javascript
// Verificar elegibilidad vía AJAX
fetch('/usuarios/verificar-elegibilidad?idpersona=1&nivelacceso=estudiante')
    .then(response => response.json())
    .then(data => {
        if (data.elegible) {
            // Permitir creación de usuario
        } else {
            // Mostrar mensaje de error
            alert(data.message);
        }
    });
```

## Rutas Disponibles

### Gestión de Usuarios
- `GET /usuarios/crear` - Formulario de creación
- `POST /usuarios/crear` - Procesar creación
- `GET /usuarios/listar` - Listar usuarios
- `GET /usuarios/obtener/{id}` - Obtener usuario específico

### API de Validaciones
- `GET /usuarios/verificar-elegibilidad` - Verificar elegibilidad
- `GET /usuarios/info-matricula/{id}` - Información de matrícula
- `GET /api/usuarios/elegibilidad` - API para AJAX
- `GET /api/usuarios/matricula/{id}` - API para matrícula

## Seguridad Implementada

1. **Validación de Reglas de Negocio**: Solo personas matriculadas/docentes pueden crear usuarios
2. **Prevención de Duplicados**: Una persona = un usuario máximo
3. **Encriptación de Contraseñas**: Hash automático con `password_hash()`
4. **Validación de Campos**: Reglas estrictas para todos los campos
5. **Mensajes de Error Descriptivos**: Feedback claro para el usuario

## Tabla de Estados de Respuesta

| Condición | Estado | Mensaje |
|-----------|--------|---------|
| Estudiante matriculado | ✅ Válido | "La persona es elegible para crear un usuario" |
| Estudiante no matriculado | ❌ Inválido | "Solo estudiantes matriculados pueden crear usuarios de tipo estudiante" |
| Docente válido | ✅ Válido | "La persona es elegible para crear un usuario" |
| Persona inexistente | ❌ Inválido | "La persona especificada no existe en el sistema" |
| Usuario duplicado | ❌ Inválido | "Esta persona ya tiene un usuario registrado" |
| Admin sin permisos | ❌ Inválido | "La creación de usuarios administradores requiere permisos especiales" |

## Archivos Relacionados

- **Modelos**: `app/Models/UsuarioModel.php`, `MatriculaModel.php`, `GrupoModel.php`
- **Controlador**: `app/Controllers/UsuarioController.php`
- **Rutas**: `app/Config/Routes.php`
- **Base de Datos**: `app/Database/biblioteca_virtual.sql`
- **Ejemplo de Uso**: `ejemplo_uso_usuarios.php`

## Próximas Mejoras Sugeridas

1. **Tabla de Empleados**: Para validación más estricta de docentes
2. **Logs de Auditoría**: Registrar intentos de creación de usuarios
3. **Permisos Granulares**: Sistema de roles más detallado
4. **Validación de Documentos**: Verificar contra RENIEC/SUNEDU
5. **Notificaciones**: Email/SMS al crear usuarios
6. **Dashboard de Gestión**: Interfaz gráfica para administradores

## Notas Técnicas

- Compatible con CodeIgniter 4
- Utiliza el ORM y Query Builder de CI4
- Implementa el patrón Repository implícitamente
- Respuestas en formato JSON para APIs
- Validaciones del lado del servidor
- Preparado para validaciones AJAX del frontend

---

**Desarrollado para**: Biblioteca Virtual Escolar  
**Fecha**: Septiembre 2025  
**Versión**: 1.0.0
