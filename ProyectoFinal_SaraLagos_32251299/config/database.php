<?php
    $db = mysqli_connect('localhost:3306' , 'root' , 'Jaehee1201', 'tiendareloj');

    if(!$db){
        echo 'Error de conexion a base de datos <br>';
    }else{
       // echo 'Conexion a base de datos exitosa<br>';
    }
?>