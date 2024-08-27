<?php
require('../includes/SimpleXLSXGen.php'); 
require('../config/db.php');

session_start();

use Shuchkin\SimpleXLSXGen; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Actualizar el encabezado con los nuevos campos
$header = [
    ['Nro', 'Instituto', 'Código AMITAI', 'Nro de boleta de pago AMITAI', 'Monto AMITAI (Bs)', 'Fecha de Pago AMITAI', 
    'Nro de boleta del pago para el prospecto', 'Monto Prospecto (Bs)', 'Fecha de Pago Prospecto', 
    'CI', 'Apellido Paterno', 'Apellido Materno', 'Nombre', 'Sexo', 
    'Examenes Complementarios', 'Fecha de Nacimiento', 'Procedencia', 'Residencia', 
    'Correo', 'Teléfono Fijo', 'Número de Celular', 'Contacto del Tutor', 
    'Nombre del Tutor', 'Cuenta con Seguro', 'De dónde es el Seguro', 'Semana de Presentación']
];

$data = [];
$contador = 1;

$query = "SELECT * FROM registros";
$params = [];
$filters = [];

// Obtener el admin_id basado en el punto de inscripción seleccionado
if (isset($_GET['punto_inscripcion']) && !empty($_GET['punto_inscripcion'])) {
    $punto_inscripcion = $_GET['punto_inscripcion'];
    $query_admin_id = "SELECT id FROM administradores WHERE nombre = ?";
    $stmt_admin_id = $pdo->prepare($query_admin_id);
    $stmt_admin_id->execute([$punto_inscripcion]);
    $admin_id_result = $stmt_admin_id->fetchColumn();

    if ($admin_id_result !== false) {
        $filters[] = "admin_id = ?";
        $params[] = $admin_id_result;
    }
}

if (isset($_GET['instituto']) && !empty($_GET['instituto'])) {
    $instituto = $_GET['instituto'];
    $filters[] = "instituto = ?";
    $params[] = $instituto;
}

if (isset($_GET['sexo']) && !empty($_GET['sexo'])) {
    $sexo = $_GET['sexo'];
    $filters[] = "sexo = ?";
    $params[] = $sexo;
}

if (isset($_GET['apellido_paterno']) && !empty($_GET['apellido_paterno'])) {
    $apellido_paterno = $_GET['apellido_paterno'];
    $filters[] = "apellido_paterno LIKE ?";
    $params[] = "%$apellido_paterno%";
}

if (count($filters) > 0) {
    $query .= " WHERE " . implode(" AND ", $filters);
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        $contador++,
        $row['instituto'],
        $row['cod_amitai'],
        $row['pago_amitai'], // Nro de boleta de pago AMITAI
        $row['monto_amitai'], // Monto AMITAI (Bs)
        $row['fecha_amitai'], // Fecha de Pago AMITAI
        $row['pago_prospecto'], // Nro de boleta del pago para el prospecto
        $row['monto_prospecto'], // Monto Prospecto (Bs)
        $row['fecha_prospecto'], // Fecha de Pago Prospecto
        $row['ci'],
        $row['apellido_paterno'],
        $row['apellido_materno'],
        $row['nombre'],
        $row['sexo'],
        $row['complementarios'],
        $row['fecha_nacimiento'],
        $row['procedencia'],
        $row['direccion'], // Residencia
        $row['correo'],
        $row['telefono_fijo'],
        $row['numero_celular'],
        $row['contacto_tutor'],
        $row['nombre_tutor'],
        $row['cuenta_con_seguro'],
        $row['de_donde_es_seguro'],
        $row['semana_presentacion'] // Añadir Semana de Presentación
    ];
}

$excel_data = array_merge($header, $data);

$xlsx = SimpleXLSXGen::fromArray($excel_data);
$xlsx->setDefaultFont('Calibri', 11);

$filename = "registros.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$xlsx->downloadAs($filename);
exit;
?>
