<?php

class Model_Row_Grupo extends Model_Row_Abstract {


	Public function __toString(){   
	
        $html = '<div class="col-sm-4">';
        $html .= '<div id="boton-'.$this->getId().'" class="Boton_select boton_'.$this->getColor().' h3">';
        $html .= '<p >';

        foreach ($this->getSubGrupos() as $subgrupos) {
        	
        	$html.= (string)$subgrupos;

        }
        $html .= '</p>';
        $html .= '</div>';
        $html .= '</div>';

	     return $html;
	}	  

	/**
	 * Devuelve los subgrupos del grupo actual
	 *
	 * @return Model_Row_SubGrupo[]
	*/
	Public Function getSubGrupos(){
		 return $this->findDependentRowset('Model_SubGrupos');

	} 


}