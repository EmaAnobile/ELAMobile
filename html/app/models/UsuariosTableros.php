<?php

class Model_UsuariosTableros extends Model_Abstract{
 
      Protected $_name     = 'usuarios_tableros';
    //Protected $_rowClass = 'Model_Row_Tablero';
 

 protected $_referenceMap = array(
       	'Tableros' => array(
            'columns'       => array('tablero_id'),
            'refTableClass' => 'Model_Tableros',
            'refColumns'    => array('id')
        ),

       	'Usuarios' => array(
            'columns'       => array('usuario_id'),
            'refTableClass' => 'Model_Usuarios',
            'refColumns'    => array('id')
        ),        
    );


}