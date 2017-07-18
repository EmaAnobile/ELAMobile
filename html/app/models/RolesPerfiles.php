<?php

class Model_RolesPerfiles extends Model_Abstract {

    Protected $_name = 'roles_perfiles';
    //Protected $_rowClass = 'Model_Row_Tablero';

    protected $_referenceMap = array(
        'Rol' => array(
            'columns' => array('rol_id'),
            'refTableClass' => 'Model_Roles',
            'refColumns' => array('id')
        ),
        'Perfil' => array(
            'columns' => array('perfil_id'),
            'refTableClass' => 'Model_Perfiles',
            'refColumns' => array('id')
        ),
    );

}
