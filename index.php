<?php
// Archivo donde se guardarán los registros
$archivoUsuarios = 'usuarios.json';

// Función para registrar un usuario
function registrarUsuario($nombre, $email, $password) {
    global $archivoUsuarios;

    // Cargar usuarios actuales
    $usuarios = file_exists($archivoUsuarios) ? json_decode(file_get_contents($archivoUsuarios), true) : [];

    // Verificar si el email ya está registrado
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email) {
            return "Error: El correo ya está registrado.";
        }
    }

    // Guardar usuario nuevo
    $nuevoUsuario = [
        'nombre' => $nombre,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT) // Encriptar contraseña
    ];

    $usuarios[] = $nuevoUsuario;
    file_put_contents($archivoUsuarios, json_encode($usuarios, JSON_PRETTY_PRINT));

    return "Registro exitoso. ¡Bienvenido, $nombre!";
}

// Procesar el formulario
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        $mensaje = registrarUsuario($nombre, $email, $password);
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    
    <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>

    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="email">Correo:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
