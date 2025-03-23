<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'biblioteca_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreLibro = $_POST['nombreLibro'];
    $tipoRecurso = $_POST['tipoRecurso'];
    $archivoLibro = $_FILES['archivoLibro'];
    $portadaLibro = $_FILES['portadaLibro'];

    $directorioSubida = 'uploads/';
    $errores = [];

    // Validación y manejo del archivo del libro
    if ($archivoLibro['error'] == UPLOAD_ERR_OK) {
        $nombreArchivoLibro = basename($archivoLibro['name']);
        $rutaLibro = $directorioSubida . $nombreArchivoLibro;

        if (!move_uploaded_file($archivoLibro['tmp_name'], $rutaLibro)) {
            $errores[] = "Error al subir el archivo del libro.";
        }
    } else {
        $errores[] = "Archivo del libro no válido.";
    }

    // Validación y manejo de la portada
    if ($portadaLibro['error'] == UPLOAD_ERR_OK) {
        $nombreArchivoPortada = basename($portadaLibro['name']);
        $rutaPortada = $directorioSubida . $nombreArchivoPortada;

        if (!move_uploaded_file($portadaLibro['tmp_name'], $rutaPortada)) {
            $errores[] = "Error al subir la portada del libro.";
        }
    } else {
        $errores[] = "Archivo de la portada no válido.";
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO recursos (nombre, tipo, imagen, archivo, created_at, updated_at) 
                VALUES (:nombre, :tipo, :imagen, :archivo, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombreLibro,
                ':tipo' => $tipoRecurso,
                ':imagen' => $rutaPortada, // Guardamos la ruta de la portada como "imagen"
                ':archivo' => $rutaLibro // Guardamos la ruta del archivo del libro
            ]);

            header("Location: lista_recursos.php?mensaje=Libro subido exitosamente.");
        } catch (PDOException $e) {
            $errores[] = "Error al guardar en la base de datos: " . $e->getMessage();
        }
    }
}

// Mostrar errores si existen
if (!empty($errores)) {
    foreach ($errores as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
}
