<?php

//Se importa la clase Persona
require_once 'clases/Persona.php';
//Se crea una nueva instacia de la clase Persona
$persona = new Persona();

//Verificamos si por el metodo get se recibe un parametro delete_id
if(isset($_GET['delete_id'])){
  //Si existe el parametro se le asigna a la variable $id
  $id=$_GET['delete_id'];
  try {
    //Si el $id no es nulo eso quiere decir que se va a elimar el registro correspondiente
    if($id != null){
      //Se llama al metodo delete y se le envia como parametro el la varaible $id
      if($persona->delete($id)){
        //si la eliminacion fue exitosa se hace un redireccion a la pagina principal
        $persona->redirect('index.php');
      }
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Head metas, css, and title -->
        <?php require_once 'includes/header.php'; ?>
    </head>
    <body>
         <div class="container mx-auto">
      <div class="row justify-content-center">
        
        <div class="col-md-8">
          <a class="btn btn-primary mt-5 mb-5" href="form.php">Agregar Persona</a>
          <table class="table">
            <thead>
              <th>#</th>
              <th>Nombre</th>
              <th>Apellido Paterno</th>
              <th>Apellido Materno</th>
              <th>Fecha de Nacimiento</th>
              <th>Acciones</th>
            </thead>
            <?php
              //Se leen los registros de la tabla persona
              $query = "SELECT * FROM persona";
              //se prepara el query
              $stmt = $persona->runQuery($query);
              //se ejecuta el query
              $stmt->execute();
            ?>
            <tbody>
              <?php
              //verificamos que haya mas de un registro
                if($stmt->rowCount() > 0){
                  //recoremos cada uno de los registros obtenidos
                  while($rowPersona = $stmt->fetch(PDO::FETCH_ASSOC)){
              ?>
                <tr>
                  <td>
                    <?php 
                      print($rowPersona['Id']);
                    ?>
                  </td>
                  <td>
                    <a href="form.php?edit_id=<?php print($rowPersona['Id']); ?>">
                      <?php print($rowPersona['Nombre']); ?>
                    </a>
                  </td>
                  <td>
                    <?php 
                      print($rowPersona['Ape_Pat']);
                    ?>
                  </td>
                  <td>
                    <?php 
                      print($rowPersona['Ape_Mat']);
                    ?>
                  </td>
                  <td>
                    <?php 
                      print($rowPersona['Fecha_Nacimiento']);
                    ?>
                  </td>
                  <td>
                    <a class="btn btn-danger" href="index.php?delete_id=<?php print($rowPersona['Id']);?>">
                        Eliminar
                    </a>
                  </td>
                </tr>
            </tbody>
            <?php }} ?>
          </table>
        </div>
      </div>
    </div>
    </body>
</html>