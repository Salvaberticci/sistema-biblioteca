<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clubes de Lectura</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?php echo base_url('index.php'); ?>">Inicio</a>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Clubes de Lectura</h1>
        <a class="btn btn-custom mb-2" href="<?php echo base_url('clubes/crear'); ?>">
            <i class="fas fa-plus"></i> Crear Club
        </a>

        <div class="row">
            <?php foreach ($clubes as $club): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $club->nombre; ?></h5>
                            <p class="card-text"><?php echo $club->descripcion; ?></p>
                            <a href="<?php echo base_url('miembros/index/'.$club->id); ?>" class="btn btn-primary">Ver Miembros</a>
                            <a href="<?php echo base_url('reuniones/index/'.$club->id); ?>" class="btn btn-secondary">Reuniones</a>
                            <a href="<?php echo base_url('noticias/index/'.$club->id); ?>" class="btn btn-info">Noticias</a>
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
