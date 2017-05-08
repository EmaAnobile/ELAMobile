<?php

class Back_AuthController extends Zend_Controller_Action {

    public function init() {
        $this->view->headTitle('Backend', Zend_View_Helper_Placeholder_Container_Abstract::SET)
            ->setSeparator(' - ');
        $this->getHelper('layout')->setLayout('login');
    }

    public function loginAction() {
        $this->view->headTitle('Entrar');

        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));

        if ($auth->hasIdentity()) {
            $this->_redirect($this->view->url(array(), 'back', true));
            return;
            //$identity = $this->_auth->getIdentity();
        }
        $form = new Back_Form_Auth();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $usuario = $form->usuario->getValue();
                $password = $form->password->getValue();
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();


                // oscar / 69a49eb7cb374ba81c01d29ca9a3d40d
//                if ($usuario == 'oscar')
//                    $adapter = new Zend_Auth_Adapter_DbTable($db, (string) new Model_Usuarios(), 'usu_usuario', 'usu_password', '?');
//                else
                $adapter = new Zend_Auth_Adapter_DbTable($db, (string) new Model_Usuarios(), 'usu_usuario', 'usu_password', 'MD5(?)');
//                $adapter = new Zend_Auth_Adapter_DbTable($db, (string) new Model_Usuarios(), 'usu_usuario', 'usu_password', '?');
                $adapter->setIdentity($usuario)->setCredential($password);
                $result = $auth->authenticate($adapter);
                $errors = array();
                switch ($result->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        $form->password->addError('Usuario o password incorrecto');
                        break;

                    case Zend_Auth_Result::SUCCESS:
                        $session = new Zend_Session_Namespace('backend');
                        $session->usuario = $adapter->getResultRowObject();

                        if ($session->usuario->usu_role === 'admin') {
                            $this->getHelper('redirector')
                                ->gotoUrlAndExit($this->view->url(array(), 'back'));
                        } else {
                            $this->getHelper('redirector')
                                ->gotoUrlAndExit($this->view->url(array(
                                        'action' => 'mis-datos',
                                        'controller' => 'auth'
                                        ), 'back'));
                        }
                        return;
                        break;
                }
            }
        }

        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
        $this->view->assign('form', $form);
    }

    public function logoutAction() {
        $this->view->headTitle('Salir');

        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));
        $auth->clearIdentity();
    }

    public function restaurarPasswordAction() {
        $this->view->headTitle('Recuperar password');

        $form = new Back_Form_RecuperarPassword();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $db = new Model_Usuarios();

                $vals = $form->getValues();
                $usu = $db->fetchRow(array(
                    'usu_usuario = ?' => $vals['usuario']
                ));
                if ($usu === null) {
                    $form->getElement('usuario')->addError('Usuario no encontrado');
                } else {
                    $pass = (string) new Service_Password();
                    $usu->usu_password = md5($pass);

                    $mail = new Zend_Mail();
                    $mail->setFrom(Service_Config::get('mail_envio')->getActivo(), 'CharterPesca.es')
                        ->addTo($usu->usu_email)
                        ->setBodyText('La password es: ' . $pass)
                        ->send();

                    $usu->save();
                    $this->getHelper('FlashMessenger')->addMessage(__('Nuevo password enviado'));

                    $this->getHelper('redirector')->gotoUrlAndExit($this->view->url(array('action' => 'login')));
                    return;
                }
            }
        }

        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
        $this->view->assign('form', $form);
    }

    public function misDatosAction() {
        $sess = new Zend_Session_Namespace('backend');
        $this->getRequest()->setParam('id', $sess->usuario->usu_id);
        $this->_forward('editar', 'usuarios');
    }

}
