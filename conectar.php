<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

// Create connection
echo "Proyecto para leer los registros de la tabla 'cursos' de la bdd 'academia'". "<br>";
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id_curso, name_curso, name_docente FROM cursos";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "id: " . $row["id_curso"]. " - curso: " . $row["name_curso"]. " - docente: " . $row["name_docente"]. "<br>";
  }
} else {
  echo "0 results";
}

mysqli_close($conn);
?>