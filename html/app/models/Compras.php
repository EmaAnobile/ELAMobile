<?php

Class Model_Compras extends Model_Abstract {

    //Nombre de la Tabla en la BD
    Protected $_name = 'compras';
    //Nombre de cada item que devuelve c/u de las consultas
    Protected $_rowClass = 'Model_Row_Compra';

    public function getRows() {
        $sql = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('main' => $this))
                ->joinLeft(
                        // alias => nombre tabla
                        array('i' => new Model_Instituciones()),
                        // Condicion del "ON"
                        'main.institucion_id = i.id',
                        // Campos a obtener de este join
                        array('razon_social'))
        ;
        return $this->fetchAll($sql);
    }

}
