<?php
// Script temporal para ejecutar la migración de favoritos
// Ejecutar desde: http://bibliotecavirtualhzg.test/ejecutar_migracion.php

$host = 'localhost';
$database = 'biblioteca_virtual';
$username = 'root';
$password = '';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    echo "<h2>Ejecutando migración: Agregar campo fecha_agregado a favoritos</h2>";
    
    // Verificar si la columna ya existe
    $check = $conn->query("SHOW COLUMNS FROM favoritos LIKE 'fecha_agregado'");
    
    if ($check->num_rows > 0) {
        echo "<p style='color: orange;'>⚠️ La columna 'fecha_agregado' ya existe en la tabla 'favoritos'.</p>";
    } else {
        // Ejecutar la migración
        $sql = "ALTER TABLE favoritos ADD COLUMN fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER idrecurso";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>✅ Migración ejecutada exitosamente!</p>";
            echo "<p>Se agregó la columna 'fecha_agregado' a la tabla 'favoritos'.</p>";
        } else {
            echo "<p style='color: red;'>❌ Error al ejecutar la migración: " . $conn->error . "</p>";
        }
    }
    
    // Verificar la estructura de la tabla
    echo "<h3>Estructura actual de la tabla 'favoritos':</h3>";
    $result = $conn->query("DESCRIBE favoritos");
    
    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    
    $conn->close();
    
    echo "<hr>";
    echo "<p><strong>IMPORTANTE:</strong> Elimina este archivo después de ejecutar la migración por seguridad.</p>";
    echo "<p><a href='catalogo/favoritos'>Ir a Mis Favoritos</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
