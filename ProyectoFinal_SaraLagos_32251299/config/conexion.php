<?php
   class Conexion extends PDO
   {
       private $hostBd = 'localhost';
       private $nombreBd = 'manjares_de_honduras';
       private $usuarioBd = 'root';
       private $passwordBd = '';
       private $puertoBd = '3306';
       public function __construct(){
          try{
              parent::__construct('mysql:host='.$this->hostBd . ';port='.$this->puertoBd . ';dbname=' . $this->nombreBd 
              .';charset=utf8', $this->usuarioBd, $this->passwordBd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
          }catch(PDOException $e){
              echo 'Error: ' . $e->getMessage();
              exit;
          }
       }
   }
?>