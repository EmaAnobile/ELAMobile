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

$query = "SELECT * FROM abecedario WHERE id_grupo =".$_POST['idboton']." AND id_letra=".$_POST['idletra'];

//Si realizo la consulta.
if($res = $con->query($query) ){

	//Obtenemos registro leido.
	 if($row = $res->get_row()){

 	  $resultado = array('resultado' => 'OK', 'msg' => '', 'letra' => $row['letra']); //Nombre de campo en BD  

	}
	 else {

	  $resultado = array('resultado' => 'NOK', 'msg' => 'No existen registros');


	 }

 }
else{
  
  $resultado = array('resultado' => 'NOK', 'msg' => 'Error al intentar realizar la consulta');

}

echo json_encode($resultado);

exit; 
/*
$telefonos[0] = array("Car" => "0336", "nro" => "232434");
$telefonos[1] = array("Car" => "0333", "nro" => "22323434");

$p = array("nombre" => "guillermo",
		   "apellido" => "guardia",
		   "telefonos" => $telefonos);

echo json_encode($p["telefonos"]);
exit;

/*

/*
$q = "SELECT * FROM abecedario";
if ($r = $con->query($q)) {
	while ($f = $r->get_row()) {
		echo "id Grupo : ".$f["id_grupo"]." - id letra : ".$f["id_letra"]." - letra : ".$f["letra"]."<br>";
	}
}
*/
$con->desconectar();

?>