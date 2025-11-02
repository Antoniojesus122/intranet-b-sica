<html>
    <body>
        <div style="text-align: center;">
            <h2>Bienvenida a la Intranet del Centro</h2>
        </div>

        <div>
            <?php
                $login = $_POST['name_log']; 
                $logpswd = $_POST['pswd_log'];
            ?>
        </div>
            Sea usted bienvenido, <?php echo $login; ?><br>
            Su contraseña es: <?php echo $logpswd; ?>
        
        <?php $sql = "SELECT name_editor FROM editores WHERE name_editor == $login"; ?>
    </body>
</html>