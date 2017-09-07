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
            $continuar = $this->getRequest()->getPost('continuar', false);
            $this->getHelper('FlashMessenger')->addMessage('Carrito actualizado');
            $url = $this->view->url(array('id' => null, 'action' => 'carrito'));
            if ($continuar !== false) {
                $url = $this->view->url(array('action' => 'pagar'));
            }

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
        $usuario = Zend_Registry::get('Usuario');
        $puedeConfirmar = $usuario->getInstitucionId() <= 0;

        $id = $this->getRequest()->getParam("id");
        $confirmando = false !== $this->getRequest()->getParam("confirmar", false);
        $rechazando = false !== $this->getRequest()->getParam("rechazar", false);
        /* @var $item Model_Row_Compra */
        $item = Model_Compras::getSingleton()->find($id)->current();

        if ($this->getRequest()->isPost() && $puedeConfirmar) {
            // Si es borrador generamos las licencias.
            if ($item->getBorrador() == 0) {
                $this->getHelper('FlashMessenger')->addMessage('danger|' . __('La compra ya fue revisada'));

                $url = $this->view->url(array('action' => 'index', 'id' => null));
                $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));

                return;
            }
            // Confirmar la compra y quitarla de borrador
            $item->setFromArray(array(
                'borrador' => 0,
                'aprobado' => $confirmando
            ))->save();
            if ($confirmando) {

                $db_licencias = new Model_InstitucionesLicencias();

                // Generar las licencias a la institucion
                foreach ($item->getDetalles() as $detalle) {

                    for ($i = 0; $i < $detalle->getCantidad(); $i++) {
                        $licencia = array(
                            'institucion_id' => $item->getInstitucionId(),
                            'tipo_licencia_id' => $detalle->getTipoLicId(),
                            'hash' => trim(com_create_guid(), '{}'),
                            'compra_id' => $item->getId()
                        );

//                        var_dump($licencia);
//                        die();

                        $db_licencias->createRow($licencia)->save();
                    }
                }
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
        $this->view->assign("puedeConfirmar", $puedeConfirmar);
        $this->view->assign("confirmando", $confirmando);
        $this->view->assign("rechazando", $rechazando);
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

    public function pagarAction() {
        require_once 'MercadoPago/mercadopago.php';
        $cfg = Zend_Registry::get('Zend_Config');
        $mp = new MP($cfg['mp']['client_id'], $cfg['mp']['client_secret']);

        $carrito = $this->_getCarrito();

        $items = array();
        foreach ($carrito->getDetalles() as $detalle) {
            if ($detalle->getCantidad() == 0)
                continue;

            $items[] = array(
                "title" => $detalle->getTipoLicencia()->getNombre(),
                "quantity" => $detalle->getCantidad(),
                "currency_id" => "ARS",
                "unit_price" => (float) $detalle->getTipoLicencia()->getPrecio()
            );
        }
        $preference_data = array(
            "items" => $items,
            'back_urls' => array(
                'success' => $this->view->serverUrl($this->view->url(array('action' => 'notificacion'))),
                'pending' => $this->view->serverUrl($this->view->url(array('action' => 'notificacion'))),
                'failure' => $this->view->serverUrl($this->view->url(array('action' => 'notificacion'))),
            ),
            'notification_url' => $this->view->serverUrl($this->view->url(array('action' => 'notificacion')))
        );
        $preference = $mp->create_preference($preference_data);
        $carrito->setFromArray(array(
            'mp_id' => $preference['response']['id'],
            'borrador' => 0
        ))->save();

        $url = $preference['response']['sandbox_init_point'];
        header('location:' . $url);
        die();
    }

}
