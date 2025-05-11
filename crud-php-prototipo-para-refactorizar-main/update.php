<?php
include 'config.php';

$id = $_GET['id'];
$result = $connection->query("SELECT * FROM students WHERE id = $id");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "UPDATE students SET fullname='$name', email='$email', age=$age WHERE id=$id";

    if ($connection->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar: " . $connection->error;
    }
}
?>
<link rel='stylesheet' href='style.css'>
<h2>Editar Estudiante</h2>
<!--<form method="post">--> <!--NO SE HACE si no especifo action, usa la url actual con el id por GET-->

<!-- En el action se agrega el id de la fila que estoy editando--> 
<form class='form'action="update.php?id=<?= $row['id'] ?>" method="post">
    Nombre completo: <input type="text" name="fullname" value="<?= $row['fullname'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $row['email'] ?>" required><br>
    Edad: <input type="number" name="age" value="<?= $row['age'] ?>" required><br>
    <input id="boton" type="submit" value="Actualizar">
</form>
