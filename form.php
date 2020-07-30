<?php

//se hace la importación de la clase Persona
require_once 'clases/Persona.php';
//Se crea una instacia nueva de la clase persona
$persona = new Persona();

// Verificamos que por el metodo get estemos recibiendo un parametro denominad edit_id
if(isset($_GET['edit_id'])){
  //si se recibimos el paramatro lo asignamos a una variable para poder manejarlo
  $id = $_GET['edit_id'];
  //Preparamos el query para buscar el registro con el id correspondiente, mismo que obtuvimos por el metodo Get
  $stmt = $persona->runQuery("SELECT * FROM persona WHERE Id=:id");
  //Ejecutamos el query
  $stmt->execute(array(":id" => $id));
  //Asignamos el resultado del query a la variable $rowPersona para despues mostrar los resultados
  $rowPersona= $stmt->fetch(PDO::FETCH_ASSOC);
}else{
  //Si no tenemos un parametro edit_id asignamos null al $id y a $rowPersona para saber que no vamos a editar sino a guardar un registro nuevo.
  $id = null;
  $rowPersona = null;
}

// Verificamos el metodo post enviado desde el formulario
if(isset($_POST['btn_save'])){
  //Si este existe se asignan a variables correspondientes lo que el formulario contiene la funcion strio_tags escapa los tags html o php de un string
  $nombre = strip_tags($_POST['Nombre']);
  $ape_pat = strip_tags($_POST['Ape_Pat']);
  $ape_mat = strip_tags($_POST['Ape_Mat']);
  $rfc = strip_tags($_POST['RFC']);
  $curp = strip_tags($_POST['CURP']);
  $fecha_nacimiento = strip_tags($_POST['Fecha_Nacimiento']);
  $sexo = strip_tags($_POST['Sexo_Id']);
  $tipo = strip_tags($_POST['Persona_Tipo_Id']);
  $avatar = $_FILES['Avatar']['name'];
  
  try {
    //Verificamos si tenemos almacena una variable $id y su valor es diferente de nullo
    if($id != null){
      //De pasar esta validacion, quiere decir que vamos a editar un registro existente llamando al metodo update y pasando como parametros las variables correspondientes
      if($persona->update($nombre,$ape_pat,$ape_mat,$id)){
        //De ser exitosa la insercion , hacemos una redireccion a la pagina principal, con la ayuda de nuestra funcion redirect
        $persona->redirect('index.php');
      }else{
        //De lo contrario mandamos un mensaje de error
        echo "No se ha podido almacenar el registro";
        $persona->redirect('index.php');
      }
    }else{
      //Si la validacion del $id no pasa quiere decir que es null por lo tanto se almacenara un nuveo registro dentro de la tabla
      //Se llama al metodo insert que es el encargado de almacenar el nuevo registro dentro de la tabla
      if($persona->insert($nombre,$ape_pat,$ape_mat,$rfc,$curp,$fecha_nacimiento,$sexo,$tipo,$avatar)){
        ///De ser exitosa la insercion , hacemos una redireccion a la pagina principal, con la ayuda de nuestra funcion redirect
        $persona->redirect('index.php');
      }else{
        //De lo contrario mandamos un mensaje de error y se redirecciona la pagina principal
         echo "No se ha podido almacenar el registro";
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
        <?php require_once 'includes/header.php'; ?>
    </head>
    <body>
        <!-- El código siguiente es para mostrar el formulario de creacion y edición del registro, los inputs tienen en su atributo value el valor de lo que en la tabla contenga el registro si es que se edita el registro  -->
        <div class="container-fluid">
            <div class="row">
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                  <h1 style="margin-top:10px">Agregar / Editar Persona</h1>
                  <form method="post" enctype='multipart/form-data'>
                    <div class="form-group">
                      <label for="id">ID</label>
                      <input class="form-control" type="text" name="id" id="id" readonly disabled value="<?php print($rowPersona['Id']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Nombre">Nombre</label>
                      <input class="form-control" type="text" name="Nombre" id="nombre" value="<?php print($rowPersona['Nombre']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Ape_Pat">Apellido Paterno</label>
                      <input class="form-control" type="text" name="Ape_Pat" id="Ape_Pat"  value="<?php print($rowPersona['Ape_Pat']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Ape_Mat">Apellido Materno</label>
                      <input class="form-control" type="text" name="Ape_Mat" id="Ape_Mat"  value="<?php print($rowPersona['Ape_Mat']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="RFC">RFC</label>
                      <input class="form-control" type="text" name="RFC" id="RFC"  value="<?php print($rowPersona['RFC']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="CURP">CURP</label>
                      <input class="form-control" type="text" name="CURP" id="CURP"  value="<?php print($rowPersona['CURP']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Fecha_Nacimiento">Fecha Nacimiento</label>
                      <input class="form-control" type="date" name="Fecha_Nacimiento" id="Fecha_Nacimiento"  value="<?php print($rowPersona['Fecha_Nacimiento']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Sexo_Id">Sexo</label>
                      <input class="form-control" type="number" name="Sexo_Id" id="Sexo_Id"  value="<?php print($rowPersona['Sexo_Id']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Persona_Tipo_Id">Persona tipo</label>
                      <input class="form-control" type="number" name="Persona_Tipo_Id" id="Persona_Tipo_Id"  value="<?php print($rowPersona['Persona_Tipo_Id']); ?>">
                    </div>
                    <div class="form-group">
                      <label for="Avatar">Avatar</label>
                      <input class="form-control" type="file" name="Avatar" id="Avatar"  value="<?php print($rowPersona['Avatar']); ?>">
                    </div>
                    <input type="submit" name="btn_save" value="Guardar" class="btn btn-primary mb-2">
                  </form>
                </main>
            </div>
        </div>
    </body>
</html>
