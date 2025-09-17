
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
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
$omitidos = ['fecha_hora_creacion', 'fecha_hora_modificacion'];
$campos_visibles = array_diff($campos, $omitidos); // columnas que sí recibimos del front

$valores = [];
foreach ($campos as $campo) {
    $valores[$campo] = $data[$campo] ?? null;
}
// ==========================
// AGREGAR
// ==========================
if ($type == 1) { // AGREGAR
    $sql = "INSERT INTO $tabla (" . implode(",", $campos_visibles) . ") VALUES (:" . implode(",:", $campos_visibles) . ")";
    $stmt = $pdo->prepare($sql);
    foreach ($campos_visibles as $campo) {
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
if ($type == 2) { // EDITAR
    $pk = $campos[0];
    $update_fields = [];
    foreach ($campos_visibles as $c) { // solo columnas visibles
        if ($c != $pk) $update_fields[] = "$c=:$c";
    }
    $sql = "UPDATE $tabla SET " . implode(",", $update_fields) . " WHERE $pk=:$pk";
    $stmt = $pdo->prepare($sql);
    foreach ($campos_visibles as $c) {
        $stmt->bindValue(":$c", $valores[$c]);
    }
    $stmt->bindValue(":$pk", $valores[$pk]);
    $res = $stmt->execute();

    if ($res) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => $stmt->errorInfo()]);
    }
    exit;
}






// Si llega aquí, tipo inválido
echo json_encode(['status'=>'error','msg'=>'Tipo de operación no válido']);
echo json_encode(['status'=>'debug', 'pk'=>$pk, 'valor'=>$valor, 'tabla'=>$tabla]);
exit;
?>



?>