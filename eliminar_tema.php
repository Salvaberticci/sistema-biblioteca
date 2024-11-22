<?php
include 'db.php';

// Verificar si se ha recibido un ID de tema
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $tema_id = $_POST['id'];

    // Eliminar el tema de la base de datos
    $sql = "DELETE FROM temas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tema_id);

    if ($stmt->execute()) {
        header("Location: foro.php?mensaje=Tema eliminado correctamente");
    } else {
        header("Location: foro.php?mensaje=Error al eliminar el tema");
    }
} else {
    header("Location: foro.php?mensaje=Operación no permitida");
}
?>
