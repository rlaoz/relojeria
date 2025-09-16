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

function obtener_tablas() {
    // Lista de tablas que quieres mostrar
    $tablas = [
        "productos",
        "proveedores",
        "platos",
        "menu",
        "acompaniante",
        "acompaniante_plato",
        "menu_plato",
        "producto_acompaniante",
        "producto_proveedor",
        "tel_proveedor",
        "bitacora_menu"

    ];
    return $tablas;
}



?>