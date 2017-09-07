<?php

/**
 * @method string getId()
 * @method float getPrecio()
 * @method int getCantidad()
 */
class Model_Row_CompraDetalle extends Model_Row_Abstract {

    private $_tipo_licencia = null;

    public function getPrecioFinal() {
        return $this->getPrecio() * $this->getCantidad();
    }

    public function getPrecioFinalCalculado() {
        return $this->getTipoLicencia()->getPrecio() * $this->getCantidad();
    }

    /**
     * 
     * @return Model_Row_TipoLicencia
     */
    public function getTipoLicencia() {
        if ($this->_tipo_licencia === null) {
            $this->_tipo_licencia = $this->findParentRow('Model_TiposLicencias');
        }
        return $this->_tipo_licencia;
    }

}
