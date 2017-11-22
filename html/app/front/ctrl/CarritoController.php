<?php

class CarritoController extends Zend_Controller_Action
{
    public function indexAction()
    {
        try {
            $carrito = Model_Compras::getSingleton()->getCarrito();
        } catch (Exception $ex) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . $ex->getMessage());
            $url = $this->view->url(array('action' => 'index', 'controller' => 'compras'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }

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
            $url = $this->view->url(array('id' => null, 'action' => 'index', 'controller' => 'compras'));
            if ($continuar !== false) {
                if ($carrito->getCantidades() == 0) {
                    $this->getHelper('FlashMessenger')->addMessage('danger|Se debe agregar al menos una licencia');
                } else {
                    $url = $this->view->url(array('action' => 'pagar', 'controller' => 'compras'));
                }
            }

            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
        }

        $this->view->assign("carrito", $carrito);
        $this->view->assign("licencias", $licencias);
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }
}
