<?php
include 'db.php'; // Conexión a la base de datos

// Crear un cuestionario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_quiz'])) {
    $quiz_name = $_POST['quiz_name'];
    $questions = $_POST['questions'];

    if (count($questions) === 5) {
        $conn->query("INSERT INTO cuestionarios (nombre) VALUES ('$quiz_name')");
        $quiz_id = $conn->insert_id;

        foreach ($questions as $question) {
            $text = $question['text'];
            $correct_answer = $question['correct_answer'];
            $conn->query("INSERT INTO preguntas (cuestionario_id, pregunta, respuesta_correcta) 
                          VALUES ($quiz_id, '$text', '$correct_answer')");
        }
    } else {
        echo "<script>alert('Un cuestionario debe tener exactamente 5 preguntas.');</script>";
    }
}

// Resolver cuestionario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solve_quiz'])) {
    $quiz_id = $_POST['quiz_id'];
    $answers = $_POST['answers'];
    $score = 0;

    foreach ($answers as $question_id => $answer) {
        $result = $conn->query("SELECT respuesta_correcta FROM preguntas WHERE id = $question_id");
        $row = $result->fetch_assoc();
        if ($row['respuesta_correcta'] === $answer) {
            $score++;
        }
    }

    // Guardamos el puntaje en una variable para mostrarlo en el modal
    $message = "Puntaje: $score/5";
}

// Editar cuestionario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_quiz'])) {
    $quiz_id = $_POST['quiz_id'];
    $quiz_name = $_POST['quiz_name'];
    $questions = $_POST['questions'];

    $conn->query("UPDATE cuestionarios SET nombre = '$quiz_name' WHERE id = $quiz_id");
    $conn->query("DELETE FROM preguntas WHERE cuestionario_id = $quiz_id");

    foreach ($questions as $question) {
        $text = $question['text'];
        $correct_answer = $question['correct_answer'];
        $conn->query("INSERT INTO preguntas (cuestionario_id, pregunta, respuesta_correcta) 
                      VALUES ($quiz_id, '$text', '$correct_answer')");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        // Lógica para eliminar el cuestionario de la base de datos
        $stmt = $conn->prepare("DELETE FROM cuestionarios WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    }
}

// Eliminar cuestionario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz'])) {
    $quiz_id = $_POST['quiz_id'];

    // Eliminar las preguntas relacionadas primero
    $conn->query("DELETE FROM preguntas WHERE cuestionario_id = $quiz_id");

    // Eliminar el cuestionario
    $stmt = $conn->prepare("DELETE FROM cuestionarios WHERE id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();

    // Redirigir a la misma página para evitar reenvíos de formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cuestionarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            /* Fondo más suave */
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: #ed8414;
            font-weight: bold;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .accordion-button {
            background-color: #ed8414;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .accordion-button:hover {
            background-color: #e3780e;
        }

        .accordion-item {
            border: 1px solid #ed8414;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-warning {
            background-color: #ed8414;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #e3780e;
        }

        .btn-success,
        .btn-primary,
        .btn-danger {
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-primary:hover {
            background-color: #0069d9;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .modal-header {
            background-color: #ed8414;
            color: white;
            border-radius: 5px;
        }

        .modal-body {
            background-color: #fafafa;
        }

        .modal-footer {
            background-color: #f4f4f9;
        }

        .form-control,
        .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #ed8414;
            box-shadow: 0 0 5px rgba(237, 132, 20, 0.6);
        }

        .modal-dialog {
            max-width: 800px;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-body {
            background-color: #f9f9f9;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center text-warning">Gestión de Cuestionarios</h1>
        <a class="btn btn-warning mb-4" href="index.php">
            <i class="fas fa-arrow-left"></i> Volver al Biblioteca
        </a>
        <!-- Botón para abrir el modal de creación -->
        <button class="btn btn-warning mb-4" data-bs-toggle="modal" data-bs-target="#createQuizModal">Crear Cuestionario</button>

        <!-- Lista de cuestionarios -->
        <div class="accordion" id="quizAccordion">
            <?php
            $result = $conn->query("SELECT * FROM cuestionarios");
            while ($quiz = $result->fetch_assoc()) {
                $quiz_id = $quiz['id'];
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?= $quiz_id ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse<?= $quiz_id ?>" aria-expanded="false"
                            aria-controls="collapse<?= $quiz_id ?>">
                            <?= $quiz['nombre'] ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $quiz_id ?>" class="accordion-collapse collapse"
                        aria-labelledby="heading<?= $quiz_id ?>" data-bs-parent="#quizAccordion">
                        <div class="accordion-body">
                            <!-- Botones de acción -->
                            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#solveQuizModal<?= $quiz_id ?>">Resolver</button>
                            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#editQuizModal<?= $quiz_id ?>">Editar</button>
                            <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#deleteQuizModal<?= $quiz_id ?>">Eliminar</button>


                            <!-- Resolver Cuestionario Modal -->
                            <div class="modal fade" id="solveQuizModal<?= $quiz_id ?>" tabindex="-1" aria-labelledby="solveQuizModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="solveQuizModalLabel">Resolver Cuestionario: <?= $quiz['nombre'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                                <?php
                                                $questions = $conn->query("SELECT * FROM preguntas WHERE cuestionario_id = $quiz_id");
                                                while ($question = $questions->fetch_assoc()) {
                                                ?>
                                                    <div class="mb-3">
                                                        <label class="form-label"><?= $question['pregunta'] ?></label>
                                                        <select name="answers[<?= $question['id'] ?>]" class="form-select" required>
                                                            <option value="Verdadero">Verdadero</option>
                                                            <option value="Falso">Falso</option>
                                                        </select>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="solve_quiz" class="btn btn-primary">Enviar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Editar Cuestionario Modal -->
                            <div class="modal fade" id="editQuizModal<?= $quiz_id ?>" tabindex="-1" aria-labelledby="editQuizModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editQuizModalLabel">Editar Cuestionario: <?= $quiz['nombre'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                                <div class="mb-3">
                                                    <input type="text" name="quiz_name" class="form-control" value="<?= $quiz['nombre'] ?>" required>
                                                </div>
                                                <?php
                                                $questions = $conn->query("SELECT * FROM preguntas WHERE cuestionario_id = $quiz_id");
                                                while ($question = $questions->fetch_assoc()) {
                                                ?>
                                                    <div class="mb-3">
                                                        <input type="text" name="questions[<?= $question['id'] ?>][text]" class="form-control" value="<?= $question['pregunta'] ?>" required>
                                                        <select name="questions[<?= $question['id'] ?>][correct_answer]" class="form-select" required>
                                                            <option value="Verdadero" <?= $question['respuesta_correcta'] === 'Verdadero' ? 'selected' : '' ?>>Verdadero</option>
                                                            <option value="Falso" <?= $question['respuesta_correcta'] === 'Falso' ? 'selected' : '' ?>>Falso</option>
                                                        </select>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="edit_quiz" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <!-- Modal Crear Cuestionario -->
    <div class="modal fade" id="createQuizModal" tabindex="-1" aria-labelledby="createQuizModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createQuizModalLabel">Crear Cuestionario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" name="quiz_name" class="form-control" placeholder="Nombre del Cuestionario" required>
                        </div>
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <div class="mb-3">
                                <input type="text" name="questions[<?= $i ?>][text]" class="form-control" placeholder="Pregunta <?= $i ?>" required>
                                <select name="questions[<?= $i ?>][correct_answer]" class="form-select" required>
                                    <option value="Verdadero">Verdadero</option>
                                    <option value="Falso">Falso</option>
                                </select>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="create_quiz" class="btn btn-success">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Cuestionario -->
    <div class="modal fade" id="deleteQuizModal<?= $quiz_id ?>" tabindex="-1" aria-labelledby="deleteQuizModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteQuizModalLabel">Eliminar Cuestionario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el cuestionario?</p>
                        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="delete_quiz" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Puntaje -->
    <?php if (isset($message)) { ?>
        <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scoreModalLabel">Resultado del Cuestionario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?= $message ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
<?php if (isset($message)) { ?>
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('scoreModal'));
        myModal.show();
    </script>
<?php } ?>

</html>