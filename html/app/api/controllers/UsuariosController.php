<?php

class Api_UsuariosController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->disableLayout();
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
        $this->getResponse()
                ->setHeader('Content-Type', 'application/json');
    }

    public function accederAction() {
        $auth = Zend_Auth::getInstance();

        // Colocamos nombre para almacenar la instancia de Sesion.        
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));

        //Si ya tiene una identidad lo redirecciona al Home
        //parametros por defecto, al default y con el true que reestablezca.
        if ($auth->hasIdentity()) {
            $this->getResponse()->setBody(Zend_Json::encode(array(
                        'info' => __('Ya se encuentra autenticado'),
                        'status' => Bootstrap::INFO_AUTENTICADO
            )));
            return;
        }

        if ($this->getRequest()->isPost()) {
            $usuario = $this->getRequest()->getPost('usuario');
            $password = $this->getRequest()->getPost('password');
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();

            //Consulta a la BD con el usuario y pass que se logeo.
            //Se utiliza un adapter que es el que espera el $Auth
            $dbUsuario = new Model_Usuarios();
            $adapter = new Zend_Auth_Adapter_DbTable($db, (string) $dbUsuario, 'usuario', 'password', '?');
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

                    // Validar vigencia
                    $usu = $adapter->getResultRowObject();
                    $usuario = $dbUsuario->fetchRow(array(
                        'id = ?' => $usu->id
                    ));
                    if ($usuario->getNombreRol() == 'Paciente') {
                        $ahora = new Zend_Date();
                        $vigencia = new Zend_Date($usuario->getFechaVigencia());
                        if ($ahora->compare($vigencia) !== -1) {
                            $auth->clearIdentity();

                            $this->getResponse()->setBody(Zend_Json::encode(array(
                                        'error' => __('Su licencia caduco. Comuniquese con su institucion.'),
                                        'status' => Bootstrap::ERROR_LICENCIA_CADUCADA
                            )));
                            return;
                        }
                    }

                    //Variable que se declara para luego tener los datos
                    // de los usuarios y no tener que volver a consultar
                    //a BD
                    $session->usuario = $adapter->getResultRowObject();

                    $this->getResponse()->setBody(Zend_Json::encode(array(
                                'info' => __('Ingreso exitoso'),
                                'status' => Bootstrap::INFO_AUTENTICADO
                    )));
                    return;
                    break;
            }
        }
    }

    public function recuperarAction() {
        if ($this->getRequest()->isPost()) {
            $db = new Model_Usuarios();
            $usuario = $this->getRequest()->getPost('usuario');
            /* @var $usu Model_Row_Usuario  */
            $usu = $db->fetchRow(array(
                'usuario = ?' => $usuario
            ));

            $hash = $usu->getHashValidacion();

            $url = $this->view->serverUrl($this->view->url(array(
                        'hash' => $hash,
                        'module' => 'front',
                        'action' => 'confirmar'
            )));
            $mail = new Zend_Mail();
            $mail->addTo($usu->getEmail())
                    ->setBodyHtml(<<<EMAIL
                        Para restaurar su clave haga clic 
                            <a href="{$url}">aqui</a>
EMAIL
                    )
                    ->send();
            $this->getResponse()->setBody(Zend_Json::encode(array(
                        'info' => __('Se enviaron las instrucciones por email'),
                        'status' => Bootstrap::INFO_EMAIL_ENVIADO
            )));
            return;
        }
        $this->getResponse()->setBody(Zend_Json::encode(array(
                    'error' => __('Error no capturado'),
                    'status' => Bootstrap::ERROR_NO_CAPTURADO
        )));
    }

}
