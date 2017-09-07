<?php

/**
 * @method string getRazonSocial()
 */
class Model_Row_InstitucionLicencia extends Model_Row_Abstract {

    public function getTipoLicencia() {
        return $this->findParentRow('Model_TiposLicencias');
    }

    public function getHashOculto() {
        return preg_replace('/^.*(.{4})$/', '*******$1', $this->getHash());
    }

    public function getUsuario() {
        return $this->findParentRow('Model_Usuarios');
    }

    public function getFechaVigencia() {
        $fecha = new Zend_Date($this->fecha_vigencia);

        return $fecha->toString(Zend_Date::DATE_LONG);
    }

}
