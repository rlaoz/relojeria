<?php
include '../config/conexion.php';
$pdo = new Conexion();

// Obtener datos JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['status' => 'error', 'msg' => 'No se recibieron datos']);
    exit;
}

$tabla = $data['tabla'] ?? '';
$type = $data['type'] ?? 0;

// Configuración de tablas y campos
$tablas_config = [
    "productos" => ["cod_producto", "nombre_producto", "ubicacion_bodega", "cant_actual", "precio_costo", "precio_venta"],
    "proveedores" => ["cod_proveedor", "nombre", "direccion", "RTN", "ciudad_actual"],
    "acompaniante" => ["cod_acompaniante", "nombre", "precio"],
    "platos" => ["cod_plato","nombre","precio","fecha_hora_creacion","fecha_hora_modificacion"],
    "menu" => ["cod_menu","fecha_elaboracion","descripcion","fecha_hora_creacion","fecha_hora_modificacion"]
];

// Validar tabla
if (!array_key_exists($tabla, $tablas_config)) {
    echo json_encode(['status' => 'error', 'msg' => 'Tabla no válida']);
    exit;
}

$campos = $tablas_config[$tabla];
$valores = [];
foreach ($campos as $campo) {
    $valores[$campo] = $data[$campo] ?? null;
}

// ==========================
// AGREGAR
// ==========================
if ($type == 1) {
    $sql = "INSERT INTO $tabla (" . implode(",", $campos) . ") VALUES (:" . implode(",:", $campos) . ")";
    $stmt = $pdo->prepare($sql);
    foreach ($campos as $campo) {
        $stmt->bindValue(":$campo", $valores[$campo]);
    }
    $res = $stmt->execute();

    if ($res) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => $stmt->errorInfo()]);
    }
    exit;
}

// ==========================
// EDITAR
// ==========================
if ($type == 2) {
    $pk = $campos[0]; // asumimos primer campo como PK
    $update_fields = [];
    foreach ($campos as $c) {
        if ($c != $pk) $update_fields[] = "$c=:$c";
    }
    $sql = "UPDATE $tabla SET " . implode(",", $update_fields) . " WHERE $pk=:$pk";
    $stmt = $pdo->prepare($sql);
    foreach ($campos as $c) {
        $stmt->bindValue(":$c", $valores[$c]);
    }
    $res = $stmt->execute();

    if ($res) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => $stmt->errorInfo()]);
    }
    exit;
}

// ==========================
// ELIMINAR
// ==========================
if ($type == 3) {
    if (empty($data['id'])) {
        echo json_encode(['status'=>'error','msg'=>'ID no recibido']);
        exit;
    }

    // Definir PK para cada tabla
    $pk_tablas = [
        "productos" => "cod_producto",
        "proveedores" => "cod_proveedor",
        "acompaniante" => "cod_acompaniante",
        "platos" => "cod_plato",
        "menu" => "cod_menu"
    ];

    if (!array_key_exists($tabla, $pk_tablas)) {
        echo json_encode(['status'=>'error','msg'=>'Tabla no permitida']);
        exit;
    }

    $pk = $pk_tablas[$tabla];
    $sql = "DELETE FROM $tabla WHERE $pk = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $data['id']);
    $res = $stmt->execute();

    if ($res) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','msg'=>$stmt->errorInfo()]);
    }
    exit;
}

// Si llega aquí, tipo inválido
echo json_encode(['status'=>'error','msg'=>'Tipo de operación no válido']);
exit;
?>



?>