<?php
// db.php

$host = 'sql207.infinityfree.com'; // Cambiar si es necesario
$dbname = 'if0_37679572_plataforma_educativa'; // Nombre de la base de datos
$username = 'if0_37679572'; // Usuario de la base de datos
$password = 'salva1919'; // Contraseña de la base de datos

try {
    // Crear una nueva conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar el modo de error para lanzar excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Mostrar un mensaje de error en caso de que la conexión falle
    echo "Error de conexión: " . $e->getMessage();
}
?>