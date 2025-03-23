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


// Arreglo de imágenes por tipo
$imagenesPorTipo = [
    'Documento' => 'Assets/images/imagen_documento.avif',
    'Video' => 'Assets/images/imagen_video.jpg',
    'Imagen' => 'Assets/images/imagen_imagen.avif',
    'Libro' => 'Assets/images/libro-icon.png',
];


// Consulta de libros
$sqlLibros = "SELECT titulo, descripcion, imagen FROM libro WHERE estado = 1";
$stmtLibros = $pdo->query($sqlLibros);
$libros = $stmtLibros->fetchAll(PDO::FETCH_ASSOC);

// Consulta de recursos
$sqlRecursos = "SELECT id, nombre, tipo, imagen, archivo, created_at, updated_at FROM recursos";
$stmtRecursos = $pdo->query($sqlRecursos);
$recursos = $stmtRecursos->fetchAll(PDO::FETCH_ASSOC);

// Obtener solo libros
$librosDigitales = array_filter($recursos, function ($recurso) {
    return $recurso['tipo'] === 'Libro';
});
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vista Móvil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #ed8414;
            color: #fff;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
        }

        .section-title {
            color: #2c662d;
            font-weight: bold;
            margin-top: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card img {
            height: 150px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .card-body {
            padding: 10px;
        }

        .btn-download {
            background-color: #2c662d;
            color: #fff;
        }

        .btn-download:hover {
            background-color: #248b24;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #ed8414;
            text-align: center;
            color: white;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand mx-auto" href="#">Biblioteca</a>
    </nav>

    <div class="container mt-4">
        <!-- Sección de Libros -->
        <h2 class="section-title">Libros</h2>
        <div class="row">
            <?php foreach ($libros as $libro): ?>
                <div class="col-6 mb-4">
                    <div class="card">
                        <img src="Assets/images/libros/<?php echo $libro['imagen']; ?>" alt="Imagen del libro">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $libro['titulo']; ?></h5>
                            <p class="card-text"><?php echo $libro['descripcion']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sección de Recursos -->
        <h2 class="section-title">Recursos</h2>
        <div class="row">
            <?php foreach ($recursos as $recurso): ?>
                <div class="col-6 mb-4">
                    <div class="card">
                        <img src="<?php echo $imagenesPorTipo[$recurso['tipo']]; ?>" alt="Imagen del recurso">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $recurso['nombre']; ?></h5>
                            <p class="card-text">Tipo: <?php echo $recurso['tipo']; ?></p>
                            <a href="<?php echo $recurso['imagen']; ?>" class="btn btn-download btn-sm" download>Descargar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Sección de Libros -->
        <h2 class="section-title">Libros Digitales Descargables</h2>
        <div class="row">
            <?php foreach ($librosDigitales as $libro): ?>
                <div class="col-6 mb-4">
                    <div class="card">
                        <img src="<?php echo $libro['imagen']; ?>" class="card-img-top" alt="Portada del libro">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $libro['nombre']; ?></h5>
                            <a href="<?php echo $libro['archivo']; ?>" class="btn btn-download btn-sm" download>Descargar Libro</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>