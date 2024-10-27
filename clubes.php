<?php
include 'db.php';

// Obtener clubes
$sql = "SELECT * FROM clubes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clubes de Lectura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/clubes-styles.css">
    
</head>

<body>
    <div class="container mt-5">
        <h1>Clubes de Lectura</h1>
        <a href="crear.php" class="btn btn-primary mb-3">Crear Nuevo Club</a>
        <a href="index.php" class="btn btn-primary mb-3">Volver a la biblioteca</a>        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>
                                <a href="editar_club.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Editar</a>
                                <a href="miembros.php?club_id=<?php echo $row['id']; ?>" class="btn btn-info">Gestionar Miembros</a>
                                <a href="reuniones.php?club_id=<?php echo $row['id']; ?>" class="btn btn-secondary">Gestionar Reuniones</a>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay clubes disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>