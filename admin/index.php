<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
require '../config/db.php';
require '../includes/header.php';

// Obtener el id del administrador logueado
$admin_id = $_SESSION['user_id'];

$total_registrados = 0;
$total_femenino = 0;
$total_masculino = 0;

if ($_SESSION['nivel'] == 'nacional') {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM registros");
    $total_registrados = $stmt->fetch()['total'];
    
    $stmt_femenino = $pdo->query("SELECT COUNT(*) AS total FROM registros WHERE sexo = 'Femenino'");
    $total_femenino = $stmt_femenino->fetch()['total'];
    
    $stmt_masculino = $pdo->query("SELECT COUNT(*) AS total FROM registros WHERE sexo = 'Masculino'");
    $total_masculino = $stmt_masculino->fetch()['total'];
} else {
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM registros WHERE admin_id = ?");
    $stmt->execute([$admin_id]);
    $total_registrados = $stmt->fetch()['total'];
    
    $stmt_femenino = $pdo->prepare("SELECT COUNT(*) AS total FROM registros WHERE admin_id = ? AND sexo = 'Femenino'");
    $stmt_femenino->execute([$admin_id]);
    $total_femenino = $stmt_femenino->fetch()['total'];
    
    $stmt_masculino = $pdo->prepare("SELECT COUNT(*) AS total FROM registros WHERE admin_id = ? AND sexo = 'Masculino'");
    $stmt_masculino->execute([$admin_id]);
    $total_masculino = $stmt_masculino->fetch()['total'];
}
?>

<div class="container">
    <h1>Panel de Administración</h1>
    <div class="stats">
        <h2>Total Registrados: <?= $total_registrados ?></h2>
        <div class="stats-detail">
            <p><span class="label">Femenino:</span> <?= $total_femenino ?></p>
            <p><span class="label">Masculino:</span> <?= $total_masculino ?></p>
        </div>
    </div>
    <div class="button-container">
        <a href="registro.php" class="button">Registrar Persona</a>
        <a href="ver.php" class="button">Ver Registros</a>
    </div>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

.stats {
    text-align: center;
    margin-bottom: 20px;
}

.stats-detail {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.stats-detail p {
    font-size: 18px;
    margin: 0;
}

.stats-detail .label {
    font-weight: bold;
}

.button-container {
    text-align: center;
    margin-top: 20px;
}

.button {
    display: inline-block;
    padding: 10px 20px;
    margin: 5px;
    background-color: #848684;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #0056b3;
}
</style>
     