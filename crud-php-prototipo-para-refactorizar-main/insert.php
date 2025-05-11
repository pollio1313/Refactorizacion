<?php
include 'config.php';

/**
 * $_SERVER con esta "super-global" detecto con qué método
 * consultan al servidor.
 * https://www.php.net/manual/es/reserved.variables.request.php
 * https://www.php.net/manual/es/language.variables.superglobals.php 
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "INSERT INTO students (fullname, email, age)
            VALUES ('$name', '$email', $age)";

    if ($connection->query($sql) === TRUE) {
        /**
         * la función header redirige a la página principal index.php
         * de lo contrario recargaría la misma página.
         */
        header("Location: index.php"); 
        exit;
    } else {
        echo "Error al insertar: " . $connection->error;
    }
}
?>
<link rel='stylesheet' href='style.css'>
<h2>Agregar Estudiante</h2>
<form class='form' action="insert.php" method="post">
    Nombre completo: <input type="text" name="fullname" required><br>
    Email: <input type="email" name="email" required><br>
    Edad: <input type="number" name="age" required><br>
    <input id="boton" type="submit" value="Guardar">
</form>
