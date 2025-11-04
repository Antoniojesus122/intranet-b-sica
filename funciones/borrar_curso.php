<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

$message = "";
$cursos = [];

// Conectar y cargar cursos
$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn) {
    $result = mysqli_query($conn, "SELECT id_curso, name_curso, id_docente FROM cursos");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cursos[] = $row;
        }
    }
    mysqli_close($conn);
}

// Procesar eliminación si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_curso'])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        $message = "<p style='color: red;'>❌ Error de conexión.</p>";
    } else {
        $id_curso = (int)$_POST['id_curso'];
        $stmt = mysqli_prepare($conn, "DELETE FROM cursos WHERE id_curso = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id_curso);
            if (mysqli_stmt_execute($stmt)) {
                $message = "<p style='color: green;'>✅ Curso eliminado exitosamente.</p>";
                // Recargar la lista sin el curso eliminado
                $result = mysqli_query($conn, "SELECT id_curso, name_curso, id_docente FROM cursos");
                $cursos = [];
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $cursos[] = $row;
                    }
                }
            } else {
                $message = "<p style='color: red;'>❌ No se pudo eliminar el curso. ¿Está siendo usado en otra parte?</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            $message = "<p style='color: red;'>❌ Error en la consulta de eliminación.</p>";
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Curso</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f0f8f0;
            color: #2e4a2e;
        }
        h2 {
            color: #c62828; /* rojo oscuro suave para "eliminar" */
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #ff7043;
            padding-bottom: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #4caf50;
            border-radius: 4px;
            background-color: white;
            font-size: 16px;
        }
        button {
            background-color: #c62828;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            font-weight: bold;
        }
        button:hover {
            background-color: #b71c1c;
        }
        .message {
            margin: 20px 0;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
        }
        .course-list {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
        }
        .course-item {
            padding: 8px 0;
            border-bottom: 1px dashed #a5d6a7;
        }
        .course-item:last-child {
            border-bottom: none;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #2e7d32;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>🗑️ Eliminar un Curso</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <?php if (empty($cursos)): ?>
        <p style="text-align: center; color: #666;">📭 No hay cursos disponibles para eliminar.</p>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_curso">Selecciona el curso a eliminar:</label>
                <select name="id_curso" id="id_curso" required>
                    <option value="">-- Elige un curso --</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?= htmlspecialchars($curso['id_curso']) ?>">
                            [ID <?= $curso['id_curso'] ?>] <?= htmlspecialchars($curso['name_curso']) ?> — Docente: <?= htmlspecialchars($curso['id_docente']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Eliminar Curso</button>
        </form>

        <div class="course-list">
            <p><strong>Lista actual de cursos:</strong></p>
            <?php foreach ($cursos as $curso): ?>
                <div class="course-item">
                    <strong>ID:</strong> <?= htmlspecialchars($curso['id_curso']) ?> |
                    <strong>Curso:</strong> <?= htmlspecialchars($curso['name_curso']) ?> |
                    <strong>Docente:</strong> <?= htmlspecialchars($curso['id_docente']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="../dashboard.html">← Volver al menú principal</a>

</body>
</html>