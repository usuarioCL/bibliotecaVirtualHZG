<?php
/**
 * Endpoint para renderizar tarjetas usando el componente PHP libro_card.php
 * Este archivo actúa como puente entre JavaScript y el componente PHP
 */

// Verificar que sea una petición POST con datos JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener datos JSON del cuerpo de la petición
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['libros'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$libros = $data['libros'];
$colClasses = $data['colClasses'] ?? 'col-lg-2 col-md-4 col-sm-6';

// Generar HTML usando el componente libro_card.php
$html = '';
foreach ($libros as $libro) {
    // Asegurar que los datos estén en el formato correcto
    if (!isset($libro['portada']) && isset($libro['rutaportada'])) {
        $libro['portada'] = $libro['rutaportada'];
    }
    
    // Capturar la salida del componente
    ob_start();
    include __DIR__ . '/../partials/libro_card.php';
    $html .= ob_get_clean();
}

// Devolver el HTML generado
header('Content-Type: text/html; charset=utf-8');
echo $html;
?>
