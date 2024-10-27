<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombreClub'];
    $descripcion = $_POST['descripcionClub'];

    $sql = "INSERT INTO clubes (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        header("Location: clubes.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Crear Nuevo Club</h1>
        <form method="POST">
            <div class="form-group">
                <label for="nombreClub">Nombre del Club</label>
                <input type="text" class="form-control" name="nombreClub" required>
            </div>
            <div class="form-group">
                <label for="descripcionClub">Descripción</label>
                <textarea class="form-control" name="descripcionClub" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Crear Club</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
