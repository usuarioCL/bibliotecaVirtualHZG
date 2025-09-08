# Sistema de Registro de Usuarios - Biblioteca Virtual

## Descripción

El sistema de registro de usuarios ha sido mejorado para garantizar que solo las personas que están matriculadas en la institución educativa (estudiantes) o que trabajan en ella (docentes) puedan crear cuentas de usuario.

## Funcionalidades Implementadas

### 1. Validación de Elegibilidad para Registro

El sistema verifica automáticamente si una persona puede registrarse basándose en:

- **Estudiantes**: Deben estar matriculados activamente en la institución
- **Docentes**: No deben tener matrícula activa como estudiantes
- **Administradores**: Solo pueden ser creados manualmente por el sistema (por seguridad)

### 2. Modelos Mejorados

#### UsuarioModel.php
- Validaciones automáticas de datos
- Método `validarRegistroPersona()` para verificar elegibilidad
- Método `crearUsuarioValidado()` para registro seguro
- Encriptación automática de contraseñas
- Métodos para obtener información completa del usuario

#### MatriculaModel.php (Nuevo)
- Verificación de estado de matrícula
- Métodos para consultar matrículas activas
- Identificación de estudiantes vs docentes

### 3. Controlador de Registro Mejorado

#### RegistroController.php
- Búsqueda de personas por número de documento
- Validación en tiempo real de disponibilidad de nombres de usuario
- Procesamiento seguro del registro
- Manejo de errores detallado

### 4. Rutas API para AJAX

```php
// Buscar persona por documento
POST /registro/buscar-persona

// Validar disponibilidad de nombre de usuario  
POST /registro/validar-usuario

// Procesar registro
POST /registro
```

## Flujo de Registro

### Para Estudiantes:

1. El estudiante ingresa su número de documento
2. El sistema busca la persona en la base de datos
3. Verifica que tenga una matrícula activa
4. Si está matriculado, permite registro como "estudiante"
5. El estudiante completa los datos del usuario
6. Se crea la cuenta con nivel de acceso "estudiante"

### Para Docentes:

1. El docente ingresa su número de documento
2. El sistema busca la persona en la base de datos
3. Verifica que NO tenga matrícula activa (no es estudiante)
4. Si cumple la condición, permite registro como "docente"
5. El docente completa los datos del usuario
6. Se crea la cuenta con nivel de acceso "docente"

## Estructura de Base de Datos

### Tablas Principales Involucradas:

- **personas**: Datos personales básicos
- **usuarios**: Cuentas de usuario del sistema
- **matriculas**: Registro de matrículas de estudiantes
- **grupos**: Grupos/clases donde están matriculados los estudiantes

### Relaciones:

```
personas (1) ←→ (0,1) usuarios
personas (1) ←→ (0,n) matriculas
grupos (1) ←→ (0,n) matriculas
```

## Ejemplo de Uso

### Validar si una persona puede registrarse:

```php
$usuarioModel = new UsuarioModel();
$validacion = $usuarioModel->validarRegistroPersona($idpersona, 'estudiante');

if ($validacion['valido']) {
    // Puede registrarse
    echo $validacion['mensaje'];
} else {
    // No puede registrarse
    echo $validacion['mensaje'];
}
```

### Crear un usuario validado:

```php
$datos = [
    'idpersona' => 1,
    'nomuser' => 'juan.perez',
    'passuser' => 'mipassword123',
    'nivelacceso' => 'estudiante'
];

$idUsuario = $usuarioModel->crearUsuarioValidado($datos);

if ($idUsuario) {
    echo "Usuario creado con ID: " . $idUsuario;
} else {
    $errores = $usuarioModel->getCustomErrors();
    echo "Error: " . implode(', ', $errores);
}
```

## Testing

Para probar el sistema, puedes usar las rutas de testing:

- `GET /test/registro` - Ejecuta ejemplos de registro
- `GET /test/limpiar` - Limpia los datos de prueba

## Seguridad

1. **Contraseñas**: Se encriptan automáticamente usando `password_hash()`
2. **Validación**: Doble validación (frontend y backend)
3. **Unicidad**: Cada persona solo puede tener un usuario
4. **Niveles de acceso**: Controlados según estado de matrícula
5. **Administradores**: No se pueden crear mediante registro público

## Personalización

Para adaptar el sistema a necesidades específicas:

1. **Validación de docentes**: Modificar `MatriculaModel::esDocente()` para usar tabla de empleados
2. **Niveles adicionales**: Agregar nuevos niveles en el enum de la BD y en las validaciones
3. **Reglas de negocio**: Personalizar `UsuarioModel::validarRegistroPersona()`

## Archivos Modificados/Creados

- `app/Models/UsuarioModel.php` (mejorado)
- `app/Models/MatriculaModel.php` (nuevo)
- `app/Models/GrupoModel.php` (corregido campo)
- `app/Controllers/RegistroController.php` (mejorado)
- `app/Controllers/TestRegistroController.php` (nuevo)
- `app/Config/Routes.php` (rutas agregadas)
