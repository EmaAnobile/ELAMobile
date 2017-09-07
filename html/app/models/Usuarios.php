<?php

class Model_Usuarios extends Model_Abstract {

    protected $_rowClass = 'Model_Row_Usuario';
    protected $_name = 'usuarios';
    protected $_referenceMap = array(
        'Rol' => array(
            'columns' => array('rol_id'),
            'refTableClass' => 'Model_Roles',
            'refColumns' => array('id')
        ),
        'TipoLicencia' => array(
            'columns' => array('tipo_licencia_id'),
            'refTableClass' => 'Model_TiposLicencias',
            'refColumns' => array('id')
        ),
    );

    public function getUsuariosAsignables($institucion_id, $fecha_limite) {
//        $sql = $this->select()
//                ->where('institucion_id = ?', $institucion_id)
//                ->where('tipo_licencia_id is null OR (tipo_licencia_id is not null AND fecha_vigencia < ?)', $fecha_limite->toString(Zend_Date::ISO_8601));

        return $this->fetchAll(array(
                    'institucion_id = ?' => $institucion_id,
                    'tipo_licencia_id is null OR fecha_vigencia < ?' => $fecha_limite->toString(Zend_Date::ISO_8601),
        ));
    }

    public function getUsuariosAsignados($institucion_id, $fecha_limite) {
//        $sql = $this->select()
//                ->where('institucion_id = ?', $institucion_id)
//                ->where('tipo_licencia_id is null OR (tipo_licencia_id is not null AND fecha_vigencia < ?)', $fecha_limite->toString(Zend_Date::ISO_8601));

        return $this->fetchAll(array(
                    'institucion_id = ?' => $institucion_id,
                    'tipo_licencia_id is not null AND fecha_vigencia >= ?' => $fecha_limite->toString(Zend_Date::ISO_8601),
        ));
    }

}
