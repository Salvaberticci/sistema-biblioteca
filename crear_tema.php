<?php
include 'db.php';

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO temas (titulo, descripcion) VALUES ('$titulo', '$descripcion')";
$conn->query($sql);

header("Location: index.php");
?>
