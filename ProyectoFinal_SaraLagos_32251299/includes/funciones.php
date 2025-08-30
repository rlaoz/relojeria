<?php
function Login($usuario, $clave){
    try{
        //importar las credenciales
        require '../config/database.php';
        //consulta SQL
        $sql = "select Username,nombre,edad,email,telefono, tipoCuenta 
                   from usuarios where Username = '".$usuario."' and clave = '".$clave."'  and estado = 1;";
        //Realizar la consulta
        //echo 'SQL: '.$sql;
        $consulta = mysqli_query($db, $sql);
        //acceder a los resultados
        /*echo "<pre>";
          var_dump(mysqli_fetch_assoc($consulta));
        echo "</pre>";*/
        //Cerrar la conexion (opcional)
         $resp = mysqli_fetch_assoc($consulta);
         return $resp;
        $resultado = mysqli_close($db);
        //echo $resultado;
    }catch(\Throwable $th){
        var_dump($th);
    }
}

function estaAutenticado() :bool {
    session_start();
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
}

function CrearUsuario($nombre, $apellido, $edad, $Telefono, $username, $email, $clave){
    try{
        //importar las credenciales
        require '../config/database.php';
        //consulta SQL
        $sql = "select count(1) as Existe
                   from usuarios where Username = '".$username."'  and estado = 1;";
        $consulta = mysqli_query($db, $sql);
        $resp = mysqli_fetch_assoc($consulta);
        if($resp['Existe'] == 0){
            $sql1 = "insert into  usuarios (Username, nombre, edad, email, clave, telefono, create_time, estado)
                    values('$username', CONCAT('$nombre',' ','$apellido'), $edad, '$email', '$clave', '$Telefono', NOW(), 1);";
            //echo 'count:'.$sql1;
            $stmt = mysqli_query($db, $sql1);
            $count = mysqli_affected_rows($db);
            //var_dump($count);
            //if($stmt->row )
            //echo 'count:'.$count;
            if($count == 1){
                $respuesta = 'success';
            }else{
                $respuesta = 'No se pudo crear el registro';
            }
        }else{
            $respuesta = 'el Username ya existe';
        }
         return $respuesta;
        $resultado = mysqli_close($db);
        //echo $resultado;
    }catch(\Throwable $th){
        var_dump($th);
    }
}


function obtener_productos(){
    try{
        //importar las credenciales
        require './config/database.php';
        //consulta SQL
        $sql = "select * from productos where estado = 1;";
        //Realizar la consulta
        $consulta = mysqli_query($db, $sql);
        //acceder a los resultados
       // echo "<pre>";
       //   var_dump(mysqli_fetch_assoc($consulta));
        //echo "</pre>";
        //Cerrar la conexion (opcional)
         return $consulta;
        $resultado = mysqli_close($db);
        //echo $resultado;
    }catch(\Throwable $th){
        var_dump($th);
    }
}

function mostrar_productos($idProducto){
    try{
        //importar las credenciales
        require './config/database.php';
        //consulta SQL
        $sql = "select * from productos where id = '$idProducto' AND estado = 1;";
        //Realizar la consulta
        $consulta = mysqli_query($db, $sql);
        //acceder a los resultados
       // echo "<pre>";
       //   var_dump(mysqli_fetch_assoc($consulta));
        //echo "</pre>";
        //Cerrar la conexion (opcional)
         return $consulta;
        $resultado = mysqli_close($db);
        //echo $resultado;
    }catch(\Throwable $th){
        var_dump($th);
    }
}

function AgregarTarjeta($usuario, $numTarjeta, $fechaExp, $nombre, $cvv){
    try{
        //importar las credenciales
        require './config/database.php';
        //preparar la consulta

        $sql = "insert into TarjetasCredito (Username, NumeroTarjeta, FechaExp, NombreTarjeta, cvv)
        values ('$usuario','$numTarjeta', '$fechaExp', '$nombre', '$cvv');";
        //enviar la consulta

        $consulta = mysqli_query($db, $sql);


        $resultado = mysqli_close($db);

        if ($consulta) {
            return true;
        } else {
            return false;
        }
       
    }catch(\Throwable $th){
        var_dump($th);
    }
}

function orden($id) {
    try {
        // Importar las credenciales
        require './config/database.php';
        
        // Consulta SQL para obtener el producto por ID
        $sql = "select * from productos where id = '$id' AND estado = 1;";
        
        // Realizar la consulta
        $consulta = mysqli_query($db, $sql);
        
        // Retornar el producto
        return mysqli_fetch_assoc($consulta);
        
        // Cerrar la conexión (opcional)
        mysqli_close($db);
    } catch (\Throwable $th) {
        var_dump($th);
    }
}


?>