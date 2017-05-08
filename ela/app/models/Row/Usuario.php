<?php

class Model_Row_Usuario extends Model_Row_Abstract {


	Public Function getTableros(){
		 return $this->findManytoManyRowset('Model_Tableros', 'Model_UsuariosTableros');

	}


}
