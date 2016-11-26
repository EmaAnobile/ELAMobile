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

 //$query = "SELECT * FROM abecedario WHERE id_grupo =".$_POST['idboton'];

   $query =  "SELECT DISTINCT sg.id_subgrupo, sg.id_letra, sg.letra FROM subgrupo AS sg INNER JOIN grupo AS g 
              ON ( sg.id_subgrupo = g.id_subgrupo ) INNER JOIN tablero as t ON t.id_grupo = g.id_grupo  
              WHERE t.id_tablero =".$_POST['idtablero']." AND sg.id_subgrupo=".$_POST['idboton'];



 $letras = array();

//Si realizo la consulta.
if($res = $con->query($query) ){

	//Obtenemos registro leido.
	 while($row = $res->get_row()){
       
      array_push($letras, $row);

	 }

 	  $resultado = array('resultado' => 'OK', 'msg' => '', 'letras' => $letras); //Nombre de campo en BD 

 }
else{
  
  $resultado = array('resultado' => 'NOK', 'msg' => 'Error al intentar realizar la consulta');

}

echo json_encode($resultado);

exit;