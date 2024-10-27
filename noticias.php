<?php
include 'db.php';

$club_id = $_GET['club_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['tituloNoticia'];
    $contenido = $_POST['contenidoNoticia'];

    $sql = "INSERT INTO noticias (club_id, titulo, contenido) VALUES ('$club_id', '$titulo', '$contenido')";
    if ($conn->query($sql) === TRUE) {
        header("Location: noticias.php?club_id=$club_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Obtener noticias
$sqlNoticias = "SELECT * FROM noticias WHERE club_id = $club_id";
$resultNoticias = $conn->query($sqlNoticias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Noticias del Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Noticias del Club</h1>
        <a href="index.php" class="btn btn-secondary mb-3">Volver a Clubes</a>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="tituloNoticia">Título</label>
                <input type="text" class="form-control" name="tituloNoticia" required>
            </div>
            <div class="form-group">
                <label for="contenidoNoticia">Contenido</label>
                <textarea class="form-control" name="contenidoNoticia" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Noticia</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Contenido</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultNoticias->num_rows > 0): ?>
                    <?php while ($row = $resultNoticias->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['titulo']; ?></td>
                            <td><?php echo $row['contenido']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay noticias disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
