<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require '../config/db.php';
require '../includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: ver.php');
    exit;
}

$id = $_GET['id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM registros WHERE id = ?");
    $stmt->execute([$id]);
    $registro = $stmt->fetch();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if (!$registro) {
    echo "Registro no encontrado.";
    exit;
}
?>

<div class="container">
    <h1>Información Completa de la Persona</h1>
    <div class="info-table">
        <table>
            <tr>
                <th>Apellido Paterno</th>
                <td><?= htmlspecialchars($registro['apellido_paterno']) ?></td>
            </tr>
            <tr>
                <th>Apellido Materno</th>
                <td><?= htmlspecialchars($registro['apellido_materno']) ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?= htmlspecialchars($registro['nombre']) ?></td>
            </tr>
            <tr>
                <th>CI</th>
                <td><?= htmlspecialchars($registro['ci']) ?></td>
            </tr>
            <tr>
                <th>Instituto</th>
                <td><?= htmlspecialchars($registro['instituto']) ?></td>
            </tr>
            <tr>
                <th>Código Amitai</th>
                <td><?= htmlspecialchars($registro['cod_amitai']) ?></td>
            </tr>
            <tr>
                <th>Nro de boleta de pago AMITAI</th>
                <td><?= htmlspecialchars($registro['pago_amitai']) ?></td>
            </tr>
            <tr>
                <th>Monto AMITAI (Bs)</th>
                <td><?= htmlspecialchars($registro['monto_amitai']) ?></td>
            </tr>
            <tr>
                <th>Fecha de Pago AMITAI</th>
                <td><?= htmlspecialchars($registro['fecha_amitai']) ?></td>
            </tr>
            <tr>
                <th>Nro de boleta del pago para el prospecto</th>
                <td><?= htmlspecialchars($registro['pago_prospecto']) ?></td>
            </tr>
            <tr>
                <th>Monto Prospecto (Bs)</th>
                <td><?= htmlspecialchars($registro['monto_prospecto']) ?></td>
            </tr>
            <tr>
                <th>Fecha de Pago Prospecto</th>
                <td><?= htmlspecialchars($registro['fecha_prospecto']) ?></td>
            </tr>
            ------
                <th>Nro de boleta del pago para el examen médico</th>
                <td><?= htmlspecialchars($registro['pago_medico']) ?></td>
            </tr>
            <tr>
                <th>Monto Examen Médico (Bs)</th>
                <td><?= htmlspecialchars($registro['monto_medico']) ?></td>
            </tr>
            <tr>
                <th>Fecha de Pago para el examen médico</th>
                <td><?= htmlspecialchars($registro['fecha_medico']) ?></td>
            </tr>
            <tr>
                <th>Sexo</th>
                <td><?= htmlspecialchars($registro['sexo']) ?></td>
            </tr>
            <tr>
                <th>Examenes Complementarios</th>
                <td><?= htmlspecialchars($registro['complementarios']) ?></td>
            </tr>
            <tr>
                <th>Fecha de Nacimiento</th>
                <td><?= htmlspecialchars($registro['fecha_nacimiento']) ?></td>
            </tr>
            <tr>
                <th>Procedencia</th>
                <td><?= htmlspecialchars($registro['procedencia']) ?></td>
            </tr>
            <tr>
                <th>Residencia</th>
                <td><?= htmlspecialchars($registro['direccion']) ?></td>
            </tr>
            <tr>
                <th>Correo</th>
                <td><?= htmlspecialchars($registro['correo']) ?></td>
            </tr>
            <tr>
                <th>Teléfono Fijo</th>
                <td><?= htmlspecialchars($registro['telefono_fijo']) ?></td>
            </tr>
            <tr>
                <th>Número de Celular</th>
                <td><?= htmlspecialchars($registro['numero_celular']) ?></td>
            </tr>
            <tr>
                <th>Contacto del Tutor</th>
                <td><?= htmlspecialchars($registro['contacto_tutor']) ?></td>
            </tr>
            <tr>
                <th>Nombre del Tutor</th>
                <td><?= htmlspecialchars($registro['nombre_tutor']) ?></td>
            </tr>
            <tr>
                <th>Cuenta con Seguro</th>
                <td><?= htmlspecialchars($registro['cuenta_con_seguro']) ?></td>
            </tr>
            <tr>
                <th>De dónde es el Seguro</th>
                <td><?= htmlspecialchars($registro['de_donde_es_seguro']) ?></td>
            </tr>
            <tr>
                <th>Semana de Presentación</th>
                <td><?= htmlspecialchars($registro['semana_presentacion']) ?></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td><?= $registro['estado'] ? 'Activo' : 'Inactivo' ?></td>
            </tr>
        </table>
    </div>
    <a href="ver2.php" class="button">Volver a Registros</a>
</div>

<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    .info-table {
        width: 80%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    th {
        width: 40%;
    }

    .button {
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }
</style>
