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

// Verificar si se recibió el ID del recurso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idRecurso'])) {
    $id = $_POST['idRecurso'];
    $nombre = $_POST['nombreRecurso'];
    $tipo = $_POST['tipoRecurso'];
    
    // Verificar si se subió un nuevo archivo
    if (!empty($_FILES['archivoRecurso']['name'])) {
        $imagen = $_FILES['archivoRecurso'];
        $nombreArchivo = $imagen['name'];
        $rutaTemporal = $imagen['tmp_name'];
        $rutaDestino = "Assets/images/" . $nombreArchivo;

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $sql = "UPDATE recursos SET nombre = ?, tipo = ?, imagen = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $tipo, $rutaDestino, $id]);
        } else {
            die("Error al mover el archivo a la carpeta de destino.");
        }
    } else {
        // Actualizar solo nombre y tipo si no se sube un nuevo archivo
        $sql = "UPDATE recursos SET nombre = ?, tipo = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $tipo, $id]);
    }

    // Redireccionar después de la actualización
    header("Location: lista_recursos.php?mensaje=Recurso actualizado correctamente.");
    exit();
} else {
    die("Error: No se ha recibido el ID del recurso.");
}
?>
