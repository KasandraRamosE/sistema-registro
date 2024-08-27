<?php
session_start();
require 'config/db.php';

date_default_timezone_set('America/La_Paz'); 
const MAX_ATTEMPTS = 5; 
const LOCKOUT_TIME = 15 * 60; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = strtolower(trim($_POST['email'])); 
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE email = ?");
    $stmt->execute([$email]);
    $attempt = $stmt->fetch();

    if ($attempt && $attempt['locked_until'] && strtotime($attempt['locked_until']) > time()) {
        $error = 'Tu cuenta está bloqueada. Intenta de nuevo más tarde.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM administradores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            if ($attempt) {
                $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
                $stmt->execute([$email]);
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nivel'] = $user['nivel'];
            $_SESSION['departamento'] = $user['departamento'];
            
            if ($_SESSION['nivel'] == 'nacional') {
                header('Location: admin/ver.php');
            } else {
                header('Location: admin/index.php');
            }
            exit;
        } else {
            if ($attempt) {
                $attempts = $attempt['attempts'] + 1;

                if ($attempts >= MAX_ATTEMPTS) {
                    $locked_until = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
                    $stmt = $pdo->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW(), locked_until = ? WHERE email = ?");
                    $stmt->execute([$attempts, $locked_until, $email]);
                    $error = 'Demasiados intentos fallidos. Tu cuenta está bloqueada por 15 minutos.';
                } else {
                    $stmt = $pdo->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW() WHERE email = ?");
                    $stmt->execute([$attempts, $email]);
                    $error = 'Email o contraseña incorrecta.';
                }
            } else {
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
