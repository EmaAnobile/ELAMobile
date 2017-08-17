<?php

class ComprasController extends Zend_Controller_Action {

    public function indexAction() {
        $usuario = Zend_Registry::get('Usuario');

        $where = [];
        if ($usuario->getInstitucionId() != null) {
            $where = ['institucion_id = ?' => $usuario->getInstitucionId()];
            $this->view->assign('ocultar_institucion', true);
        }

        $this->view->assign("listado", Model_Compras::getSingleton()->getRows());
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

    /**
     * 
     * @return Model_Row_Compra
     */
    private function _getCarrito() {
        $usuario = Zend_Registry::get('Usuario');

        if (!$usuario->getInstitucionId()) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . __('Usted no tiene permitido comprar'));
            $url = $this->view->url(array('action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
        }

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

    public function verAction() {
        $id = $this->getRequest()->getParam("id");
        $confirmando = false !== $this->getRequest()->getParam("confirmar", false);
        $rechazando = false !== $this->getRequest()->getParam("rechazar", false);

        $item = Model_Compras::getSingleton()->find($id)->current();

        if ($this->getRequest()->isPost()) {
            // Confirmar la compra y quitarla de borrador
            $item->setFromArray(array(
                'borrador' => 0,
                'aprobado' => $confirmando
            ))->save();
            if ($confirmando) {
                // Generar las licencias a la institucion
            }

            // Enviar aviso al usuario que realizo la compra sobre su aceptacion
            if ($confirmando) {
                // Mail satisfactorio
            } else {
                // Mail rechazatorio
            }
            $url = $this->view->url(array('action' => 'index', 'id' => null));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
        }

        $this->view->assign("item", $item);
        $this->view->assign("confirmando", $confirmando);
        $this->view->assign("rechazando", $rechazando);
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

}
