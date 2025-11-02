<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Verificación de Acceso</title>
<style>
    .container {
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
        text-align: center;
    }
    .message {
        text-align: center;
        margin: 20px 0;
        padding: 15px;
        border-radius: 8px;
        font-size: 16px;
    }
    .success {
        background-color: #dff0d8;
        color: #3c763d;
        border: 1px solid #d6e9c6;
    }
    .error {
        background-color: #f2dede;
        color: #a94442;
        border: 1px solid #ebccd1;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        background-color: #337ab7;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    .btn:hover {
        background-color: #286090;
    }
    .btn-secondary {
        background-color: #5bc0de;
    }
    .btn-secondary:hover {
        background-color: #31b0d5;
    }
    .countdown {
        font-size: 14px;
        color: #666;
        margin-top: 10px;
    }
</style>
</head>  
<body>
<div class="container">
    <h2>Verificación de Acceso</h2>

    <?php
    $login = $_POST['name_log'] ?? '';
    $logpswd = $_POST['pswd_log'] ?? '';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jefatura";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prevenir inyección SQL usando sentencias preparadas
    $sql = "SELECT name_editor, pswd_editor FROM editores WHERE name_editor = ? AND pswd_editor = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $login, $logpswd);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $autorzdo = 3;
        echo '<div class="message success">Usuario autenticado correctamente. Redirigiendo en 4 segundos...</div>';
    } else {
        $autorzdo = 0;
        echo '<div class="message error">Usuario no autorizado. Redirigiendo en 4 segundos...</div>';
    }

    $prosigue = ["login.html", "conectar13.php", "conectar14.php", "dashboard.html"];
    mysqli_close($conn);
    $p = ($autorzdo == 0) ? 0 : 3;
    $destino = $prosigue[$p];
    ?>

    <!-- Botón para redirigir inmediatamente -->
    <a href="<?php echo htmlspecialchars($destino); ?>" class="btn btn-secondary">Ir ahora</a>

    <script type="text/javascript">
        setTimeout(function() {
            window.location.href = '<?php echo addslashes($destino); ?>';
        }, 4000);
    </script>
</div>
</body>
</html>