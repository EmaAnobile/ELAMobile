<?php

class UsuariosController extends Zend_Controller_Action {

    public function accederAction() {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));

        if ($auth->hasIdentity()) {
            $this->_redirect($this->view->url(array(), 'default', true));
            return;
        }

        if ($this->getRequest()->isPost()) {
            $usuario = $this->getRequest()->getPost('usuario');
            $password = $this->getRequest()->getPost('password');
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();

//            $adapter = new Zend_Auth_Adapter_DbTable($db, 'usuarios', 'usuario', 'password', '?');
            $adapter = new Zend_Auth_Adapter_DbTable($db, (string) new Model_Usuarios(), 'usuario', 'password', '?');
            $adapter->setIdentity($usuario)->setCredential($password);
            $result = $auth->authenticate($adapter);
            $error = '';
            switch ($result->getCode()) {
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    $error = 'Usuario o password incorrecto';
                    break;

                case Zend_Auth_Result::SUCCESS:
                    $session = new Zend_Session_Namespace('backend');
                    $session->usuario = $adapter->getResultRowObject();

                    $this->getHelper('redirector')
                        ->gotoUrlAndExit($this->view->serverUrl($this->view->url(array())));
                    return;
                    break;
                }
            $this->view->assign("error_form", $error);
        }
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));
        $auth->clearIdentity();

        $url = $this->view->serverUrl( $this->view->url(array(),"default", true));

       $this->getHelper('redirector')
            ->gotoUrlAndExit($url );       

    }

}
