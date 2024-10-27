<?php
include 'db.php';

$reunion_id = $_GET['id'];
$club_id = $_GET['club_id'];

$sql = "DELETE FROM reuniones WHERE id = $reunion_id";
if ($conn->query($sql) === TRUE) {
    header("Location: reuniones.php?club_id=$club_id");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
