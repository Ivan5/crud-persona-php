<?php

require_once "Database.php";

class Persona{
  //declaracion de la variable de conexion
  private $conn;

  //Definimos el constructor de la clase
  public function __construct()
  {
    //se hace una instacia de la clase Database
    $database = new Database();
    //se asigna a la variable conexion lo que la funcion de conexion retornaa
    $this->conn = $database->dbConnection();
  }

  //Definimos el metodo para la preparaacion de la ejecucion de los querys
  public function runQuery($sql){
    $stmt = $this->conn->prepare($sql);
    return $stmt;
  }

  //Insertar un registro en la BD
  public function insert($nombre,$ape_pat,$ape_mat,$rfc,$curp,$fecha_nacimiento,$sexo,$tipo,$avatar){
    try {
      //Se prepara el Query para poder ser ejecutado
      $stmt = $this->runQuery("INSERT INTO persona (Nombre,Ape_Pat,Ape_Mat,RFC,CURP,Fecha_Nacimiento,Fecha_Alta,Estatus_id,Sexo_id,Persona_Tipo_Id,Avatar) VALUES (:nombre,:paterno,:materno,:rfc,:curp,:nacimiento,:alta,:estatus,:sexo,:tipo,:avatar)");
      //se asigna el valor a las referencias
      $stmt->bindParam(":nombre",$nombre);
      $stmt->bindParam(":paterno",$ape_pat);
      $stmt->bindParam(":materno",$ape_mat);
      $stmt->bindParam(":rfc",$rfc);
      $stmt->bindParam(":curp",$curp);
      $stmt->bindParam(":nacimiento",$fecha_nacimiento);
      //Se crea la fecha actual para asignarla al campo para saber cuando se dio el alta del registro
      $fecha = new DateTime();
      $alta = $fecha->format('Y-m-d H:i:s');
      $stmt->bindParam(":alta",$alta);
      $estatus = 1;
      $stmt->bindParam(":estatus",$estatus);
      $stmt->bindParam(":sexo",$sexo);
      $stmt->bindParam(":tipo",$tipo);
      //se verifica que haya un avatar
      if(isset($_FILES[$avatar])){
        $target = '/images/'. pathinfo($_FILES[$avatar]['name']);
       
        move_uploaded_file( $_FILES[$avatar]['name'], $target);
      }
      $stmt->bindParam(":avatar",$avatar);
      
      //Se ejecuta el query
      $stmt->execute();
      return $stmt;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  //El metodo update nos sirve para poder actualizar un registro en especifico dado por el $id
  public function update($nombre,$ape_pat,$ape_mat,$id){
    try {
      //Se prepara el query con su respectiva sentencia
      $stmt = $this->conn->prepare("UPDATE persona SET Nombre = :nombre, Ape_Pat =:paterno, Ape_Mat=:materno, Fecha_Modificacion = :modificacion WHERE Id = :id");
     
      //Se asignan los parametros con su respectivo valor
      $stmt->bindParam(":nombre",$nombre);
      $stmt->bindParam(":paterno",$ape_pat);
      $stmt->bindParam(":materno",$ape_mat);
      //se crea la fecha actual para asignarla al registro en la fecha de modificación del registro
      $fecha = new DateTime();
      $modificacion = $fecha->format('Y-m-d H:i:s');
      $stmt->bindParam(":modificacion",$modificacion);

      $stmt->bindParam(":id",$id);
      //Se ejecuta el query
      $stmt->execute();
      return $stmt;

    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  //El metodo delete sirve para elimnar un registro de la tabla siempre y cuando coincida con el id que se nos proporciona
  public function delete($id){
      try {
        //Buscamos el registro con el id correspondiente
        $stmt = $this->conn->prepare("DELETE FROM persona WHERE Id = :id");
        //vinculamos el parametro id con su valor
        $stmt->bindparam(":id",$id);
        //Ejecutamos el query
        $stmt->execute();
        //retornamos la respuesta de la ejecución
        return $stmt;
      } catch (PDOException $e) {
        echo $e->getMessage();
      }

  }


  //El metodo redirec nos sirve para hacer un manejo de la redireccion a cierta url mandada como parametro
  public function redirect($url){
      header("Location: $url");
    }
}