<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";  // Cambia esto según tu configuración
$username = "root";   // Cambia esto según tu configuración
$password = ""; // Cambia esto según tu configuración
$database = "biblioteca_bd";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para obtener los recursos
$sql = "SELECT id, nombre, tipo, imagen, created_at, updated_at FROM recursos";
$result = $conn->query($sql);

// Arreglo para almacenar los recursos
$recursos = [];

if ($result->num_rows > 0) {
    // Iterar sobre los resultados y almacenarlos en el arreglo $recursos
    while($row = $result->fetch_assoc()) {
        $recursos[] = $row;
    }
} else {
    echo "No se encontraron resultados";
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Recursos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo general */
        }

        .container {
            margin-top: 20px;
        }

        .btn-custom {
            background-color: #ed8414;
            border-color: #ed8414;
            color: white;
        }

        .btn-custom:hover,
        .btn-custom:focus {
            background-color: #ffab40; /* Color de hover */
            border-color: #ffab40;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Inicio</a>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Lista de Recursos</h1>
        <!-- Botón para abrir la ventana modal -->
        <button class="btn btn-custom mb-2" data-toggle="modal" data-target="#modalSubirRecurso">
            <i class="fas fa-upload"></i> Subir Recurso
        </button>
        <a class="btn btn-custom mb-2" href="#"> <!-- Aquí puedes ajustar el enlace -->
            <i class="fas fa-arrow-left"></i> Volver al Índice
        </a>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Imagen</th>
                        <th>Fecha de Creación</th>
                        <th>Fecha de Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recursos as $recurso): ?>
                        <tr>
                            <td><?php echo $recurso['nombre']; ?></td>
                            <td><?php echo $recurso['tipo']; ?></td>
                            <td><?php echo $recurso['imagen']; ?></td>
                            <td><?php echo $recurso['created_at']; ?></td>
                            <td><?php echo $recurso['updated_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ventana modal para subir recurso -->
    <div class="modal fade" id="modalSubirRecurso" tabindex="-1" role="dialog" aria-labelledby="modalSubirRecursoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirRecursoLabel">Subir Recurso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para subir el recurso -->
                    <form action="subir_recurso.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombreRecurso">Nombre del Recurso</label>
                            <input type="text" class="form-control" id="nombreRecurso" name="nombreRecurso" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoRecurso">Tipo de Recurso</label>
                            <select class="form-control" id="tipoRecurso" name="tipoRecurso" required>
                                <option value="Documento">Documento</option>
                                <option value="Video">Video</option>
                                <option value="Imagen">Imagen</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="archivoRecurso">Archivo</label>
                            <input type="file" class="form-control-file" id="archivoRecurso" name="archivoRecurso" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Subir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
