<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "academia";

// Conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("❌ Falló la conexión: " . mysqli_connect_error());
}

$sql = "SELECT id_curso, name_curso, id_docente FROM cursos";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Cursos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f0f8f0;
            color: #2e4a2e;
        }
        h1 {
            color: #1b5e20;
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4caf50;
            padding-bottom: 8px;
        }
        .header-note {
            background-color: #e8f5e9;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #4caf50;
            font-style: italic;
        }
        .course-list {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .course-item {
            padding: 14px 20px;
            border-bottom: 1px solid #e0f2e0;
            display: flex;
            justify-content: space-between;
        }
        .course-item:last-child {
            border-bottom: none;
        }
        .course-id {
            font-weight: bold;
            color: #1b5e20;
            min-width: 80px;
        }
        .course-name {
            flex: 1;
            padding: 0 15px;
        }
        .course-teacher {
            color: #2e7d32;
            font-weight: 600;
        }
        .no-results {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
            background-color: #e8f5e9;
            border-radius: 8px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #2e7d32;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>📚 Listado de Cursos</h1>

    <div class="header-note">
        <i>Proyecto para leer los registros de la tabla 'cursos' de la base de datos 'academia'.</i>
    </div>

    <div class="course-list">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="course-item">
                    <span class="course-id">ID: <?= htmlspecialchars($row["id_curso"]) ?></span>
                    <span class="course-name"><?= htmlspecialchars($row["name_curso"]) ?></span>
                    <span class="course-teacher">Docente: <?= htmlspecialchars($row["id_docente"]) ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                📭 No se encontraron cursos registrados.
            </div>
        <?php endif; ?>
    </div>

    <a href="../dashboard.html">← Volver al menú principal</a>

    <?php mysqli_close($conn); ?>
</body>
</html>