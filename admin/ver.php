<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require '../config/db.php';
require '../includes/header.php';

$admin_id = $_SESSION['user_id'];
$nivel = $_SESSION['nivel'];

if ($nivel == 'departamental' && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query_check_owner = "SELECT admin_id FROM registros WHERE id = ?";
    $stmt_check_owner = $pdo->prepare($query_check_owner);
    $stmt_check_owner->execute([$delete_id]);
    $owner = $stmt_check_owner->fetchColumn();

    if ($owner == $admin_id) {
        $stmt = $pdo->prepare("DELETE FROM registros WHERE id = ?");
        $stmt->execute([$delete_id]);
        echo "Registro borrado exitosamente.";
    } else {
        echo "No tienes permiso para eliminar este registro.";
    }
}

$sexo_filtrado = '';
$instituto_filtrada = '';
$punto_inscripcion_filtrado = '';
$apellido_paterno_filtrado = '';
$registros = [];
$conteos_sexo = ['Femenino' => 0, 'Masculino' => 0];

$query_institutos = "SELECT DISTINCT instituto FROM registros";
$stmt_institutos = $pdo->prepare($query_institutos);
$stmt_institutos->execute();
$institutos = $stmt_institutos->fetchAll(PDO::FETCH_ASSOC);

$query_admins = "SELECT DISTINCT nombre FROM administradores";
$stmt_admins = $pdo->prepare($query_admins);
$stmt_admins->execute();
$puntos_inscripcion = $stmt_admins->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM registros";
$filters = [];
$params = [];

if (isset($_GET['punto_inscripcion']) && !empty($_GET['punto_inscripcion'])) {
    $punto_inscripcion = $_GET['punto_inscripcion'];
    $query_admin_id = "SELECT id FROM administradores WHERE nombre = ?";
    $stmt_admin_id = $pdo->prepare($query_admin_id);
    $stmt_admin_id->execute([$punto_inscripcion]);
    $admin_id_result = $stmt_admin_id->fetchColumn();

    if ($admin_id_result !== false) {
        $filters[] = "admin_id = ?";
        $params[] = $admin_id_result;
        $punto_inscripcion_filtrado = $punto_inscripcion;
    }
}

if (isset($_GET['instituto']) && !empty($_GET['instituto'])) {
    $instituto = $_GET['instituto'];
    $filters[] = "instituto = ?";
    $params[] = $instituto;
    $instituto_filtrada = $instituto;
}

if (isset($_GET['sexo']) && !empty($_GET['sexo'])) {
    $sexo = $_GET['sexo'];
    $filters[] = "sexo = ?";
    $params[] = $sexo;
    $sexo_filtrado = $sexo;
}

if (isset($_GET['apellido_paterno']) && !empty($_GET['apellido_paterno'])) {
    $apellido_paterno = $_GET['apellido_paterno'];
    $filters[] = "apellido_paterno LIKE ?";
    $params[] = "%$apellido_paterno%";
    $apellido_paterno_filtrado = $apellido_paterno;
}

if (count($filters) > 0) {
    $query .= " WHERE " . implode(" AND ", $filters);
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_femenino = 0;
$total_masculino = 0;

foreach ($registros as $registro) {
    if ($registro['sexo'] == 'Femenino') {
        $total_femenino++;
    } elseif ($registro['sexo'] == 'Masculino') {
        $total_masculino++;
    }
}
?>

<div class="container">
    <div class="counts">
        <p><strong>Registros Femeninos:</strong> <?= $total_femenino ?> <strong> Registros Masculinos: </strong><?= $total_masculino ?></p>
    </div>

    <form method="get" class="filter-form">
        <div class="filter-group">
            <label for="instituto">Filtrar por Institución:</label>
            <select name="instituto" id="instituto">
                <option value="">Todos</option>
                <?php foreach ($institutos as $instituto): ?>
                    <option value="<?= htmlspecialchars($instituto['instituto']) ?>" <?= $instituto_filtrada == $instituto['instituto'] ? 'selected' : '' ?>><?= htmlspecialchars($instituto['instituto']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="punto_inscripcion">Filtrar por Punto de Inscripción:</label>
            <select name="punto_inscripcion" id="punto_inscripcion">
                <option value="">Todos</option>
                <?php foreach ($puntos_inscripcion as $punto): ?>
                    <option value="<?= htmlspecialchars($punto['nombre']) ?>" <?= $punto_inscripcion_filtrado == $punto['nombre'] ? 'selected' : '' ?>><?= htmlspecialchars($punto['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="apellido_paterno">Buscar por Apellido Paterno:</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= htmlspecialchars($apellido_paterno_filtrado) ?>">
        </div>
        <div class="filter-group">
            <button type="submit">Filtrar</button>
        </div>
    </form>

    <div class="button-container">
        <a href="export_excel.php?<?= http_build_query($_GET) ?>" class="button">Exportar a Excel</a>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Nro</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Nombre</th>
                <th>CI</th>
                <th>Instituto</th>
                <th>Semana de Presentacion</th>
                <th>Código Amitai</th>
                <th>Monto AMITAI (Bs)</th>
                <th>Fecha de Pago AMITAI</th>
                <th>Monto Prospecto (Bs)</th>
                <th>Fecha de Pago Prospecto</th>
                <th>Sexo</th>
                <th>Examenes Complementarios</th>
                <th>Fecha de Nacimiento</th>
                <th>Procedencia</th>
                <th>Residencia</th>
                <th>Correo</th>
                <th>Teléfono Fijo</th>
                <th>Número de Celular</th>
                <th>Contacto del Tutor</th>
                <th>Nombre del Tutor</th>
                <th>Cuenta con Seguro</th>
                <th>De dónde es el Seguro</th>
                <?php if ($nivel == 'departamental'): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($registros as $index => $registro): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($registro['apellido_paterno']) ?></td>
                    <td><?= htmlspecialchars($registro['apellido_materno']) ?></td>
                    <td><?= htmlspecialchars($registro['nombre']) ?></td>
                    <td><?= htmlspecialchars($registro['ci']) ?></td>
                    <td><?= htmlspecialchars($registro['instituto']) ?></td>
                    <td><?= htmlspecialchars($registro['semana_presentacion']) ?></td>
                    <td><?= htmlspecialchars($registro['cod_amitai']) ?></td>
                    <td><?= htmlspecialchars($registro['monto_amitai']) ?></td> <!-- Campo añadido -->
                    <td><?= htmlspecialchars($registro['fecha_amitai']) ?></td> <!-- Campo añadido -->
                    <td><?= htmlspecialchars($registro['monto_prospecto']) ?></td> <!-- Campo añadido -->
                    <td><?= htmlspecialchars($registro['fecha_prospecto']) ?></td> <!-- Campo añadido -->
                    <td><?= htmlspecialchars($registro['sexo']) ?></td>
                    <td><?= htmlspecialchars($registro['complementarios']) ?></td>
                    <td><?= htmlspecialchars($registro['fecha_nacimiento']) ?></td>
                    <td><?= htmlspecialchars($registro['procedencia']) ?></td>
                    <td><?= htmlspecialchars($registro['direccion']) ?></td>
                    <td><?= htmlspecialchars($registro['correo']) ?></td>
                    <td><?= htmlspecialchars($registro['telefono_fijo']) ?></td>
                    <td><?= htmlspecialchars($registro['numero_celular']) ?></td>
                    <td><?= htmlspecialchars($registro['contacto_tutor']) ?></td>
                    <td><?= htmlspecialchars($registro['nombre_tutor']) ?></td>
                    <td><?= htmlspecialchars($registro['cuenta_con_seguro']) ?></td>
                    <td><?= htmlspecialchars($registro['cuenta_con_seguro'] == 'Si' ? $registro['de_donde_es_seguro'] : '') ?></td>
                    <?php if ($nivel == 'departamental'): ?>
                        <td>
                            <?php if ($registro['admin_id'] == $admin_id): ?>
                                <a href="modificar.php?id=<?= $registro['id'] ?>" class="button">Modificar</a>
                                <a href="ver.php?delete_id=<?= $registro['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas borrar este registro?');" class="button">Borrar</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <a href="index.php" class="button">Volver al Índice</a>
</div>

<style>
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 5px;
    }

    .filter-group select, .filter-group input {
        padding: 5px;
    }

    .button-container {
        margin: 20px 0;
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

</style>
