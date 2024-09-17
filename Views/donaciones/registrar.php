<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Donación</title>
</head>
<body>
    <h1>Registrar Donación</h1>
    <form action="<?php echo base_url(); ?>donaciones/registrar" method="POST">
        <label for="nombre">Nombre del Donante:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="correo">Correo del Donante:</label>
        <input type="email" id="correo" name="correo" required>
        <br>
        <label for="monto">Monto:</label>
        <input type="number" id="monto" name="monto" step="0.01" required>
        <br>
        <input type="submit" value="Registrar Donación">
    </form>
</body>
</html>
