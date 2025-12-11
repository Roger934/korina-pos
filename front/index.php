<!DOCTYPE html>
<html>
<head>
    <title>Login Korina POS</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>

    <form action="../back/auth.php" method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <br><br>
        <input type="password" name="password" placeholder="Contraseña" required>
        <br><br>
        <button type="submit">Entrar</button>
    </form>

</body>
</html>
