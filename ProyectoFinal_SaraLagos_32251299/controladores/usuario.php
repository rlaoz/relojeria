<?php
include '../config/conexion.php';
$pdo = new Conexion();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entityBody = json_decode(file_get_contents('php://input'), true);

    try {
        error_log(print_r($entityBody, true));

        if ($entityBody['Operacion'] == 'Login') {
            $sql = "SELECT * FROM usuarios WHERE Username = :Username AND clave = :clave AND estado = 1;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':Username', $entityBody['Username']);
            $stmt->bindValue(':clave', $entityBody['clave']);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo json_encode($stmt->fetchAll());
        } elseif ($entityBody['Operacion'] == 'Consulta') {
            $sql = "SELECT * FROM usuarios WHERE Username = COALESCE(:Username, Username) AND estado = 1;";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':Username', $entityBody['Username']);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo json_encode($stmt->fetchAll());
        } elseif ($entityBody['Operacion'] == 'Crear') {
            $sql = "INSERT INTO usuarios (Username, nombre, edad, email, clave, telefono, create_time, estado)
                    VALUES (:Username, :nombre, :edad, :email, :clave, :telefono, :create_time, :estado);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':Username', $entityBody['Username']);
            $stmt->bindValue(':nombre', $entityBody['nombre']);
            $stmt->bindValue(':edad', $entityBody['edad']);
            $stmt->bindValue(':email', $entityBody['email']);
            $stmt->bindValue(':clave', $entityBody['clave']);
            $stmt->bindValue(':telefono', $entityBody['telefono']);
            $stmt->bindValue(':create_time', $entityBody['create_time']);
            $stmt->bindValue(':estado', $entityBody['estado']);
            $stmt->execute();
            $idPost = $pdo->lastInsertId();
            if ($idPost) {
                header("HTTP/1.1 200 OK");
                echo json_encode(["Registro Creado" => $idPost, "Resultado" => "Success"]);
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode(["Registro Creado" => $idPost, "Resultado" => "Error en procesamiento"]);
            }
        } elseif ($entityBody['Operacion'] == 'Actualizar') {
            if (isset($entityBody['Username'])) {
                $fields = [];
                $params = [':Username' => $entityBody['Username']];
        
                // Lista de campos a actualizar
                $updateFields = [
                    'nombre', 'clave', 'edad', 'email', 'telefono', 'create_time',
                    'estado', 'Direccion', 'Ubicacion', 'Facebook', 'Twitter', 'Youtube', 'FechaNac'
                ];
        
                foreach ($updateFields as $field) {
                    if (isset($entityBody[$field])) {
                        $fields[] = "$field = COALESCE(:$field, $field)";
                        $params[":$field"] = $entityBody[$field];
                    }
                }
        
                if (empty($fields)) {
                    header("HTTP/1.1 400 Bad Request");
                    echo json_encode(["error" => "No fields to update"]);
                    exit;
                }
        
                $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE Username = :Username";
                $stmt = $pdo->prepare($sql);
        
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
        
                $resultado = $stmt->execute();
                if ($resultado) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode(["Registro Actualizado" => $stmt->rowCount(), "Resultado" => "Success"]);
                } else {
                    header("HTTP/1.1 500 Internal Server Error");
                    echo json_encode(["Registro Actualizado" => $stmt->rowCount(), "Resultado" => "Error en procesamiento"]);
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(["error" => "Username is required"]);
            }        
        } elseif ($entityBody['Operacion'] == 'Borrar') {
            if (isset($entityBody['Username'])) {
                $sql = "UPDATE usuarios SET estado = 0 WHERE Username = :Username;";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':Username', $entityBody['Username']);
                $resultado = $stmt->execute();
                if ($resultado) {
                    header("HTTP/1.1 200 OK");
                    echo json_encode(["Registro Borrado" => $stmt->rowCount(), "Resultado" => "Success"]);
                } else {
                    header("HTTP/1.1 500 Internal Server Error");
                    echo json_encode(["Registro Borrado" => $stmt->rowCount(), "Resultado" => "Error en procesamiento"]);
                }
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(["Registro Borrado" => 0, "Resultado" => "Error el campo Username es obligatorio"]);
            }
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
