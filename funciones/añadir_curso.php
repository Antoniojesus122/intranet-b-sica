<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

// Mensaje de resultado
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Falló la conexión: " . mysqli_connect_error());
    }

    // Obtener datos del formulario
    $id_curso = trim($_POST['id_curso']);
    $name_curso = trim($_POST['name_curso']);
    $id_docente = trim($_POST['id_docente']);

    // Validación básica
    if (empty($id_curso) || empty($name_curso) || empty($id_docente)) {
        $message = "<p style='color: red;'>Todos los campos son obligatorios.</p>";
    } else {
        // Preparar la consulta
        $sql = "INSERT INTO cursos (id_curso, name_curso, id_docente) 
                VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iss", $id_curso, $name_curso, $id_docente);
            if (mysqli_stmt_execute($stmt)) {
                $message = "<p style='color: green;'>✅ Curso añadido exitosamente.</p>";
            } else {
                $message = "<p style='color: red;'>❌ Error al insertar: " . mysqli_error($conn) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            $message = "<p style='color: red;'>❌ Error en la preparación de la consulta.</p>";
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Nuevo Curso</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f0f8f0;
            color: #2e4a2e;
        }
        h2 {
            color: #1b5e20;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #4caf50;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #2e7d32;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #1b5e20;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2e7d32;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Añadir Nuevo Curso</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="id_curso">ID del Curso:</label>
            <input type="number" id="id_curso" name="id_curso" min="1" required>
        </div>

        <div class="form-group">
            <label for="name_curso">Nombre del Curso:</label>
            <input type="text" id="name_curso" name="name_curso" required>
        </div>

        <div class="form-group">
            <label for="id_docente">ID del Docente:</label>
            <input type="number" id="id_docente" name="id_docente" min="1" required>
        </div>

        <button type="submit">Añadir Curso</button>
    </form>

    <a href="../dashboard.html">← Volver al menú principal</a>

</body>
</html>