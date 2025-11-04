<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

$message = "";
$cursos = [];
$curso_editar = null;

// Conexión inicial para cargar la lista
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("❌ Falló la conexión: " . mysqli_connect_error());
}

// Cargar lista de cursos
$result = mysqli_query($conn, "SELECT id_curso, name_curso, id_docente FROM cursos");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cursos[] = $row;
    }
}

// Si se selecciona un curso para editar
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    $id_seleccionado = (int)$_GET['id'];
    $stmt = mysqli_prepare($conn, "SELECT id_curso, name_curso, id_docente FROM cursos WHERE id_curso = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_seleccionado);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $curso_editar = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
}

// Si se envía el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_original'])) {
    $id_original = (int)$_POST['id_original'];
    $id_curso = (int)$_POST['id_curso'];
    $name_curso = trim($_POST['name_curso']);
    $id_docente = (int)$_POST['id_docente'];

    if (empty($name_curso)) {
        $message = "<p style='color: red;'>❌ El nombre del curso no puede estar vacío.</p>";
    } else {
        // Verificar que no haya conflicto de ID (si se cambió)
        $check = mysqli_prepare($conn, "SELECT id_curso FROM cursos WHERE id_curso = ? AND id_curso != ?");
        mysqli_stmt_bind_param($check, "ii", $id_curso, $id_original);
        mysqli_stmt_execute($check);
        $conflicto = mysqli_stmt_get_result($check);
        if (mysqli_num_rows($conflicto) > 0) {
            $message = "<p style='color: red;'>❌ El ID del curso ya existe. Elige otro.</p>";
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE cursos SET id_curso = ?, name_curso = ?, id_docente = ? WHERE id_curso = ?");
            mysqli_stmt_bind_param($stmt, "isii", $id_curso, $name_curso, $id_docente, $id_original);
            if (mysqli_stmt_execute($stmt)) {
                $message = "<p style='color: green;'>✅ Curso actualizado exitosamente.</p>";
                // Recargar los cursos
                $result = mysqli_query($conn, "SELECT id_curso, name_curso, id_docente FROM cursos");
                $cursos = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $cursos[] = $row;
                }
                // Cargar el curso actualizado
                $curso_editar = ['id_curso' => $id_curso, 'name_curso' => $name_curso, 'id_docente' => $id_docente];
            } else {
                $message = "<p style='color: red;'>❌ Error al actualizar: " . mysqli_error($conn) . "</p>";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
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
            color: #1b5e20;
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4caf50;
            padding-bottom: 8px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #4caf50;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
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
            background-color: #1b5e20;
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
            background-color: #0d3b14;
        }
        .message {
            margin: 20px 0;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
        }
        .warning {
            background-color: #fff8e1;
            color: #5d4037;
            border-left: 4px solid #ff8f00;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 14px;
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

    <h2>✏️ Editar Curso</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <!-- Seleccionar curso -->
    <?php if (!$curso_editar): ?>
        <p>Selecciona un curso para editar:</p>
        <form method="GET" action="">
            <div class="form-group">
                <select name="id" required>
                    <option value="">-- Elige un curso --</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= htmlspecialchars($c['id_curso']) ?>">
                            [ID <?= $c['id_curso'] ?>] <?= htmlspecialchars($c['name_curso']) ?> — Docente: <?= htmlspecialchars($c['id_docente']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Cargar para editar</button>
        </form>

        <?php if (empty($cursos)): ?>
            <p style="text-align: center; margin-top: 20px; color: #666;">📭 No hay cursos disponibles.</p>
        <?php endif; ?>

    <?php else: ?>
        <!-- Formulario de edición -->
        <div class="warning">
            ⚠️ <strong>Advertencia:</strong> Cambiar el ID del curso puede afectar otras partes del sistema si se usa como referencia.
        </div>

        <form method="POST" action="">
            <input type="hidden" name="id_original" value="<?= htmlspecialchars($curso_editar['id_curso']) ?>">

            <div class="form-group">
                <label for="id_curso">Nuevo ID del Curso:</label>
                <input type="number" id="id_curso" name="id_curso" value="<?= htmlspecialchars($curso_editar['id_curso']) ?>" min="1" required>
            </div>

            <div class="form-group">
                <label for="name_curso">Nombre del Curso:</label>
                <input type="text" id="name_curso" name="name_curso" value="<?= htmlspecialchars($curso_editar['name_curso']) ?>" required>
            </div>

            <div class="form-group">
                <label for="id_docente">ID del Docente:</label>
                <input type="number" id="id_docente" name="id_docente" value="<?= htmlspecialchars($curso_editar['id_docente']) ?>" min="1" required>
            </div>

            <button type="submit">Actualizar Curso</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            <a href="?">← Elegir otro curso</a>
        </p>
    <?php endif; ?>

    <a href="../dashboard.html">← Volver al menú principal</a>

</body>
</html>