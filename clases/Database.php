<?php

class Database{
  //Variables de conexion
  private $host = "localhost";
  private $dbName = "persona_crud";
  private $username = "root";
  private $password = "";

  public $conn;

  //Metodo para conectar a la DB
  public function dbConnection(){
    //inicializamos la variable de conexion
    $this->conn = null;
    try {
      //Se crea la conexion con PDO a la base de datos
      $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->dbName, $this->username, $this->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (PDOException $exception) {
      echo "Connection error: ".$exception->getMessage();
    }
    return $this->conn;

  }
}