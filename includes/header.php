<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Registro</title>
    <link rel="stylesheet" type="text/css" href="/registro/styles.css">
</head>
<body>
<div class="header">
    <img src="/registro/logo.png" alt="Logo" class="logo">
    <h1>Sistema de Registro DPTO V</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="logout">
            <a href="/registro/logout.php" class="button">Logout</a>
        </div>
    <?php endif; ?>
</div>
