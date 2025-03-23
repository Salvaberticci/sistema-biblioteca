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
$sql = "SELECT id, nombre, tipo, imagen, archivo, created_at, updated_at FROM recursos";
$stmt = $pdo->query($sql);
$recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener solo libros
$libros = array_filter($recursos, function ($recurso) {
    return $recurso['tipo'] === 'Libro';
});

// Arreglo de imágenes por tipo
$imagenesPorTipo = [
    'Documento' => 'Assets/images/imagen_documento.avif',
    'Video' => 'Assets/images/imagen_video.jpg',
    'Imagen' => 'Assets/images/imagen_imagen.avif',
    'Libro' => 'Assets/images/libro-icon.png',
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
    <title>Lista de Recursos y Libros Descargables</title>
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

        .seccion-libros {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Lista de Recursos y Libros Descargables</h1>

        <!-- Mensaje de éxito -->
        <?php if ($mensaje): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Botones principales -->
        <button class="btn btn-custom mb-2" data-toggle="modal" data-target="#modalSubirRecurso">
            <i class="fas fa-upload"></i> Subir Recurso
        </button>

        <button class="btn btn-custom mb-2" data-toggle="modal" data-target="#modalSubirLibro">
            <i class="fas fa-upload"></i> Subir Libro
        </button>

        <a class="btn btn-custom mb-2" href="index.php">
            <i class="fas fa-arrow-left"></i> Volver al Biblioteca
        </a>

        <!-- Recursos generales -->
        <div class="row">
            <?php foreach ($recursos as $recurso): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $imagenesPorTipo[$recurso['tipo']]; ?>" class="card-img-top" alt="<?php echo $recurso['nombre']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $recurso['nombre']; ?></h5>
                            <p class="card-text">Tipo: <?php echo $recurso['tipo']; ?></p>
                            <a href="<?php echo $recurso['archivo']; ?>" class="btn btn-primary" download>Descargar recurso</a>

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

        <!-- Sección de Libros -->
        <div class="seccion-libros">
            <h2>Libros Disponibles</h2>
            <div class="row">
                <?php foreach ($libros as $libro): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo $libro['imagen']; ?>" class="card-img-top" alt="Portada del libro">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $libro['nombre']; ?></h5>
                                <a href="<?php echo $libro['archivo']; ?>" class="btn btn-primary" download>Descargar Libro</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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
                            <select class="form-control" id="tipoRecurso" name="tipoRecurso">
                                <option value="Documento">Documento</option>
                                <option value="Video">Video</option>
                                <option value="Imagen">Imagen</option>
                                <option value="Libro">Libro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="archivoRecurso">Archivo del Recurso</label>
                            <input type="file" class="form-control-file" id="archivoRecurso" name="archivoRecurso" required>
                        </div>
                        <div class="form-group">
                            <label for="imagenRecurso">Imagen del Recurso</label>
                            <input type="file" class="form-control-file" id="imagenRecurso" name="imagenRecurso" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Subir Recurso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para subir libro -->
    <div class="modal fade" id="modalSubirLibro" tabindex="-1" aria-labelledby="modalSubirLibroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirLibroLabel">Subir Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para subir el libro y la portada -->
                    <form action="subir_libro.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombreLibro" class="form-label">Nombre del Libro</label>
                            <input type="text" class="form-control" id="nombreLibro" name="nombreLibro" required>
                        </div>

                        <div class="mb-3">
                            <label for="archivoLibro" class="form-label">Archivo (PDF o eBook)</label>
                            <input type="file" class="form-control" id="archivoLibro" name="archivoLibro" accept=".pdf, .epub, .mobi" required>
                        </div>

                        <div class="mb-3">
                            <label for="portadaLibro" class="form-label">Portada del Libro</label>
                            <input type="file" class="form-control" id="portadaLibro" name="portadaLibro" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoRecurso">Tipo de Recurso</label>
                            <select class="form-control" id="tipoRecurso" name="tipoRecurso">
                                <option value="Libro">Libro</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Subir Libro</button>
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
                                <option value="Libro">Libro</option>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#modalEditarRecurso').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var tipo = button.data('tipo');
            var archivo = button.data('archivo');
            var imagen = button.data('imagen');

            var modal = $(this);
            modal.find('#idRecurso').val(id);
            modal.find('#nombreRecursoEditar').val(nombre);
            modal.find('#tipoRecursoEditar').val(tipo);
            modal.find('#archivoRecursoEditar').val(archivo);
            modal.find('#imagenRecursoEditar').val(imagen);
        });
    </script>
</body>

</html>