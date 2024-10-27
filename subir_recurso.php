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

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombreRecurso = $_POST['nombreRecurso'];
    $tipoRecurso = $_POST['tipoRecurso'];
    $archivoRecurso = $_FILES['archivoRecurso'];

    // Verifica si el archivo fue subido sin errores
    if ($archivoRecurso['error'] == UPLOAD_ERR_OK) {
        $nombreArchivo = $archivoRecurso['name'];
        $rutaTemporal = $archivoRecurso['tmp_name'];
        $directorioSubida = 'uploads/';

        // Asegúrate de que el directorio de subida existe y es escribible
        if (!is_dir($directorioSubida)) {
            mkdir($directorioSubida, 0755, true);
        }

        // Mueve el archivo subido al directorio de subida
        $rutaDestino = $directorioSubida . basename($nombreArchivo);
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            // Inserta los datos en la base de datos
            $sql = "INSERT INTO recursos (nombre, tipo, imagen, created_at, updated_at) VALUES (:nombre, :tipo, :imagen, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombreRecurso,
                ':tipo' => $tipoRecurso,
                ':imagen' => $rutaDestino
            ]);

            // Redirige con un mensaje de éxito
            header("Location: lista_recursos.php?mensaje=El recurso se ha subido exitosamente.");
            exit();
        } else {
            // Redirige con un mensaje de error al mover el archivo
            header("Location: lista_recursos.php?mensaje=Error al mover el archivo subido.");
            exit();
        }
    } else {
        // Redirige con un mensaje de error al subir el archivo
        header("Location: lista_recursos.php?mensaje=Error al subir el archivo: " . $archivoRecurso['error']);
        exit();
    }
} else {
    // Redirige si no se envió ningún formulario
    header("Location: lista_recursos.php?mensaje=No se envió ningún formulario.");
    exit();
}
?>
