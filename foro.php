<?php
include 'db.php';

// Obtener todos los temas
$sql = "SELECT * FROM temas ORDER BY creado_en DESC";
$temas = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Foro General</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        .btn-custom {
            background-color: #ed8414;
            color: white;
        }

        .btn-custom:hover {
            background-color: #ff9b34;
        }

        .btn-danger-custom {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger-custom:hover {
            background-color: #c82333;
        }

        .tema-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .tema-card a {
            text-decoration: none;
            color: #333;
        }

        .tema-card a:hover {
            color: #ed8414;
        }

        .tema-card h5 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .tema-card p {
            margin-bottom: 0;
        }

        .tema-card small {
            font-size: 0.85em;
            color: #6c757d;
        }

        .form-container {
            background-color: #f1f3f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-container h3 {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-success text-center mb-4">Foro General</h1>
        <a class="btn btn-custom mb-2" href="index.php">
            <i class="fas fa-arrow-left"></i> Volver al Biblioteca
        </a>

        <!-- Crear Nuevo Tema -->
        <div class="form-container">
            <form action="crear_tema.php" method="POST">
                <h3>Crear Nuevo Tema</h3>
                <div class="form-group">
                    <label for="titulo">Título del Tema:</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-custom">Crear Tema</button>
            </form>
        </div>

        <!-- Listado de Temas -->
        <h3 class="text-success">Temas Recientes</h3>
        <?php if ($temas->num_rows > 0): ?>
            <?php while ($tema = $temas->fetch_assoc()): ?>
                <div class="tema-card">
                    <a href="tema_detalle.php?id=<?php echo $tema['id']; ?>">
                        <h5><?php echo $tema['titulo']; ?></h5>
                        <p><?php echo $tema['descripcion']; ?></p>
                    </a>
                    <small>Publicado el <?php echo $tema['creado_en']; ?></small>
                    
                    <!-- Botón Eliminar -->
                    <form action="eliminar_tema.php" method="POST" class="position-absolute" style="top: 10px; right: 10px;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tema?');">
                        <input type="hidden" name="id" value="<?php echo $tema['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger-custom">Eliminar</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No hay temas disponibles</p>
        <?php endif; ?>
    </div>
</body>

</html>
