<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Falló la conexión. Mensaje: " . mysqli_connect_error());
}

$sql = "INSERT INTO cursos (id_curso, name_curso, name_docente)
VALUES (2, 'Telas', 'Adelaida');";
$sql .= "INSERT INTO cursos (id_curso, name_curso, name_docente)
VALUES (3, 'Confección', 'Maigualida');";
$sql .= "INSERT INTO cursos (id_curso, name_curso, name_docente)
VALUES (4, 'Costura', 'Adolfina')";

if (mysqli_multi_query($conn, $sql)) {
  echo "Nuevos registros creados exitosamente";
} else {
  echo "Hubo un error. Mensaje: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>