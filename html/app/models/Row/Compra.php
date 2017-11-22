<?php

/**
 * @method string getId()
 */
class Model_Row_Compra extends Model_Row_Abstract {

    private $_detalles = null;
    private $_licencias = null;

    public function getPrecio() {
        $precio = 0;
        foreach ($this->getDetalles() as $detalle) {
            $precio += $detalle->getPrecioFinal();
        }

        return $precio;
    }

    /**
     * 
     * @return Model_Row_CompraDetalle[]
     */
    function getDetalles() {
        if ($this->getId() == null)
            return array();
        if ($this->_detalles === null) {
            $this->_detalles = $this->findDependentRowset('Model_ComprasDetalles');
        }

        return $this->_detalles;
    }

    /**
     * 
     * @return Model_Row_CompraDetalle[]
     */
    function getLicenciasGen() {
        if ($this->getId() == null)
            return array();
        if ($this->_licencias === null) {
            $this->_licencias = $this->findDependentRowset('Model_InstitucionesLicencias');
        }

        return $this->_licencias;
    }

    public function getCantidadLicencia($licencia) {
        foreach ($this->getDetalles() as $detalle) {
            if ($detalle->getTipoLicId() == $licencia->getId())
                return $detalle->getCantidad();
        }
        return 0;
    }

    public function getPrecioLicencia($licencia) {
        foreach ($this->getDetalles() as $detalle) {
            if ($detalle->getTipoLicId() == $licencia->getId())
                return $detalle->getCantidad() * $licencia->getPrecio();
        }
        return 0;
    }

    public function getCantidades() {
        if ($this->getId() == null)
            return 0;

        if ($this->_detalles === null) {
            $this->_detalles = $this->findDependentRowset('Model_ComprasDetalles');
        }

        $cant = 0;
        foreach ($this->getDetalles() as $detalle) {
            $cant += $detalle->getCantidad();
        }
        return $cant;
    }

}
