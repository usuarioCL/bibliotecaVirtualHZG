<?php
/**
 * EJEMPLO DE USO: Sistema de Creación de Usuarios con Validación de Matrícula
 * Biblioteca Virtual Escolar
 * 
 * Este archivo muestra cómo usar la funcionalidad implementada para crear usuarios
 * validando que solo estudiantes matriculados o docentes puedan registrarse.
 */

require_once 'vendor/autoload.php';

use App\Models\UsuarioModel;
use App\Models\PersonaModel;
use App\Models\MatriculaModel;
use App\Models\GrupoModel;

// Ejemplo 1: Crear un usuario estudiante
echo "=== EJEMPLO 1: Crear Usuario Estudiante ===\n";

$usuarioModel = new UsuarioModel();

// Datos del usuario a crear
$datosUsuario = [
    'nomuser' => 'juan.perez',
    'passuser' => 'password123',
    'nivelacceso' => 'estudiante',
    'idpersona' => 1 // ID de una persona que debe estar matriculada
];

// Crear usuario con validación automática
$resultado = $usuarioModel->crearUsuarioConValidacion($datosUsuario);

if ($resultado['exito']) {
    echo "✅ Usuario creado exitosamente\n";
    echo "   ID: {$resultado['id']}\n";
    echo "   Mensaje: {$resultado['mensaje']}\n";
} else {
    echo "❌ Error al crear usuario\n";
    echo "   Mensaje: {$resultado['mensaje']}\n";
}

echo "\n";

// Ejemplo 2: Verificar elegibilidad antes de crear
echo "=== EJEMPLO 2: Verificar Elegibilidad ===\n";

$idpersona = 2;
$nivelacceso = 'estudiante';

$validacion = $usuarioModel->validarElegibilidadUsuario($idpersona, $nivelacceso);

if ($validacion['valido']) {
    echo "✅ La persona es elegible para crear usuario\n";
    echo "   Mensaje: {$validacion['mensaje']}\n";
} else {
    echo "❌ La persona NO es elegible\n";
    echo "   Motivo: {$validacion['mensaje']}\n";
}

echo "\n";

// Ejemplo 3: Verificar matrícula de una persona
echo "=== EJEMPLO 3: Verificar Matrícula ===\n";

$matriculaModel = new MatriculaModel();
$idpersona = 1;

if ($matriculaModel->personaEstaMatriculada($idpersona)) {
    echo "✅ La persona está matriculada\n";
    
    $matricula = $matriculaModel->getMatriculaActiva($idpersona);
    if ($matricula) {
        echo "   Nivel: {$matricula['nivel']}\n";
        echo "   Grado: {$matricula['grado']}\n";
        echo "   Sección: {$matricula['seccion']}\n";
        echo "   Año Lectivo: {$matricula['aniolectivo']}\n";
    }
} else {
    echo "❌ La persona NO está matriculada\n";
}

echo "\n";

// Ejemplo 4: Obtener información completa de usuario
echo "=== EJEMPLO 4: Información Completa de Usuario ===\n";

$idusuario = 1; // Suponiendo que existe
$usuarioCompleto = $usuarioModel->getUsuarioCompleto($idusuario);

if ($usuarioCompleto) {
    echo "✅ Usuario encontrado\n";
    echo "   Nombre: {$usuarioCompleto['nombres']} {$usuarioCompleto['apellidos']}\n";
    echo "   Usuario: {$usuarioCompleto['nomuser']}\n";
    echo "   Nivel: {$usuarioCompleto['nivelacceso']}\n";
    echo "   Email: {$usuarioCompleto['email']}\n";
    
    // Si es estudiante, mostrar matrícula
    if ($usuarioCompleto['nivelacceso'] === 'estudiante') {
        $matriculaUsuario = $usuarioModel->getMatriculaUsuario($idusuario);
        if ($matriculaUsuario) {
            echo "   Matrícula: {$matriculaUsuario['nivel']} - {$matriculaUsuario['grado']}°{$matriculaUsuario['seccion']}\n";
        }
    }
} else {
    echo "❌ Usuario no encontrado\n";
}

echo "\n";

// Ejemplo 5: Casos de error comunes
echo "=== EJEMPLO 5: Casos de Error ===\n";

// Intentar crear usuario para persona no matriculada
$datosInvalidos = [
    'nomuser' => 'no.matriculado',
    'passuser' => 'password123',
    'nivelacceso' => 'estudiante',
    'idpersona' => 999 // ID que no existe o no está matriculado
];

$resultadoError = $usuarioModel->crearUsuarioConValidacion($datosInvalidos);
echo "❌ Intento de crear usuario no válido: {$resultadoError['mensaje']}\n";

// Intentar crear usuario admin (requiere permisos especiales)
$datosAdmin = [
    'nomuser' => 'admin.test',
    'passuser' => 'password123',
    'nivelacceso' => 'admin',
    'idpersona' => 1
];

$resultadoAdmin = $usuarioModel->crearUsuarioConValidacion($datosAdmin);
echo "❌ Intento de crear admin: {$resultadoAdmin['mensaje']}\n";

echo "\n=== FIN DE EJEMPLOS ===\n";

/**
 * NOTAS IMPORTANTES:
 * 
 * 1. VALIDACIÓN DE ESTUDIANTES:
 *    - Los estudiantes DEBEN estar matriculados (tabla matriculas)
 *    - La matrícula debe estar activa (estadomatricula = true)
 *    - Se verifica contra la tabla personas, matriculas y grupos
 * 
 * 2. VALIDACIÓN DE DOCENTES:
 *    - Los docentes deben existir en la tabla personas
 *    - No requieren matrícula como estudiantes
 *    - Puedes agregar validaciones adicionales (ej: tabla empleados)
 * 
 * 3. VALIDACIÓN DE ADMINISTRADORES:
 *    - Requieren permisos especiales para ser creados
 *    - Solo otros administradores deberían poder crearlos
 * 
 * 4. PREVENCIÓN DE DUPLICADOS:
 *    - Una persona solo puede tener un usuario
 *    - Los nombres de usuario deben ser únicos
 * 
 * 5. SEGURIDAD:
 *    - Las contraseñas se encriptan automáticamente
 *    - Se validan todos los campos obligatorios
 *    - Se verifican las reglas de negocio antes de crear
 */
?>
