<?php
include 'db.php';

$club_id = $_GET['id'];

// Obtener información del club
$sql = "SELECT * FROM clubes WHERE id = $club_id";
$result = $conn->query($sql);
$club = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE clubes SET nombre='$nombre', descripcion='$descripcion' WHERE id=$club_id";
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
    <title>Editar Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/clubes-styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Club</h1>
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo $club['nombre']; ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3" required><?php echo $club['descripcion']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="clubes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
