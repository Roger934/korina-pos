<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Korina POS - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-image: url('/korina-pos/front/imagenes/bg-cafe.jpg'); /* tú colocas la imagen */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="bg-white/85 backdrop-blur-md shadow-2xl rounded-3xl p-10 w-[480px]">

        <!-- LOGO -->
        <div class="flex justify-center mb-8">
            <img src="/korina-pos/front/imagenes/logo.png" class="h-36 drop-shadow-lg">
        </div>

        <!-- TÍTULO -->
        <h2 class="text-3xl font-bold text-center mb-8 text-[#4b2e2b]">
            Iniciar Sesión
        </h2>

        <!-- FORMULARIO -->
        <form action="/korina-pos/back/auth.php" method="POST" class="space-y-6">

            <div>
                <label class="font-semibold text-[#4b2e2b] text-lg">Usuario</label>
                <input type="text" 
                       name="usuario" 
                       required
                       class="w-full px-4 py-3 rounded-xl bg-[#7a59524f] text-white placeholder-white focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </div>

            <div>
                <label class="font-semibold text-[#4b2e2b] text-lg">Contraseña</label>
                <input type="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-3 rounded-xl bg-[#7a59524f] text-white placeholder-white focus:ring-2 focus:ring-[#4b2e2b] outline-none">
            </div>

            <!-- BOTÓN LOGIN -->
            <button type="submit"
                class="w-full bg-[#4b2e2b] text-white font-bold py-3 rounded-2xl mt-4 hover:bg-[#6b423f] text-lg transition">
                Entrar
            </button>

        </form>

    </div>

</body>
</html>
