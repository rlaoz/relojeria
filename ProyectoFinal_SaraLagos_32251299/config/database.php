<?php
    $db = mysqli_connect('localhost:3306' , 'root' , 'Lissy_root', 'manjares_de_honduras');

    if(!$db){
        echo 'Error de conexion a base de datos <br>';
    }else{
       // echo 'Conexion a base de datos exitosa<br>';
    }
?>