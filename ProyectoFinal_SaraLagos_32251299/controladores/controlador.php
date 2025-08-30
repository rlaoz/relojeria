
<?php
include '../config/conexion.php';
$pdo = new Conexion();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $sql = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit;
    } else {
        $sql = $pdo->prepare("SELECT * FROM productos");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $entityBody = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error de decodificación JSON: ' . json_last_error_msg());
        }

        if ($entityBody['type'] == 1) { // Agregar
            $sql = "INSERT INTO productos (create_time, descripcion, cantidad, precio, modelo, marca, caracteristicas, estado)
                    VALUES (:create_time, :descripcion, :cantidad, :precio, :modelo, :marca, :caracteristicas, :estado)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':create_time', $entityBody['create_time']);
            $stmt->bindValue(':descripcion', $entityBody['descripcion']);
            $stmt->bindValue(':cantidad', $entityBody['cantidad']);
            $stmt->bindValue(':precio', $entityBody['precio']);
            $stmt->bindValue(':modelo', $entityBody['modelo']);
            $stmt->bindValue(':marca', $entityBody['marca']);
            $stmt->bindValue(':caracteristicas', $entityBody['caracteristicas']);
            $stmt->bindValue(':estado', $entityBody['estado']);
            $stmt->execute();

            $idPost = $pdo->lastInsertId();

            if ($idPost) {
                header("HTTP/1.1 200 OK");
                $Resp = ['RegistroCreado' => $idPost, 'Resultado' => 'Success'];
                echo json_encode($Resp);
            } else {
                header("HTTP/1.1 500 Error");
                $Resp = ['Resultado' => 'Error en procesamiento'];
                echo json_encode($Resp);
            }
        }elseif ($entityBody['type'] == 2) { // Editar
            $sql = "UPDATE productos SET create_time = :create_time, descripcion = :descripcion, cantidad = :cantidad, precio = :precio, modelo = :modelo, marca = :marca, caracteristicas = :caracteristicas
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':id', $entityBody['id']);
            $stmt->bindValue(':create_time', $entityBody['create_time']);
            $stmt->bindValue(':descripcion', $entityBody['descripcion']);
            $stmt->bindValue(':cantidad', $entityBody['cantidad']);
            $stmt->bindValue(':precio', $entityBody['precio']);
            $stmt->bindValue(':modelo', $entityBody['modelo']);
            $stmt->bindValue(':marca', $entityBody['marca']);
            $stmt->bindValue(':caracteristicas', $entityBody['caracteristicas']);
            $stmt->execute();

            if ($stmt->rowCount()) {
                header("HTTP/1.1 200 OK");
                $Resp = ['Resultado' => 'Success'];
                echo json_encode($Resp);
            } else {
                header("HTTP/1.1 500 Error");
                $Resp = ['Resultado' => 'Error en procesamiento'];
                echo json_encode($Resp);
            }
        }elseif ($entityBody['type'] == 3) { // Eliminar
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':id', $entityBody['id']);
            $stmt->execute();

            if ($stmt->rowCount()) {
                header("HTTP/1.1 200 OK");
                $Resp = ['Resultado' => 'Success'];
                echo json_encode($Resp);
            } else {
                header("HTTP/1.1 500 Error");
                $Resp = ['Resultado' => 'Error en procesamiento'];
                echo json_encode($Resp);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            $Resp = ['Resultado' => 'Tipo de solicitud no válido'];
            echo json_encode($Resp);
        }
    } catch (Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        $Resp = ['Resultado' => 'Error: ' . $e->getMessage()];
        echo json_encode($Resp);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entityBody = json_decode(file_get_contents('php://input'), true);
    $productos = json_decode($entityBody['productos'], true);

    try {
        error_log(print_r($entityBody, true));
        
        // Inserta la orden en la tabla ordencompra
        $sql = "INSERT INTO ordencompra (Username, subtotal, total, create_time, estado)
                VALUES (:username, :subtotal, :total, NOW(), :estado)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $entityBody['username']);
        $stmt->bindValue(':subtotal', $entityBody['subtotal']);
        $stmt->bindValue(':total', $entityBody['total']);
        $stmt->bindValue(':estado', $entityBody['estado']);
        $stmt->execute();
        $ordenId = $pdo->lastInsertId();

        foreach ($productos as $producto) {
            $productoId = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];

            // Inserta en la tabla detalleorden
            $sql = "INSERT INTO detalleorden (idOrden, Username, cantidad, precio, total) 
                    VALUES (:idOrden, :username, :cantidad, :precio, :total)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':idOrden', $ordenId);
            $stmt->bindValue(':username', $entityBody['username']);
            $stmt->bindValue(':cantidad', $cantidad);
            $stmt->bindValue(':precio', $precio);
            $stmt->bindValue(':total', $cantidad * $precio);
            $stmt->execute();
        }

        header("HTTP/1.1 200 OK");
        echo json_encode(["Resultado" => "Success"]);
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>