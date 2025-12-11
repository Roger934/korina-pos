<?php
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../index.php");
    exit;
}
?>
<h1>Bienvenido Admin, <?php echo $_SESSION["nombre"]; ?></h1>
<a href="../../back/logout.php">Cerrar sesión</a>
