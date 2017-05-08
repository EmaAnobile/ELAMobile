<?php

class Model_Row_Tablero extends Model_Row_Abstract {

	Public function __toString(){   
		//return 'Tablero: ' . $this->getCodigo();
	
        $html = '';
        
        foreach ($this->getGrupos() as $grupos) {
        	
        	$html.= (string)$grupos;

        }
	     
	     return $html;
	}



	Public Function getUsuarios(){
		 return $this->findManytoManyRowset('Model_Usuarios', 'Model_UsuariosTableros');

	}

	Public Function getGrupos(){
		 return $this->findManytoManyRowset('Model_Grupos', 'Model_TablerosGrupos');

	}

}
