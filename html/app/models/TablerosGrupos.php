<?php

class Model_TablerosGrupos extends Model_Abstract {

    Protected $_name = 'tableros_grupos';
    //Protected $_rowClass = 'Model_Row_Tablero';

    protected $_referenceMap = array(
        'Tableros' => array(
            'columns' => array('tablero_id'),
            'refTableClass' => 'Model_Tableros',
            'refColumns' => array('id')
        ),
        'Grupos' => array(
            'columns' => array('grupo_id'),
            'refTableClass' => 'Model_Grupos',
            'refColumns' => array('id')
        ),
    );

}
