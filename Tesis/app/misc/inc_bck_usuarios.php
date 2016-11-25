<?php

include_once("Conexion_class.php");


$con = new Conexion();

/* Establecemos conexión*/
if (!$con->conectar()) {

//  echo "Error al coenctar!";
  
//Generamos un array para contemplar respuetas
  $resultado = array('resultado' => 'NOK', 'msg' => 'Error en la conexión');

  echo json_encode($resultado);
  
  exit;
 
 }

  $query = "SELECT * FROM USUARIOS WHERE desc_usuario =".$_POST['iduser']." AND clave=".$_POST['idpass'];


//Si realizo la consulta.
if($res = $con->query($query) ){

	//Obtenemos registro leido.
	 if($row = $res->get_row()){

 	  $resultado = array('resultado' => 'OK', 'msg' => '', 'desc_usuario' => $row['desc_usuario']); //Nombre de campo en BD  

	}
	 else {

	  $resultado = array('resultado' => 'NOK', 'msg' => 'Su usuario o su contraseña es incorrecta');


	 }

	}

else{
  
 $resultado = array('resultado' => 'NOK', 'msg' => 'Su usuario o su contraseña es incorrecta');

}

echo json_encode($resultado);

exit;