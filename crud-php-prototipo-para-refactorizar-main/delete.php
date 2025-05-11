<?php
include 'config.php';

$id = $_GET['id'];

$sql = "DELETE FROM students WHERE id = $id";

if ($connection->query($sql) === TRUE) {
    header("Location: index.php");
    exit;
} else {
    echo "Error al borrar: " . $connection->error;
}
?>
