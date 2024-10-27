<?php
include 'db.php';

$club_id = $_GET['club_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    $sql = "INSERT INTO miembros (club_id, nombre, correo) VALUES ('$club_id', '$nombre', '$correo')";
    if ($conn->query($sql) === TRUE) {
        header("Location: miembros.php?club_id=$club_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Obtener miembros
$sqlMiembros = "SELECT * FROM miembros WHERE club_id = $club_id";
$resultMiembros = $conn->query($sqlMiembros);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Miembros del Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/clubes-styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Miembros del Club</h1>
        <a href="clubes.php" class="btn btn-secondary mb-3">Volver a Clubes</a>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" class="form-control" name="correo" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Miembro</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultMiembros->num_rows > 0): ?>
                    <?php while ($row = $resultMiembros->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['correo']; ?></td>
                            <td>
                                <a href="eliminar_miembro.php?id=<?php echo $row['id']; ?>&club_id=<?php echo $club_id; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay miembros disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
