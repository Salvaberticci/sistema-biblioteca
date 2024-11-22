<?php
include 'db.php';

// Obtener el ID del tema desde la URL
$tema_id = $_GET['id'];

// Consultar los detalles del tema
$sql_tema = "SELECT * FROM temas WHERE id = ?";
$stmt_tema = $conn->prepare($sql_tema);
$stmt_tema->bind_param("i", $tema_id);
$stmt_tema->execute();
$result_tema = $stmt_tema->get_result();
$tema = $result_tema->fetch_assoc();

// Consultar todos los comentarios relacionados con el tema
$sql_comentarios = "SELECT * FROM comentarios WHERE tema_id = ? ORDER BY creado_en ASC";
$stmt_comentarios = $conn->prepare($sql_comentarios);
$stmt_comentarios->bind_param("i", $tema_id);
$stmt_comentarios->execute();
$result_comentarios = $stmt_comentarios->get_result();
$comentarios = $result_comentarios->fetch_all(MYSQLI_ASSOC);

// Función recursiva para mostrar comentarios y respuestas
function mostrarComentarios($comentarios, $parent_id = NULL, $nivel = 0) {
    foreach ($comentarios as $comentario) {
        if ($comentario['comentario_padre'] == $parent_id) {
            ?>
            <div class="comment-container" style="margin-left: <?php echo $nivel * 20; ?>px;">
                <div class="comment-card">
                    <div class="comment-header">
                        <strong><?php echo $comentario['usuario']; ?></strong>
                        <span class="text-muted small"><?php echo $comentario['creado_en']; ?></span>
                    </div>
                    <p><?php echo $comentario['texto']; ?></p>
                    <?php if ($comentario['imagen']): ?>
                        <img src="uploads/<?php echo $comentario['imagen']; ?>" class="img-fluid comment-image" alt="Imagen del comentario">
                    <?php endif; ?>
                    <button class="btn btn-sm btn-custom responder-btn" data-id="<?php echo $comentario['id']; ?>" data-toggle="modal" data-target="#modalResponder">Responder</button>
                </div>
            </div>
            <?php
            mostrarComentarios($comentarios, $comentario['id'], $nivel + 1);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $tema['titulo']; ?></title>
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
        .comment-container {
            margin-bottom: 20px;
        }
        .comment-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .comment-image {
            margin-top: 10px;
            max-width: 100%;
            border-radius: 5px;
        }
        .modal-content {
            border-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-success"><?php echo $tema['titulo']; ?></h1>
        <p><?php echo $tema['descripcion']; ?></p>
        <a class="btn btn-custom mb-2" href="foro.php">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <hr>
        <h3>Comentarios</h3>
        <!-- Mostrar comentarios en cascada -->
        <?php mostrarComentarios($comentarios); ?>

        <hr>

        <!-- Formulario para agregar comentarios -->
        <div class="form-container">
            <h4>Agregar un Comentario</h4>
            <form method="POST" action="guardar_comentario.php" enctype="multipart/form-data">
                <input type="hidden" name="tema_id" value="<?php echo $tema_id; ?>">
                <div class="form-group">
                    <label for="usuario">Nombre:</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="texto">Comentario:</label>
                    <textarea name="texto" id="texto" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen (opcional):</label>
                    <input type="file" name="imagen" id="imagen" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-custom">Comentar</button>
            </form>
        </div>
    </div>

    <!-- Modal para responder a un comentario -->
    <div class="modal fade" id="modalResponder" tabindex="-1" role="dialog" aria-labelledby="modalResponderLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResponderLabel">Responder a Comentario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="respuesta-form" method="POST" action="guardar_respuesta.php" enctype="multipart/form-data">
                        <input type="hidden" name="tema_id" value="<?php echo $tema_id; ?>">
                        <input type="hidden" name="comentario_padre" id="comentario_padre" value="">
                        <div class="form-group">
                            <label for="usuario">Nombre:</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="texto">Respuesta:</label>
                            <textarea name="texto" id="texto" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Imagen (opcional):</label>
                            <input type="file" name="imagen" id="imagen" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-custom">Enviar Respuesta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Configurar el modal de respuestas
        $('#modalResponder').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const comentarioId = button.data('id');
            const modal = $(this);
            modal.find('#comentario_padre').val(comentarioId);
        });
    </script>
</body>
</html>
