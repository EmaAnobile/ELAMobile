<?php

class UsuariosController extends Zend_Controller_Action implements Interface_ICatalogoFacadeController {

    public function accederAction() {
        //Setea nombre a la solapa       
        $this->view->headTitle('Entrar');

        $auth = Zend_Auth::getInstance();

        // Colocamos nombre para almacenar la instancia de Sesion.        
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));

        //Si ya tiene una identidad lo redirecciona al Home
        //parametros por defecto, al default y con el true que reestablezca.
        if ($auth->hasIdentity()) {
            $this->getHelper('redirector')
                    ->gotoUrlAndExit($this->view->serverUrl($this->view->url(array(), 'default', true)));
            return;
        }

        if ($this->getRequest()->isPost()) {
            $usuario = $this->getRequest()->getPost('usuario');
            $password = $this->getRequest()->getPost('password');
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();

            //Consulta a la BD con el usuario y pass que se logeo.
            //Se utiliza un adapter que es el que espera el $Auth
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

                    //Variable que se declara para luego tener los datos
                    // de los usuarios y no tener que volver a consultar
                    //a BD
                    $session->usuario = $adapter->getResultRowObject();

                    $url = $this->view->url(array(), 'default', true);

                    if ($session->usuario->rol === 'admin') {

                        $url = $this->view->url(array(), 'administracion');
                    }

                    $this->getHelper('redirector')
                            ->gotoUrlAndExit($this->view->serverUrl($url));
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

        $url = $this->view->serverUrl($this->view->url(array(), "default", true));

        $this->getHelper('redirector')
                ->gotoUrlAndExit($url);
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

    //Administración de Usuarios
    public function indexAction() {
//        Zend_Layout::getMvcInstance()->disableLayout();
//        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
//        
//        $listado = [];
//        foreach (Model_Usuarios::getSingleton()->fetchAll() as $u) {
//            $listado[] = [
//                'id' => $u->getId(),
//                'usuario' => $u->getUsuario(),
//                'rol_id' => $u->getRolId(),
//            ];
//        }
//
//        $this->getResponse()->setBody(Zend_Json::encode($listado));
//        return;
        // Obtengo el usuario actual
        $usuarioActual = Zend_Registry::get('Usuario');

        $listado = Model_Usuarios::getSingleton()->select();
        $adminInstitucion = $usuarioActual->getInstitucionId() != null;

        if ($adminInstitucion) {
            $listado->where('institucion_id = ?', $usuarioActual->getInstitucionId());
        }

        $this->view->assign("listado", Model_Usuarios::getSingleton()->fetchAll($listado));
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

    public function altaAction() {
        $this->_forward('modificar');
    }

    public function modificarAction() {
        // Obtengo el usuario actual
        $usuarioActual = Zend_Registry::get('Usuario');
        $adminInstitucion = $usuarioActual->getInstitucionId() != null;

        $id = $this->getRequest()->getParam("id");

        if ($usuarioActual->getId() == $id) {
            $this->getHelper('FlashMessenger')->addMessage('danger|No se puede editar el mismo usuario que esta utilizando el sistema');
            $url = $this->view->url(array('id' => null, 'action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }
        $usuario = Model_Usuarios::getSingleton()->find($id)->current();

        if ($usuario == null) {
            $usuario = Model_Usuarios::getSingleton()->createRow();
        }

        $mensajes = [];
        if ($this->getRequest()->isPost()) {
            try {

                $idPaciente = Model_Roles::getSingleton()
                        ->getRol('Paciente')
                        ->getId();

                $datos = $this->getRequest()->getPost();

                $datosUsuario = array(
                    'usuario' => $datos['usuario']
                );
                if (isset($datos['rol_id']))
                    $datosUsuario['rol_id'] = $datos['rol_id'];

                if (isset($datos['institucion_id']))
                    $datosUsuario['institucion_id'] = $datos['institucion_id'];

                if ($adminInstitucion == true) {
                    $datosUsuario['rol_id'] = $idPaciente;
                    $datosUsuario['institucion_id'] = $usuarioActual->getInstitucionId();
                } elseif ($datos['institucion_id'] == '') {
                    $datosUsuario['institucion_id'] = null;
                }

                // Seteo dos array vacios
                $tablerosBaja = $tablerosAlta = [];
                // Si $valor viene en cero es porque no se selecciono el tablero
                foreach ($datos['tablero'] as $id => $valor) {
                    // Si no se selecciono lo asigno al array de baja
                    if ($valor == 0)
                        $tablerosBaja[] = $id;
                    // ...sino se selecciono lo asigno al array de alta
                    else
                        $tablerosAlta[] = $id;
                }
                $usuario->setFromArray($datosUsuario);

                if ($datosUsuario['rol_id'] == $idPaciente && count($tablerosAlta) == 0) {
                    throw new Exception('Se debe asignar al menos un tablero');
                }

                $usuario->save();

                /* @var $usuariostableros Model_UsuariosTableros */
                $usuariostableros = Model_UsuariosTableros::getSingleton();
                foreach ($tablerosBaja as $id) {
                    //Quitamos permiso
                    $usuariostableros->delete(array(
                        'tablero_id = ?' => $id,
                        'usuario_id = ?' => $usuario->getId(),
                    ));
                }

                foreach ($tablerosAlta as $id) {
                    //Intentamos Agregar permiso
                    try {
                        $usuariostableros->insert(array(
                            'tablero_id' => $id,
                            'usuario_id' => $usuario->getId(),
                        ));
                    } catch (Exception $ex) {
                        
                    }
                }
                $this->getHelper('FlashMessenger')->addMessage('Usuario guardado correctamente');
                $url = $this->view->url(array('id' => null, 'action' => 'index'));
                $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
                return;
            } catch (Exception $ex) {
                $mensajes[] = 'danger|' . $ex->getMessage();
            }
        }

        $this->view->assign("Usuario", $usuario);
        $this->view->assign("admin_institucion", $adminInstitucion);
        $this->view->assign("Roles", Model_Roles::getSingleton()->fetchAll());
        $this->view->assign("instituciones", Model_Instituciones::getSingleton()->fetchAll([
                    'borrado = 0'
        ]));
        $this->view->assign("Tableros", Model_Tableros::getSingleton()->fetchAll());
        $this->view->assign('mensajes', array_merge($mensajes, $this->getHelper('FlashMessenger')->getMessages()));
    }

    public function misDatosAction() {
        
    }

    public function bajaAction() {
        $this->getHelper('FlashMessenger')->addMessage('danger|No es posible dar de baja usuarios');
        $url = $this->view->url(array('id' => null, 'action' => 'index'));
        $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
    }

}
