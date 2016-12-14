<?php

session_start();

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

  $query = "SELECT * FROM usuarios WHERE nombre='".$_POST['iduser']."' AND clave='".$_POST['idpass']."'";
  


	$_SESSION['id_usuario'] =-1;
	$_SESSION['datos_usuario'] =array();

  //$query = "SELECT * FROM USUARIOS WHERE desc_nombre='".$_POST['iduser']."' AND clave='".$_POST['idpass']."'";

//Si realizo la consulta.
if($res = $con->query($query) ){

	//Obtenemos registro leido.
	 if($row = $res->get_row()){

// Guardamos ID de usuario para la sesión	 	
	 	$_SESSION['id_usuario'] = $row['id_usuario'];
		
// Guardamos Datos de usuario para la sesión		
		$_SESSION['datos_usuario'] = $row;
 	  
 	    $resultado = array('resultado' => 'OK', 'msg' => '', 'desc_usuario' => $row['nombre']); //Nombre de campo en BD  

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