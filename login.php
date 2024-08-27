<?php
session_start();
require 'config/db.php';

// Establecer la zona horaria
date_default_timezone_set('America/La_Paz'); // Ajusta la zona horaria según tu ubicación

// Configuración de seguridad
const MAX_ATTEMPTS = 5; // Número máximo de intentos permitidos
const LOCKOUT_TIME = 15 * 60; // 15 minutos de bloqueo en segundos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = strtolower(trim($_POST['email'])); // Normalizar correo electrónico
    $contrasena = $_POST['contrasena'];

    // Comprobar si el usuario está bloqueado
    $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE email = ?");
    $stmt->execute([$email]);
    $attempt = $stmt->fetch();

    if ($attempt && $attempt['locked_until'] && strtotime($attempt['locked_until']) > time()) {
        $error = 'Tu cuenta está bloqueada. Intenta de nuevo más tarde.';
    } else {
        // Consulta a la base de datos para buscar el usuario
        $stmt = $pdo->prepare("SELECT * FROM administradores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && password_verify($contrasena, $user['contrasena'])) {
            // Credenciales correctas; restablecer intentos fallidos
            if ($attempt) {
                $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
                $stmt->execute([$email]);
            }

            // Regenerar ID de sesión para evitar ataques de fijación de sesión
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nivel'] = $user['nivel'];
            $_SESSION['departamento'] = $user['departamento'];
            
            // Redirigir según el nivel del usuario
            if ($_SESSION['nivel'] == 'nacional') {
                header('Location: admin/ver.php');
            } else {
                header('Location: admin/index.php');
            }
            exit;
        } else {
            // Credenciales incorrectas; manejar intentos fallidos
            if ($attempt) {
                $attempts = $attempt['attempts'] + 1;

                if ($attempts >= MAX_ATTEMPTS) {
                    // Bloquear la cuenta
                    $locked_until = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
                    $stmt = $pdo->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW(), locked_until = ? WHERE email = ?");
                    $stmt->execute([$attempts, $locked_until, $email]);
                    $error = 'Demasiados intentos fallidos. Tu cuenta está bloqueada por 15 minutos.';
                } else {
                    // Actualizar los intentos fallidos
                    $stmt = $pdo->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW() WHERE email = ?");
                    $stmt->execute([$attempts, $email]);
                    $error = 'Email o contraseña incorrecta.';
                }
            } else {
                // Primer intento fallido para este usuario
                $stmt = $pdo->prepare("INSERT INTO login_attempts (email, attempts, last_attempt) VALUES (?, 1, NOW())");
                $stmt->execute([$email]);
                $error = 'Email o contraseña incorrecta.';
            }
        }
    }
}

require 'includes/header.php';
?>

<div class="container">
    <h1>Inicio de Sesión</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="email">Usuario:</label>
        <input type="text" name="email" id="email" required>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>
        <button type="submit">Ingresar</button>
    </form>
</div>
