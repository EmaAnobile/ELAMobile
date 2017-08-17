<?php

Class Model_Roles extends Model_Abstract {

    //Nombre de la Tabla en la BD
    Protected $_name = 'roles';
    //Nombre de cada item que devuelve c/u de las consultas
    Protected $_rowClass = 'Model_Row_Rol';

    public function getRol($rol_nombre) {
        return $this->fetchRow(['nombre = ?' => $rol_nombre]);
    }
}
