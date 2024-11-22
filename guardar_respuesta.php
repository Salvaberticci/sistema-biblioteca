<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tema_id = $_POST['tema_id'];
    $comentario_padre = $_POST['comentario_padre'] ?: NULL;
    $usuario = $_POST['usuario'];
    $texto = $_POST['texto'];
    $imagen = null;

    // Subir imagen si existe
    if (!empty($_FILES['imagen']['name'])) {
        $target_dir = "uploads/";
        $imagen = time() . "_" . basename($_FILES['imagen']['name']);
        $target_file = $target_dir . $imagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
    }

    // Insertar comentario/respuesta en la base de datos
    $stmt = $conn->prepare("INSERT INTO comentarios (tema_id, comentario_padre, usuario, texto, imagen, creado_en) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $tema_id, $comentario_padre, $usuario, $texto, $imagen);
    $stmt->execute();

    header("Location: tema_detalle.php?id=$tema_id");
    exit();
}
?>
