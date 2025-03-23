<?php
include 'db.php';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    if (isset($_POST['libro_id'])) {
        $libro_id = $_POST['libro_id'];
    } else {
        die("El campo libro_id no está presente.");
    }

    $nombre = $_POST['nombre'];
    $fecha_creacion = date('Y-m-d H:i:s'); // Fecha y hora actual

    // Verificar si el libro con el id dado existe en la tabla libros
    $sql_check_libro = "SELECT id FROM libros WHERE id = ?";
    if ($stmt_check = $conn->prepare($sql_check_libro)) {
        $stmt_check->bind_param("i", $libro_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        // Si el libro no existe, mostrar un error
        if ($result->num_rows == 0) {
            echo "El libro con el ID $libro_id no existe.";
            exit();
        }

        // Continuar con la inserción si el libro existe
        $sql = "INSERT INTO cuestionarios (nombre, libro_id, fecha_creacion) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sis", $nombre, $libro_id, $fecha_creacion);
            if ($stmt->execute()) {
                header("Location: cuestionarios.php?success=1");
                exit();
            } else {
                echo "Error al crear el cuestionario: " . $stmt->error;
            }
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    }
}

// Cerrar la conexión
$conn->close();
?>
