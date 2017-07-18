<?php

class Model_Usuarios extends Model_Abstract {

    protected $_rowClass = 'Model_Row_Usuario';
    protected $_name = 'usuarios';
    protected $_referenceMap = array(
        'Rol' => array(
            'columns' => array('rol_id'),
            'refTableClass' => 'Model_Roles',
            'refColumns' => array('id')
        ),
    );

}
