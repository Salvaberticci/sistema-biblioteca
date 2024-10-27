<?php
include 'db.php';

$club_id = $_GET['club_id'];

// Manejo de formulario de nueva reunión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO reuniones (club_id, fecha, hora, descripcion) VALUES ('$club_id', '$fecha', '$hora', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        header("Location: reuniones.php?club_id=$club_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Obtener reuniones del club
$sqlReuniones = "SELECT * FROM reuniones WHERE club_id = $club_id ORDER BY fecha, hora";
$resultReuniones = $conn->query($sqlReuniones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reuniones del Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/clubes-styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Reuniones del Club</h1>
        <a href="clubes.php" class="btn btn-secondary mb-3">Volver a Clubes</a>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" class="form-control" name="fecha" required>
            </div>
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" class="form-control" name="hora" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Reunión</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultReuniones->num_rows > 0): ?>
                    <?php while ($row = $resultReuniones->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['hora']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>
                                <a href="eliminar_reunion.php?id=<?php echo $row['id']; ?>&club_id=<?php echo $club_id; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay reuniones programadas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
