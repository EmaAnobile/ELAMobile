<?php

Class Model_InstitucionesLicencias extends Model_Abstract {

    //Nombre de la Tabla en la BD
    Protected $_name = 'instituciones_licencias';
    //Nombre de cada item que devuelve c/u de las consultas
    Protected $_rowClass = 'Model_Row_InstitucionLicencia';
    Protected $_referenceMap = array(
        'Compra' => array(
            'columns' => array('compra_id'),
            'refTableClass' => 'Model_Compras',
            'refColumns' => array('id')
        ),
        'Usuario' => array(
            'columns' => array('usuario_id'),
            'refTableClass' => 'Model_Usuarios',
            'refColumns' => array('id')
        ),
        'TipoLicencia' => array(
            'columns' => array('tipo_licencia_id'),
            'refTableClass' => 'Model_TiposLicencias',
            'refColumns' => array('id')
        ),
    );

    /**
     * 
     * @param type $institucion_id
     * @param type $tipoLicencia
     * @param type $cantidad
     * @return Model_Row_InstitucionLicencia[]
     * @throws Exception
     */
    public function getLicenciasDisponible($institucion_id, $tipoLicencia, $cantidad) {
        $sql = $this->select()
                ->where('usuario_id is null')
                ->where('tipo_licencia_id = ?', $tipoLicencia)
                ->where('institucion_id = ?', $institucion_id)
                ->limit($cantidad)
        ;

        $rowset = $this->fetchAll($sql);

        if ($rowset->count() < $cantidad) {
            throw new Exception(__('No hay disponible la cantidad requerida para el tipo de licencia seleccionado'));
        }

        return $rowset;
    }

    public function getTiposLicenciasDisponibles($institucion_id) {
        $sql = $this->select()
                ->from($this, array('*', 'cantidad' => 'COUNT(*)'))
                ->where('usuario_id is null')
                ->where('institucion_id = ?', $institucion_id)
                ->group('tipo_licencia_id');
        $retorno = array();
        foreach ($this->fetchAll($sql) as $item) {
            $retorno[] = array(
                'cantidad' => $item->getCantidad(),
                'id' => $item->getTipoLicenciaId(),
                'tipo_licencia' => $item->getTipoLicencia(),
            );
        }
        return $retorno;
    }

    public function generarLicencias($compra) {
        // Generar las licencias a la institucion
        foreach ($compra->getDetalles() as $detalle) {
            for ($i = 0; $i < $detalle->getCantidad(); $i++) {
                $licencia = array(
                    'institucion_id' => $compra->getInstitucionId(),
                    'tipo_licencia_id' => $detalle->getTipoLicId(),
                    'hash' => trim(com_create_guid(), '{}'),
                    'compra_id' => $compra->getId()
                );

                $this->createRow($licencia)->save();
            }
        }
    }

}
