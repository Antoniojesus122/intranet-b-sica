<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

// Establecer la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Verificar la conexión
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO cursos (id_curso, name_curso, name_docente)
VALUES (1, 'Patrones', 'Torcuato')";

if (mysqli_query($conn, $sql)) {
  echo "Nuevo registro insertado exitosamente";
} else {
  echo "Sucedió un error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
