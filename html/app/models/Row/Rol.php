<?php

/**
 * @method string getNombre()
 */
class Model_Row_Rol extends Model_Row_Abstract {

    function getPermisos() {
        $permisos = array();

        foreach ($this->getPerfiles() as $perfil) {
            foreach ($perfil->getPermisos() as $permiso) {
                $permisos[] = $permiso;
            }
        }

        return $permisos;
    }

    /**
     * 
     * @return Model_Row_Perfil[]
     */
    function getPerfiles() {
        return $this->findManyToManyRowset('Model_Perfiles', 'Model_RolesPerfiles');
    }

    /**
     * 
     * @return Model_Row_Usuario[]
     */
    function getUsuarios() {
        if ($this->getId() == null)
            return array();
        return $this->findDependentRowset('Model_Usuarios');
    }
    
}
