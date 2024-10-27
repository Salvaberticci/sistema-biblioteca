<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'biblioteca_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Consulta para obtener los recursos
$sql = "SELECT id, nombre, tipo, imagen, created_at, updated_at FROM recursos";
$stmt = $pdo->query($sql);
$recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Arreglo de imágenes por tipo
$imagenesPorTipo = [
    'Documento' => 'Assets/images/imagen_documento.avif',
    'Video' => 'Assets/images/imagen_video.jpg',
    'Imagen' => 'Assets/images/imagen_imagen.avif',
];

// Mensaje de éxito
$mensaje = '';
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Recursos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/lista-recursos-styles.css">
    <style>
        body {
            background-color: #f8f9fa;
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
            background-color: #ffab40;
            border-color: #ffab40;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <h1 class="mb-4">Lista de Recursos</h1>

        <!-- Mensaje de éxito -->
        <?php if ($mensaje): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <button class="btn btn-custom mb-2" data-toggle="modal" data-target="#modalSubirRecurso">
            <i class="fas fa-upload"></i> Subir Recurso
        </button>
        <a class="btn btn-custom mb-2" href="index.php">
            <i class="fas fa-arrow-left"></i> Volver al Biblioteca
        </a>

        <div class="row">
            <?php foreach ($recursos as $recurso): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $imagenesPorTipo[$recurso['tipo']]; ?>" class="card-img-top" alt="<?php echo $recurso['nombre']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $recurso['nombre']; ?></h5>
                            <p class="card-text">Tipo: <?php echo $recurso['tipo']; ?></p>
                            <a href="<?php echo $recurso['imagen']; ?>" class="btn btn-primary" download>Descargar Curso</a>
                            
                            <!-- Botón para editar recurso -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalEditarRecurso" data-id="<?php echo $recurso['id']; ?>" data-nombre="<?php echo $recurso['nombre']; ?>" data-tipo="<?php echo $recurso['tipo']; ?>" data-imagen="<?php echo $recurso['imagen']; ?>">
                                Editar
                            </button>

                            <!-- Botón para eliminar recurso -->
                            <form action="eliminar_recurso.php" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este recurso?');">
                                <input type="hidden" name="id" value="<?php echo $recurso['id']; ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

    <!-- Ventana modal para editar recurso -->
    <div class="modal fade" id="modalEditarRecurso" tabindex="-1" role="dialog" aria-labelledby="modalEditarRecursoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarRecursoLabel">Editar Recurso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="editar_recurso.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="idRecurso" name="idRecurso">
                        <div class="form-group">
                            <label for="nombreRecursoEditar">Nombre del Recurso</label>
                            <input type="text" class="form-control" id="nombreRecursoEditar" name="nombreRecurso" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoRecursoEditar">Tipo de Recurso</label>
                            <select class="form-control" id="tipoRecursoEditar" name="tipoRecurso" required>
                                <option value="Documento">Documento</option>
                                <option value="Video">Video</option>
                                <option value="Imagen">Imagen</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="archivoRecursoEditar">Archivo (opcional)</label>
                            <input type="file" class="form-control-file" id="archivoRecursoEditar" name="archivoRecurso">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Script para cargar los datos en el modal de edición
        $('#modalEditarRecurso').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var tipo = button.data('tipo');
            var imagen = button.data('imagen');

            var modal = $(this);
            modal.find('#idRecurso').val(id);
            modal.find('#nombreRecursoEditar').val(nombre);
            modal.find('#tipoRecursoEditar').val(tipo);
        });
    </script>
</body>
</html>
