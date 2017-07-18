<?php

Class Model_ComprasDetalles extends Model_Abstract {

    //Nombre de la Tabla en la BD
    Protected $_name = 'compras_detalles';
    //Nombre de cada item que devuelve c/u de las consultas
    Protected $_rowClass = 'Model_Row_CompraDetalle';
    Protected $_referenceMap = array(
        'Compra' => array(
            'columns' => array('compra_id'),
            'refTableClass' => 'Model_Compras',
            'refColumns' => array('id')
        ),
        'TipoLicencia' => array(
            'columns' => array('tipo_lic_id'),
            'refTableClass' => 'Model_TiposLicencias',
            'refColumns' => array('id')
        ),
    );

}
