<?php
// Inicializar variables
$searchTerm = '';
$books = [];

// Verificar si se ha enviado el formulario
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);

    // URL de la API de Open Library para buscar por título
    $apiUrl = 'https://openlibrary.org/search.json?q=' . urlencode($searchTerm);

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar si hubo un error
    if (curl_errno($ch)) {
        echo 'Error en la solicitud: ' . curl_error($ch);
    } else {
        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Verificar si se encontraron libros
        if (isset($data['docs'])) {
            $books = $data['docs'];
        } else {
            echo 'No se encontraron libros.';
        }
    }

    // Cerrar cURL
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Libros en Open Library</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h1 class="mb-4">Búsqueda de Libros en Open Library</h1>

        <form method="post" action="">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Buscar por título" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
                <div class="input-group-append">
                    <button class="btn btn-custom" type="submit">Buscar</button>
                </div>
            </div>
        </form>

        <?php if (!empty($books)): ?>
            <h2>Resultados de búsqueda:</h2>
            <div class="row">
                <?php foreach ($books as $book): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if (isset($book['cover_i'])): ?>
                                <img src="https://covers.openlibrary.org/b/id/<?php echo $book['cover_i']; ?>-L.jpg" class="card-img-top" alt="Portada del libro">
                            <?php else: ?>
                                <img src="Assets/images/libro-icon.png" class="card-img-top" alt="Portada del libro">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                <p>Autor(es): <?php echo htmlspecialchars(implode(', ', $book['author_name'] ?? [])); ?></p>
                                <p>Fecha de publicación: <?php echo htmlspecialchars($book['first_publish_year'] ?? 'Sin fecha de publicación'); ?></p>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#infoModal"
                                    data-title="<?php echo htmlspecialchars($book['title']); ?>"
                                    data-authors="<?php echo htmlspecialchars(implode(', ', $book['author_name'] ?? [])); ?>"
                                    data-publish="<?php echo htmlspecialchars($book['first_publish_year'] ?? 'Sin fecha de publicación'); ?>"
                                    data-description="<?php echo htmlspecialchars($book['first_sentence'][0] ?? 'Descripción no disponible'); ?>">
                                    Más Información
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal para más información -->
    <div class="modal ```html
" id="infoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Información del Libro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="modalTitle"></h5>
                    <p><strong>Autor(es):</strong> <span id="modalAuthors"></span></p>
                    <p><strong>Fecha de publicación:</strong> <span id="modalPublish"></span></p>
                    <p><strong>Descripción:</strong> <span id="modalDescription"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#infoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var title = button.data('title');
            var authors = button.data('authors');
            var publish = button.data('publish');
            var description = button.data('description');

            // Actualizar el contenido del modal
            var modal = $(this);
            modal.find('#modalTitle').text(title);
            modal.find('#modalAuthors').text(authors);
            modal.find('#modalPublish').text(publish);
            modal.find('#modalDescription').text(description);
        });
    </script>
</body>

</html>