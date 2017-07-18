<?php

class ComprasController extends Zend_Controller_Action {

    public function indexAction() {
        $usuario = Zend_Registry::get('Usuario');

        $where = [];
        if ($usuario->getInstitucionId() != null) {
            $where = ['institucion_id = ?' => $usuario->getInstitucionId()];
        }

        $this->view->assign("listado", Model_Compras::getSingleton()->fetchAll($where));
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

    public function carritoAction() {
        $carrito = $this->_getCarrito();

        // Obtengo las licencias
        $licencias = Model_TiposLicencias::getSingleton()->fetchAll(null, 'precio');

        if ($this->getRequest()->isPost()) {

            if ($carrito->getId() <= 0)
                $carrito->save();

            $detalle = $this->getRequest()->getPost('detalle');
            $licencia = null;
            foreach ($detalle as $id_licencia => $cantidad_ingresada) {
                // Si ingreso 0, borro el detalle
                if ($cantidad_ingresada == 0) {
                    Model_ComprasDetalles::getSingleton()
                            ->delete(
                                    // WHERE
                                    [
                                        'compra_id = ?' => $carrito->getId(),
                                        'tipo_lic_id = ?' => $id_licencia,
                                    ]
                    );
                    continue; // foreach de detalle continue
                }

                foreach ($licencias as $licencia_foreach) {
                    if ($licencia_foreach->getId() == $id_licencia) {
                        $licencia = $licencia_foreach;
                        break; // Corte del foreach
                    }
                }
                try {
                    // Intento insertar la fila
                    Model_ComprasDetalles::getSingleton()
                            ->insert(
                                    // DATOS
                                    [
                                        'cantidad' => $cantidad_ingresada,
                                        'compra_id' => $carrito->getId(),
                                        'tipo_lic_id' => $id_licencia,
                                        'precio' => $licencia->getPrecio()
                                    ]
                    );
                } catch (Exception $ex) {
                    // Si la clave unica (compra_id, tipo_lic_id) ya existe
                    // actualizo los dato
                    Model_ComprasDetalles::getSingleton()
                            ->update(
                                    // Datos
                                    [
                                'cantidad' => $cantidad_ingresada,
                                'precio' => $licencia->getPrecio()
                                    ],
                                    // WHERE
                                    [
                                'compra_id = ?' => $carrito->getId(),
                                'tipo_lic_id = ?' => $id_licencia,
                                    ]
                    );
                }
            }

            $this->getHelper('FlashMessenger')->addMessage('Carrito actualizado');
            $url = $this->view->url(array('id' => null, 'action' => 'carrito'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
        }

        $this->view->assign("carrito", $carrito);
        $this->view->assign("licencias", $licencias);
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

//    public function quitarAction() {
//        $carrito = $this->_getCarrito();
//
//        $id = $this->getRequest()->getParam('id');
//
//        Model_ComprasDetalles::getSingleton()
//                ->delete([
//                    'id = ?' => $id,
//                    'compra_id = ?' => $carrito->getId()
//        ]);
//
//        $this->getHelper('FlashMessenger')->addMessage('Detalle borrado');
//        $url = $this->view->url(array('id' => null, 'action' => 'carrito'));
//        $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
//    }

    /**
     * 
     * @return Model_Row_Compra
     */
    private function _getCarrito() {
        $usuario = Zend_Registry::get('Usuario');

        $where = [
            'borrador = 1',
            'institucion_id = ?' => $usuario->getInstitucionId()
        ];

        $carrito = Model_Compras::getSingleton()->fetchRow($where);

        if ($carrito == null) {
            $carrito = Model_Compras::getSingleton()->createRow([
                'institucion_id' => $usuario->getInstitucionId(),
                'usuario_id' => $usuario->getId()
            ]);
        }

        return $carrito;
    }

}
