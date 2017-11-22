<?php

class ComprasController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $usuario = Zend_Registry::get('Usuario');

        $where = [];
        if ($usuario->getInstitucionId() != null) {
            $where = ['institucion_id = ?' => $usuario->getInstitucionId()];
            $this->view->assign('ocultar_institucion', true);
        }

        $this->view->assign("listado", Model_Compras::getSingleton()->getRows());
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

    public function verAction()
    {
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

            // Indica si estaba o no aprobado antes de confirmar
            $yaEstaba = $item->getAprobado() == 1;
            // Confirmar la compra y quitarla de borrador
            $item->setFromArray(array(
                'borrador' => 0,
                'aprobado' => $confirmando
            ))->save();

            if ($confirmando && $yaEstaba == false) {
                $db_licencias = new Model_InstitucionesLicencias();
                $db_licencias->generarLicencias($item);
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

    public function exitoAction()
    {
        $compra_id = $this->getRequest()->getParam('compra_id');

        /* @var $compra Model_Row_Compra */
        $compra = Model_Compras::getSingleton()
            ->find($compra_id)
            ->current();

        $this->view->assign('compra', $compra);
    }

    public function errorAction()
    {
        
    }

    public function notificacionAction()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);

        $compra_id = $this->getRequest()->getParam('compra_id');

        /* @var $compra Model_Row_Compra */
        $compra = Model_Compras::getSingleton()
            ->find($compra_id)
            ->current();

        $metodoPago = $this->_getInstanciaPago($compra);

        $aprobado = $metodoPago->notify($compra);

        if ($aprobado) {
            $compra->setFromArray([
                'aprobado' => 1
            ])->save();

            $db_licencias = new Model_InstitucionesLicencias();
            $db_licencias->generarLicencias($compra);
        }
    }

    public function pagarAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                /* @var $compra Model_Row_Compra */
                $compra = Model_Compras::getSingleton()
                    ->find($id)
                    ->current();

                if ($compra == null) {
                    $this->getHelper('FlashMessenger')->addMessage('danger|' . __('La compra no existe'));

                    $url = $this->view->url(array('action' => 'index', 'id' => null));
                    $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
                    return;
                }

                if ($compra->getAprobado() == 1) {
                    $this->getHelper('FlashMessenger')->addMessage('danger|' . __('La compra ya fue aprobada'));

                    $url = $this->view->url(array('action' => 'index', 'id' => null));
                    $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
                    return;
                }
            } else {
                try {
                    $compra = Model_Compras::getSingleton()->getCarrito();
                } catch (Exception $ex) {
                    $this->getHelper('FlashMessenger')->addMessage('danger|' . $ex->getMessage());
                    $url = $this->view->url(array('action' => 'index', 'controller' => 'compras'));
                    $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
                    return;
                }
            }

            $metodoPago = $this->_getInstanciaPago($compra);
            $view = $this->view;
            $metodoPago->setUrlOk($view->serverUrl($view->url(array('action' => 'exito', 'compra_id' => $compra->getId()))));
            $metodoPago->setUrlKo($view->serverUrl($view->url(array('action' => 'error', 'compra_id' => $compra->getId()))));
            $metodoPago->setUrlNotificacion($view->serverUrl($view->url(['action' => 'notificacion', 'compra_id' => $compra->getId()])));
            if ($metodoPago->isRedirect()) {
                header('location:' . $metodoPago->getRedirect());
                die();
            } else {
                die($metodoPago->getFormularioPago());
            }
        } catch (Exception $ex) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . $ex->getMessage());

            $url = $this->view->url(array('action' => 'index', 'id' => null));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }
    }

    private function _getInstanciaPago($compra)
    {
        // En la variable $clase vamos a tener el nombre del metodo de pago
        // Ej: MetodoPago_MercadoPago
        // Ej: MetodoPago_Paypal
        $metodoPago = $compra->getMetodoPago();
        
        $clase = 'MetodoPago_' . $metodoPago;

        if (!class_exists($clase))
            throw new Exception(__('Metodo de pago %s incorrecto', $metodoPago));

        /* @var $instancia Interface_IMetodoPagoFacade */
        $instancia = new $clase($compra);
        // Devolvemos la instancia
        return $instancia;
    }
//
//    public function usuarioAction() {
//        require_once 'MercadoPago/mercadopago.php';
//        $cfg = Zend_Registry::get('Zend_Config');
//        $mp = new MP($cfg['mp']['client_id'], $cfg['mp']['client_secret']);
//        if ($cfg['mp']['sandbox'])
//            $mp->sandbox_mode(true);
//
//        try {
//            $ut = $mp->post('/users/test_user', ['site_id' => 'MLA']);
//            Bootstrap::logVar([
//                'Email' => $ut['response']['email'],
//                'password' => $ut['response']['password'],
//            ]);
//            $ut = $mp->post('/users/test_user', ['site_id' => 'MLA']);
//            Bootstrap::logVar([
//                'Email' => $ut['response']['email'],
//                'password' => $ut['response']['password'],
//            ]);
//        } catch (Exception $ex) {
//            var_dump($ex->getMessage());
//        }
//        die();
//    }
}
