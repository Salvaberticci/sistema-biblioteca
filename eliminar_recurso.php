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

// Verifica si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idRecurso = $_POST['id'];

    // Prepara la consulta para eliminar el recurso
    $sql = "DELETE FROM recursos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':id' => $idRecurso])) {
        // Redirige con un mensaje de éxito
        header("Location: lista_recursos.php?mensaje=El recurso se ha eliminado exitosamente.");
        exit();
    } else {
        // Redirige con un mensaje de error
        header("Location: lista_recursos.php?mensaje=Error al eliminar el recurso.");
        exit();
    }
} else {
    // Redirige si no se envió ningún formulario
    header("Location: lista_recursos.php?mensaje=No se envió ningún formulario para eliminar.");
    exit();
}
?>
