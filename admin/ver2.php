<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require '../config/db.php';
require '../includes/header.php';

if (isset($_POST['update_estado'])) {
    $id = $_POST['id'];
    $estado = isset($_POST['estado']) ? 1 : 0;
    $grupo = $_POST['grupo'];
    $pago_medico = $_POST['pago_medico'];
    $monto_medico = $_POST['monto_medico'];
    $fecha_medico = $_POST['fecha_medico'];

    $stmt = $pdo->prepare("UPDATE registros SET estado = ?, grupo = ?, pago_medico = ?, monto_medico = ?, fecha_medico = ? WHERE id = ?");
    $stmt->execute([$estado, $grupo, $pago_medico, $monto_medico, $fecha_medico, $id]);
}

$apellido_paterno_filtrado = '';
$registros = [];
$query = "SELECT id, apellido_paterno, apellido_materno, ci, instituto, semana_presentacion, cod_amitai, direccion, estado, grupo, pago_medico, monto_medico, fecha_medico FROM registros";
$params = [];
if (isset($_GET['apellido_paterno']) && !empty($_GET['apellido_paterno'])) {
    $apellido_paterno = $_GET['apellido_paterno'];
    $query .= " WHERE apellido_paterno LIKE ?";
    $params[] = "%$apellido_paterno%";
    $apellido_paterno_filtrado = $apellido_paterno;
}
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$registros = $stmt->fetchAll();
?>

<div class="container">
    <form method="get" class="filter-form">
        <div class="filter-group">
            <label for="apellido_paterno">Buscar por Apellido Paterno:</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= htmlspecialchars($apellido_paterno_filtrado) ?>">
            <button type="submit">Buscar</button>
        </div>
    </form>

    <div class="table-container">
        <table>
            <tr>
                <th>Nro</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>CI</th>
                <th>Instituto</th>
                <th>Semana de Presentacion</th>
                <th>Código Amitai</th>
                <th>Residencia</th>
                <th>Estado</th>
                <th>Grupo</th>
                <th>Nro Boleta</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($registros as $index => $registro): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($registro['apellido_paterno']) ?></td>
                    <td><?= htmlspecialchars($registro['apellido_materno']) ?></td>
                    <td><?= htmlspecialchars($registro['ci']) ?></td>
                    <td><?= htmlspecialchars($registro['instituto']) ?></td>
                    <td><?= htmlspecialchars($registro['semana_presentacion']) ?></td>
                    <td><?= htmlspecialchars($registro['cod_amitai']) ?></td>
                    <td><?= htmlspecialchars($registro['direccion']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $registro['id'] ?>">
                            <input type="checkbox" name="estado" <?= $registro['estado'] ? 'checked' : '' ?>>
                            <input type="hidden" name="update_estado" value="1">
                    </td>
                    <td>
                            <select name="grupo">
                                <option value="grupo 1" <?= $registro['grupo'] == 'grupo 1' ? 'selected' : '' ?>>Grupo 1</option>
                                <option value="grupo 2" <?= $registro['grupo'] == 'grupo 2' ? 'selected' : '' ?>>Grupo 2</option>
                                <option value="grupo 3" <?= $registro['grupo'] == 'grupo 3' ? 'selected' : '' ?>>Grupo 3</option>
                                <option value="grupo 4" <?= $registro['grupo'] == 'grupo 4' ? 'selected' : '' ?>>Grupo 4</option>
                            </select>
                    </td>
                    <td>
                        <input type="text" name="pago_medico" value="<?= htmlspecialchars($registro['pago_medico'] ?? '') ?>" placeholder="Nro Boleta">
                    </td>
                    <td>
                        <input type="text" name="monto_medico" value="<?= htmlspecialchars($registro['monto_medico'] ?? '') ?>" placeholder="monto_medico">
                    </td>
                    <td>
                        <input type="date" name="fecha_medico" value="<?= htmlspecialchars($registro['fecha_medico'] ?? '') ?>">
                    </td>
                    <td>
                        <button type="submit" class="button">Guardar</button>
                        </form>
                        <a href="verpersona.php?id=<?= $registro['id'] ?>" class="button">Ver Información</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <a href="index.php" class="button">Volver al Índice</a>
</div>

<style>
    .filter-form {
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        align-items: center;
    }

    .filter-group label {
        margin-right: 10px;
    }

    .filter-group input {
        padding: 5px;
    }

    .filter-group button {
        margin-left: 10px;
        padding: 5px 10px;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        table-layout: auto;
        border-collapse: collapse;
        margin: 20px 0;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    .button {
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .button:hover {
        background-color: #0056b3;
    }
</style>
