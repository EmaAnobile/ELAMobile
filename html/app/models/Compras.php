<?php

Class Model_Compras extends Model_Abstract
{

    //Nombre de la Tabla en la BD
    Protected $_name = 'compras';
    //Nombre de cada item que devuelve c/u de las consultas
    Protected $_rowClass = 'Model_Row_Compra';

    public function getRows()
    {
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

    /**
     * 
     * @return Model_Row_Compra
     */
    public function getCarrito()
    {
        $usuario = Zend_Registry::get('Usuario');

        if (!$usuario->getInstitucionId()) {
            throw new Exception(__('Usted no tiene permitido comprar'));
        }

        $where = [
            'borrador = 1',
            'institucion_id = ?' => $usuario->getInstitucionId()
        ];

        $carrito = $this->fetchRow($where);

        if ($carrito == null) {
            $carrito = $this->createRow([
                'institucion_id' => $usuario->getInstitucionId(),
                'usuario_id' => $usuario->getId(),
                // Esto deberia variar cuando el usuario pueda cambiar de metodo de pago
                'metodo_pago' => 'MercadoPago'
            ]);
        }

        return $carrito;
    }
}
