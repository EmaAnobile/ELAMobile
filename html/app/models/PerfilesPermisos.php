<?php

class Model_PerfilesPermisos extends Model_Abstract {

    Protected $_name = 'perfiles_permisos';
    Protected $_rowClass = 'Model_Row_PerfilPermiso';
    protected $_referenceMap = array(
        'Perfil' => array(
            'columns' => array('perfil_id'), // Columna en ESTE model
            'refTableClass' => 'Model_Perfiles', // Model externo
            'refColumns' => array('id') // Columna de model externo
        ),
    );

}
