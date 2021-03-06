<?php

class Model_Row_Usuario extends Model_Row_Abstract {

    Public Function getTableros() {

        if ($this->getId() > 0)
            return $this->findManytoManyRowset('Model_Tableros', 'Model_UsuariosTableros');

        return array();
    }

    Public Function getTablerosIds() {
        $ids = array();
        foreach ($this->getTableros() as $tablero) {
            $ids[] = $tablero->getId();
        }
        return $ids;
    }

    public function getNombreRol() {
        return $this->getRol()->getNombre();
    }

    public function getRol() {
        return $this->findParentRow('Model_Roles');
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

    public function getHashValidacion() {
        return md5($this->getUsuario() . '-' . $this->getPassword());
    }

    public function getTipoLicencia() {
        return $this->findParentRow('Model_TiposLicencias');
    }

    public function getFechaVigencia() {
        $fecha = new Zend_Date($this->fecha_vigencia);

        return $fecha->toString(Zend_Date::DATE_LONG);
    }

}
