<?php

/**
 */
class Model_Row_Perfil extends Model_Row_Abstract {

    function getPermisos() {
        if ($this->getId() == null)
            return array();
        return $this->findDependentRowset('Model_PerfilesPermisos');
    }
 /**
     * 
     * @return Model_Row_Rol[]
     */
    function getRoles() {
        return $this->findManyToManyRowset('Model_Roles', 'Model_RolesPerfiles');
    }
}
