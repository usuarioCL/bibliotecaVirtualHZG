<?php

// Database connection settings
$servername = "localhost";
$username = "username"; // Change this to your database username
$password = "password"; // Change this to your database password
dbname = "library_db"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include PHPExcel
require 'PHPExcel.php';

// Load the Excel file
$inputFileName = 'categorias.xlsx'; // Change this to your Excel file path
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO categorias (categoria) VALUES (?)");

// Loop through each row of the Excel file
foreach ($objPHPExcel->getActiveSheet()->getRowIterator() as $row) {
    $data = [];
    foreach ($row->getCellIterator() as $cell) {
        $data[] = $cell->getValue();
    }

    // Bind the category value
    $stmt->bind_param("s", $data[0]);

    // Execute the statement
    $stmt->execute();
}

// Close the statement and connection
$stmt->close();
$conn->close();

echo "Categories imported successfully!";
?>