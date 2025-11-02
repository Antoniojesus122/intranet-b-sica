<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE cursos(
id_curso INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name_curso VARCHAR(30) NOT NULL,
name_docente VARCHAR(30) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
  echo "Table cursos created successfully";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>