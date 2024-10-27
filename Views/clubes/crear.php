<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Club de Lectura</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
</head>
<body>
    <div class="container mt-4">
        <h1>Crear Club de Lectura</h1>
        <form action="<?php echo base_url('clubes/crear'); ?>" method="post">
            <div class="form-group">
                <label for="nombreClub">Nombre del Club</label>
                <input type="text" class="form-control" id="nombreClub" name="nombreClub" required>
            </div>
            <div class="form-group">
                <label for="descripcionClub">Descripción</label>
                <textarea class="form-control" id="descripcionClub" name="descripcionClub" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Crear Club</button>
            <a href="<?php echo base_url('clubes'); ?>" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>
