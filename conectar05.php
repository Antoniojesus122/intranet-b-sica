<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "instituto";

// establecer la conexión con los valores de variables anteriores
$conn = mysqli_connect($servername, $username, $password, $dbname);
// verificar si la conexió no presentó errores
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM asignaturas";
$result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["id_asignatura"]. " - Name: " . $row["name_asignatura"]. " " .  "<br>";
    }
    } else {
    echo "0 results";
    }

mysqli_close($conn);
?>