<?php
include 'db.php'; // Asegúrate de que la conexión a la base de datos esté correctamente incluida

// Verifica si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger el nombre del cuestionario y las preguntas/respuestas
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);

    $pregunta_1 = mysqli_real_escape_string($conn, $_POST['pregunta_1']);
    $respuesta_1 = mysqli_real_escape_string($conn, $_POST['respuesta_1']);
    $pregunta_2 = mysqli_real_escape_string($conn, $_POST['pregunta_2']);
    $respuesta_2 = mysqli_real_escape_string($conn, $_POST['respuesta_2']);
    $pregunta_3 = mysqli_real_escape_string($conn, $_POST['pregunta_3']);
    $respuesta_3 = mysqli_real_escape_string($conn, $_POST['respuesta_3']);
    $pregunta_4 = mysqli_real_escape_string($conn, $_POST['pregunta_4']);
    $respuesta_4 = mysqli_real_escape_string($conn, $_POST['respuesta_4']);
    $pregunta_5 = mysqli_real_escape_string($conn, $_POST['pregunta_5']);
    $respuesta_5 = mysqli_real_escape_string($conn, $_POST['respuesta_5']);

    // Inserta el cuestionario en la base de datos
    $sql = "INSERT INTO cuestionarios (nombre, pregunta_1, respuesta_1, pregunta_2, respuesta_2, pregunta_3, respuesta_3, pregunta_4, respuesta_4, pregunta_5, respuesta_5) 
            VALUES ('$nombre', '$pregunta_1', '$respuesta_1', '$pregunta_2', '$respuesta_2', '$pregunta_3', '$respuesta_3', '$pregunta_4', '$respuesta_4', '$pregunta_5', '$respuesta_5')";
    
    if (mysqli_query($conn, $sql)) {
        // Redirige con un mensaje de éxito
        echo "<script>
            alert('¡Cuestionario guardado exitosamente!');
            window.location.href = 'cuestionarios.php';
        </script>";
    } else {
        // Manejo de errores
        echo "<script>
            alert('Error al guardar el cuestionario: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
    }
}
?>
