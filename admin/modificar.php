<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['nivel'] != 'departamental') {
    header('Location: ../login.php');
    exit;
}

require '../config/db.php';

// Obtener el ID del registro a modificar
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM registros WHERE id = ?");
$stmt->execute([$id]);
$registro = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Función para sanitizar entradas
    function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Obtener y sanitizar entradas del formulario
    $instituto = sanitizeInput($_POST['instituto']);
    $ci = sanitizeInput($_POST['ci']);
    $fecha_nacimiento = sanitizeInput($_POST['fecha_nacimiento']);
    $procedencia = sanitizeInput($_POST['procedencia']);
    $apellido_paterno = sanitizeInput($_POST['apellido_paterno']);
    $apellido_materno = sanitizeInput($_POST['apellido_materno']);
    $nombre = sanitizeInput($_POST['nombre']);
    $sexo = sanitizeInput($_POST['sexo']);
    $complementarios = sanitizeInput($_POST['complementarios']);
    $direccion = sanitizeInput($_POST['direccion']);
    $telefono_fijo = sanitizeInput($_POST['telefono_fijo']);
    $numero_celular = sanitizeInput($_POST['numero_celular']);
    $correo = sanitizeInput($_POST['correo']);
    $contacto_tutor = sanitizeInput($_POST['contacto_tutor']);
    $nombre_tutor = sanitizeInput($_POST['nombre_tutor']);
    $cuenta_con_seguro = sanitizeInput($_POST['cuenta_con_seguro']);

    // Si no cuenta con seguro, no guardar el dato en "de_donde_es_seguro"
    $de_donde_es_seguro = ($cuenta_con_seguro == 'Si') ? sanitizeInput($_POST['de_donde_es_seguro']) : null;
    
    $cod_amitai = sanitizeInput($_POST['cod_amitai']);
    $pago_amitai = sanitizeInput($_POST['pago_amitai']);
    $monto_amitai = sanitizeInput($_POST['monto_amitai']);
    $fecha_amitai = !empty($_POST['fecha_amitai']) ? sanitizeInput($_POST['fecha_amitai']) : null;
    $pago_prospecto = sanitizeInput($_POST['pago_prospecto']);
    $monto_prospecto = sanitizeInput($_POST['monto_prospecto']);
    $fecha_prospecto = !empty($_POST['fecha_prospecto']) ? sanitizeInput($_POST['fecha_prospecto']) : null;
    $semana_presentacion = $instituto == 'COLMILAV' ? sanitizeInput($_POST['semana_presentacion']) : null;

    // Validaciones
    if ($cuenta_con_seguro == 'Si' && empty($de_donde_es_seguro)) {
        echo 'Error: Debe especificar de dónde es el seguro.';
        exit;
    }

    if ($instituto == 'COLMILAV' && empty($semana_presentacion)) {
        echo 'Error: Debe especificar la semana de presentación.';
        exit;
    }

    // Actualizar el registro en la base de datos
    $stmt = $pdo->prepare("UPDATE registros SET instituto = ?, ci = ?, fecha_nacimiento = ?, procedencia = ?, apellido_paterno = ?, apellido_materno = ?, nombre = ?, sexo = ?, complementarios = ?, direccion = ?, telefono_fijo = ?, numero_celular = ?, correo = ?, contacto_tutor = ?, nombre_tutor = ?, cuenta_con_seguro = ?, de_donde_es_seguro = ?, cod_amitai = ?, pago_amitai = ?, monto_amitai = ?, fecha_amitai = ?, pago_prospecto = ?, monto_prospecto = ?, fecha_prospecto = ?, semana_presentacion = ? WHERE id = ?");
    $stmt->execute([$instituto, $ci, $fecha_nacimiento, $procedencia, $apellido_paterno, $apellido_materno, $nombre, $sexo, $complementarios, $direccion, $telefono_fijo, $numero_celular, $correo, $contacto_tutor, $nombre_tutor, $cuenta_con_seguro, $de_donde_es_seguro, $cod_amitai, $pago_amitai, $monto_amitai, $fecha_amitai, $pago_prospecto, $monto_prospecto, $fecha_prospecto, $semana_presentacion, $id]);

    echo 'Modificación exitosa!';
    header('Location: ver.php');
    exit;
}

require '../includes/header.php';
?>

<div class="container">
    <h1>Modificar Registro</h1>
    <form method="post" onsubmit="return validateForm()">
        <label for="instituto">Instituto:</label>
        <select name="instituto" id="instituto" required onchange="toggleSemanaPresentacion()">
            <option value="COLMILAV" <?= $registro['instituto'] == 'COLMILAV' ? 'selected' : '' ?>>COLMILAV</option>
            <option value="POLMILAE" <?= $registro['instituto'] == 'POLMILAE' ? 'selected' : '' ?>>POLMILAE</option>
            <option value="EMMFAB" <?= $registro['instituto'] == 'EMMFAB' ? 'selected' : '' ?>>EMMFAB</option>
        </select>

        <div id="semana_presentacion_container" style="<?= $registro['instituto'] == 'COLMILAV' ? '' : 'display: none;' ?>">
            <label for="semana_presentacion">Semana de Presentación:</label>
            <select name="semana_presentacion" id="semana_presentacion">
                <option value="Semana 1" <?= $registro['semana_presentacion'] == 'Semana 1' ? 'selected' : '' ?>>Semana 1</option>
                <option value="Semana 2" <?= $registro['semana_presentacion'] == 'Semana 2' ? 'selected' : '' ?>>Semana 2</option>
            </select>
        </div>

        <label for="cod_amitai">Código AMITAI:</label>
        <input type="text" name="cod_amitai" id="cod_amitai" value="<?= htmlspecialchars($registro['cod_amitai']) ?>" required>

        <div class="flex-container"> <!-- Flex container para AMITAI -->
            <div>
                <label for="pago_amitai">Nro de boleta de pago AMITAI:</label>
                <input type="text" name="pago_amitai" id="pago_amitai" value="<?= htmlspecialchars($registro['pago_amitai']) ?>" required>
            </div>
            <div>
                <label for="monto_amitai">Monto (Bs):</label>
                <input type="text" name="monto_amitai" id="monto_amitai" value="<?= htmlspecialchars($registro['monto_amitai']) ?>" required>
            </div>
            <div>
                <label for="fecha_amitai">Fecha de Pago AMITAI:</label>
                <input type="date" name="fecha_amitai" id="fecha_amitai" value="<?= htmlspecialchars($registro['fecha_amitai']) ?>" required>
            </div>
        </div>

        <div class="flex-container"> <!-- Flex container para Prospecto -->
            <div>
                <label for="pago_prospecto">Nro de boleta del pago para el prospecto:</label>
                <input type="text" name="pago_prospecto" id="pago_prospecto" value="<?= htmlspecialchars($registro['pago_prospecto']) ?>" required>
            </div>
            <div>
                <label for="monto_prospecto">Monto (Bs):</label>
                <input type="text" name="monto_prospecto" id="monto_prospecto" value="<?= htmlspecialchars($registro['monto_prospecto']) ?>" required>
            </div>
            <div>
                <label for="fecha_prospecto">Fecha de Pago Prospecto:</label>
                <input type="date" name="fecha_prospecto" id="fecha_prospecto" value="<?= htmlspecialchars($registro['fecha_prospecto']) ?>" required>
            </div>
        </div>

        <label for="ci">Cédula de Identidad:</label>
        <input type="text" name="ci" id="ci" value="<?= htmlspecialchars($registro['ci']) ?>" required>

        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= htmlspecialchars($registro['apellido_paterno']) ?>" required>

        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" name="apellido_materno" id="apellido_materno" value="<?= htmlspecialchars($registro['apellido_materno']) ?>" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($registro['nombre']) ?>" required>

        <label for="sexo">Sexo:</label>
        <select name="sexo" id="sexo" required>
            <option value="Femenino" <?= $registro['sexo'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
            <option value="Masculino" <?= $registro['sexo'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
        </select>
        
        <label for="complementarios">Examenes Complementarios:</label>
        <select name="complementarios" id="complementarios" required>
            <option value="La Paz" <?= $registro['complementarios'] == 'La Paz' ? 'selected' : '' ?>>La Paz</option>
            <option value="Cochabamba" <?= $registro['complementarios'] == 'Cochabamba' ? 'selected' : '' ?>>Cochabamba</option>
            <option value="Santa Cruz" <?= $registro['complementarios'] == 'Santa Cruz' ? 'selected' : '' ?>>Santa Cruz</option>
        </select>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?= htmlspecialchars($registro['fecha_nacimiento']) ?>" required>

        <label for="procedencia">Procedencia:</label>
        <input type="text" name="procedencia" id="procedencia" value="<?= htmlspecialchars($registro['procedencia']) ?>" required>

        <label for="direccion">Residencia (Dirección):</label>
        <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($registro['direccion']) ?>" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($registro['correo']) ?>" required>

        <label for="telefono_fijo">Teléfono Fijo:</label>
        <input type="text" name="telefono_fijo" id="telefono_fijo" value="<?= htmlspecialchars($registro['telefono_fijo']) ?>">

        <label for="numero_celular">Número de Celular:</label>
        <input type="text" name="numero_celular" id="numero_celular" value="<?= htmlspecialchars($registro['numero_celular']) ?>" required>

        <label for="contacto_tutor">Contacto del Tutor:</label>
        <input type="text" name="contacto_tutor" id="contacto_tutor" value="<?= htmlspecialchars($registro['contacto_tutor']) ?>">

        <label for="nombre_tutor">Nombre del Tutor:</label>
        <input type="text" name="nombre_tutor" id="nombre_tutor" value="<?= htmlspecialchars($registro['nombre_tutor']) ?>">

        <label for="cuenta_con_seguro">¿Cuenta con Seguro?</label>
        <select name="cuenta_con_seguro" id="cuenta_con_seguro" required onchange="toggleSeguro()">
            <option value="No" <?= $registro['cuenta_con_seguro'] == 'No' ? 'selected' : '' ?>>No</option>
            <option value="Si" <?= $registro['cuenta_con_seguro'] == 'Si' ? 'selected' : '' ?>>Sí</option>
        </select>

        <label for="de_donde_es_seguro" id="label_de_donde_es_seguro" style="<?= $registro['cuenta_con_seguro'] == 'Si' ? '' : 'display: none;' ?>">¿De dónde es el Seguro?</label>
        <input type="text" name="de_donde_es_seguro" id="de_donde_es_seguro" value="<?= htmlspecialchars($registro['de_donde_es_seguro']) ?>" style="<?= $registro['cuenta_con_seguro'] == 'Si' ? '' : 'display: none;' ?>">

        <button type="submit">Guardar Cambios</button>
    </form>
    <div class="button-container">
        <a href="ver.php" class="button">Volver</a>
    </div>
</div>

<style>
    .flex-container {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
        width: 100%; /* Asegura que el contenedor ocupe todo el ancho disponible */
    }

    .flex-container > div {
        flex: 1; /* Hace que cada campo dentro del contenedor flex ocupe el mismo ancho */
    }

    .flex-container label {
        display: block;
        margin-bottom: 5px;
    }

    .flex-container input {
        width: 100%; /* Asegura que los campos de entrada ocupen todo el ancho del contenedor */
    }

    select, input {
        padding: 8px;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
    }
</style>

<script>
    function toggleSeguro() {
        const cuentaConSeguro = document.getElementById('cuenta_con_seguro').value;
        const deDondeEsSeguroLabel = document.getElementById('label_de_donde_es_seguro');
        const deDondeEsSeguroInput = document.getElementById('de_donde_es_seguro');

        if (cuentaConSeguro === 'Si') {
            deDondeEsSeguroLabel.style.display = 'block';
            deDondeEsSeguroInput.style.display = 'block';
        } else {
            deDondeEsSeguroLabel.style.display = 'none';
            deDondeEsSeguroInput.style.display = 'none';
        }
    }

    function toggleSemanaPresentacion() {
        const instituto = document.getElementById('instituto').value;
        const semanaPresentacionContainer = document.getElementById('semana_presentacion_container');

        if (instituto === 'COLMILAV') {
            semanaPresentacionContainer.style.display = 'block';
        } else {
            semanaPresentacionContainer.style.display = 'none';
        }
    }

    function validateForm() {
        const cuentaConSeguro = document.getElementById('cuenta_con_seguro').value;
        const deDondeEsSeguroInput = document.getElementById('de_donde_es_seguro').value;
        const instituto = document.getElementById('instituto').value;
        const semanaPresentacionInput = document.getElementById('semana_presentacion').value;

        if (cuentaConSeguro === 'Si' && deDondeEsSeguroInput.trim() === '') {
            alert('Por favor, especifique de dónde es el seguro.');
            return false;
        }

        if (instituto === 'COLMILAV' && semanaPresentacionInput.trim() === '') {
            alert('Por favor, especifique la semana de presentación.');
            return false;
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleSeguro();
        toggleSemanaPresentacion();
    });
</script>
