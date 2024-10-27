<?php
include 'db.php';

$miembro_id = $_GET['id'];
$club_id = $_GET['club_id'];

$sql = "DELETE FROM miembros WHERE id = $miembro_id";
if ($conn->query($sql) === TRUE) {
    header("Location: miembros.php?club_id=$club_id");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
