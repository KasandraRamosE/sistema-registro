<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['nivel'] != 'departamental') {
    header('Location: ../login.php');
    exit;
}

require '../config/db.php';

// Función para sanitizar entradas
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $de_donde_es_seguro = ($cuenta_con_seguro == 'Si') ? sanitizeInput($_POST['de_donde_es_seguro']) : null;
    
    $cod_amitai = sanitizeInput($_POST['cod_amitai']);
    $pago_amitai = sanitizeInput($_POST['pago_amitai']);
    $monto_amitai = sanitizeInput($_POST['monto_amitai']);
    $fecha_amitai = !empty($_POST['fecha_amitai']) ? sanitizeInput($_POST['fecha_amitai']) : null;
    $pago_prospecto = sanitizeInput($_POST['pago_prospecto']);
    $monto_prospecto = sanitizeInput($_POST['monto_prospecto']);
    $fecha_prospecto = !empty($_POST['fecha_prospecto']) ? sanitizeInput($_POST['fecha_prospecto']) : null;
    $admin_id = $_SESSION['user_id'];
    $ciudad_registro = $_SESSION['departamento'];
    $semana_presentacion = $instituto == 'COLMILAV' ? sanitizeInput($_POST['semana_presentacion']) : null;

    if ($cuenta_con_seguro == 'Si' && empty($de_donde_es_seguro)) {
        echo 'Error: Debe especificar de dónde es el seguro.';
        exit;
    }

    if ($instituto == 'COLMILAV' && empty($semana_presentacion)) {
        echo 'Error: Debe especificar la semana de presentación.';
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO registros (instituto, ci, fecha_nacimiento, procedencia, apellido_paterno, apellido_materno, nombre, sexo, complementarios, ciudad_registro, direccion, telefono_fijo, numero_celular, correo, contacto_tutor, nombre_tutor, cuenta_con_seguro, de_donde_es_seguro, cod_amitai, pago_amitai, monto_amitai, fecha_amitai, pago_prospecto, monto_prospecto, fecha_prospecto, admin_id, fecha_creacion, semana_presentacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    $stmt->execute([$instituto, $ci, $fecha_nacimiento, $procedencia, $apellido_paterno, $apellido_materno, $nombre, $sexo, $complementarios, $ciudad_registro, $direccion, $telefono_fijo, $numero_celular, $correo, $contacto_tutor, $nombre_tutor, $cuenta_con_seguro, $de_donde_es_seguro, $cod_amitai, $pago_amitai, $monto_amitai, $fecha_amitai, $pago_prospecto, $monto_prospecto, $fecha_prospecto, $admin_id, $semana_presentacion]);

    echo 'Registro exitoso!';
}

require '../includes/header.php';
?>



<style>
    .flex-container {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 20px;
        width: 100%; /* Asegura que el contenedor ocupe todo el ancho disponible */
    }

    .flex-container > div {
        flex: 1;
    }

    .flex-container label {
        display: block;
        margin-bottom: 5px;
    }

    .flex-container input {
        width: 100%; 
    }

    select, input {
        padding: 8px;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
    }
</style>

<div class="container">
    <h1>Registrar Persona</h1>
    <form method="post" onsubmit="return validateForm()">
        <label for="instituto">Instituto:</label>
        <select name="instituto" id="instituto" required onchange="toggleSemanaPresentacion()">
            <option value="COLMILAV">COLMILAV</option>
            <option value="POLMILAE">POLMILAE</option>
            <option value="EMMFAB">EMMFAB</option>
        </select>

        <div id="semana_presentacion_container" style="display: none;">
            <label for="semana_presentacion">Semana de Presentación:</label>
            <select name="semana_presentacion" id="semana_presentacion">
                <option value="Semana 1">Semana 1</option>
                <option value="Semana 2">Semana 2</option>
            </select>
        </div>

        <label for="cod_amitai">Código AMITAI:</label>
        <input type="text" name="cod_amitai" id="cod_amitai" required>

        <div class="flex-container"> 
            <div>
                <label for="pago_amitai">Nro de boleta de pago AMITAI:</label>
                <input type="text" name="pago_amitai" id="pago_amitai" required>
            </div>
            <div>
                <label for="monto_amitai">Monto (Bs):</label>
                <input type="text" name="monto_amitai" id="monto_amitai" required>
            </div>
            <div>
                <label for="fecha_amitai">Fecha de Pago AMITAI:</label>
                <input type="date" name="fecha_amitai" id="fecha_amitai" required>
            </div>
        </div>

        <div class="flex-container">
            <div>
                <label for="pago_prospecto">Nro de boleta del pago para el prospecto:</label>
                <input type="text" name="pago_prospecto" id="pago_prospecto" required>
            </div>
            <div>
                <label for="monto_prospecto">Monto (Bs):</label>
                <input type="text" name="monto_prospecto" id="monto_prospecto" required>
            </div>
            <div>
                <label for="fecha_prospecto">Fecha de Pago Prospecto:</label>
                <input type="date" name="fecha_prospecto" id="fecha_prospecto" required>
            </div>
        </div>

        <label for="ci">Cédula de Identidad:</label>
        <input type="text" name="ci" id="ci" required>

        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" id="apellido_paterno" required>

        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" name="apellido_materno" id="apellido_materno" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="sexo">Sexo:</label>
        <select name="sexo" id="sexo" required>
            <option value="Femenino">Femenino</option>
            <option value="Masculino">Masculino</option>
        </select>
        
        <label for="complementarios">Examenes Complementarios:</label>
        <select name="complementarios" id="complementarios" required>
            <option value="La Paz">La Paz</option>
            <option value="Cochabamba">Cochabamba</option>
            <option value="Santa Cruz">Santa Cruz</option>
        </select>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>

        <label for="procedencia">Procedencia:</label>
        <input type="text" name="procedencia" id="procedencia" required>

        <label for="direccion">Residencia (Dirección):</label>
        <input type="text" name="direccion" id="direccion" required>

        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" required>

        <label for="telefono_fijo">Teléfono Fijo:</label>
        <input type="text" name="telefono_fijo" id="telefono_fijo">

        <label for="numero_celular">Número de Celular:</label>
        <input type="text" name="numero_celular" id="numero_celular" required>

        <label for="contacto_tutor">Contacto del Tutor:</label>
        <input type="text" name="contacto_tutor" id="contacto_tutor">

        <label for="nombre_tutor">Nombre del Tutor:</label>
        <input type="text" name="nombre_tutor" id="nombre_tutor">

        <label for="cuenta_con_seguro">¿Cuenta con Seguro?</label>
        <select name="cuenta_con_seguro" id="cuenta_con_seguro" required onchange="toggleSeguro()">
            <option value="No">No</option>
            <option value="Si">Sí</option>
        </select>

        <label for="de_donde_es_seguro" id="label_de_donde_es_seguro" style="display: none;">¿De dónde es el Seguro?</label>
        <input type="text" name="de_donde_es_seguro" id="de_donde_es_seguro" style="display: none;">

        <button type="submit">Registrar</button>
    </form>
    <div class="button-container">
        <a href="ver.php" class="button">Ver Registros</a>
        <a href="index.php" class="button">Volver al Índice</a>
    </div>
</div>

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
