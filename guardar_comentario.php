<?php
include 'db.php';

$tema_id = $_POST['tema_id'];
$usuario = $_POST['usuario'];
$texto = $_POST['texto'];
$imagen = null;

// Subir imagen si está presente
if ($_FILES['imagen']['name']) {
    $target_dir = "uploads/";
    $imagen = basename($_FILES['imagen']['name']);
    $target_file = $target_dir . $imagen;
    move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
}

$sql = "INSERT INTO comentarios (tema_id, usuario, texto, imagen) VALUES ('$tema_id', '$usuario', '$texto', '$imagen')";
$conn->query($sql);

header("Location: tema_detalle.php?id=$tema_id");
?>
