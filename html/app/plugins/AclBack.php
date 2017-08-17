<?php

class Plugin_AclBack extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $ctrl = $request->getControllerName();
        $act = $request->getActionName();
        $mod = $request->getModuleName();

        // Obtengo la vista y seteo los nombres para tenerlos en caso de ser necesario
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();

        $view->assign('controller', $ctrl);
        $view->assign('action', $act);
        $view->assign('module', $mod);

        // Por defecto, usuario es null
        $usuario = null;

        $auth = Zend_Auth::getInstance();

        //Almacena la sesión para obtener en futuras instancias.
        $auth->setStorage(new Zend_Auth_Storage_Session('backend'));
        $session = new Zend_Session_Namespace('backend');

        if ($mod === 'back') {
            $front = Zend_Controller_Front::getInstance();

            // Todos los controllers del modulo back requieren de un usuario logueado
            $protegido = ($ctrl != 'error') ? true : false;
            if ($auth->hasIdentity() == false && $protegido) {
                $this->getRequest()
                        ->setControllerName('usuarios')
                        ->setActionName('acceder')
                        ->setModuleName('default');
                return;
            } elseif ($auth->hasIdentity()) {
                $usuario = $session->usuario;
            }
        } else {
            if ($auth->hasIdentity()) {
                $usuario = $session->usuario;
            }
        }

        //El patrón Singleton nos permite obtener la instancia de Usuarios
        //en caso de ya estar instanciada obtiene esa, en otro caso realiza una 
        //nueva instancia.
        if ($usuario != null) {
            $usuario = Model_Usuarios::getSingleton()->find($usuario->id)->current();

            if ($usuario->getNombreRol() == 'Paciente') {
                $ahora = new Zend_Date();
                $vigencia = new Zend_Date($usuario->getFechaVigencia());
                if ($ahora->compare($vigencia) >= 0) {
                    $auth->clearIdentity();
                    $this->getRequest()
                            ->setControllerName('usuarios')
                            ->setActionName('acceder')
                            ->setModuleName('default');
                    return;
                }
            }
        }

        switch ($mod) {
            case 'back':
                $navigation = new Menu_Front();
                $activeControllers = $navigation->findAllBy('controller', $ctrl);
                $activo = null;
                foreach ($activeControllers as $activeController) {
                    if ($activeController->getAction() == $act) {
                        $activo = $activeController;
//                        $activeController->active = true;
                    }
                }

                $res = 'back';
                $priv = 'ver';
                $role = ($usuario !== null) ? $usuario->getNombreRol() : 'invitado';
                if ($activo !== null) {
                    $res = $activo->getResource();
                    $priv = $activo->getPrivilege();
                }

                if (($auth->hasIdentity() && !Zend_Registry::get('Zend_Acl')->isAllowed($role, $res, $priv))) {
                    //throw new Zend_Acl_Exception(Zend_Acl::TYPE_DENY);
                    $this->getRequest()
                            ->setControllerName('index')
                            ->setActionName('index')
                            ->setModuleName('default');
                }

                break;
            default:

                $navigation = new Menu_Front();
                $activeControllers = $navigation->findAllBy('controller', $ctrl);
                foreach ($activeControllers as $activeController) {
                    if ($activeController->getAction() == $act) {
//                        $activeController->active = true;
                    }
                }

                break;
        }

        //Setea el usuario logeado en el propio Framework para recuperarlo
        // en otras instancias para realizar validaciones que sean necesarias.
        Zend_Registry::set('Usuario', $usuario);

        if (isset($usuario)) {
            $role = $usuario->getNombreRol();
            $view->assign('usuario_id', $usuario->getId());
            $view->assign('usuario', $usuario);
        } else {
            $role = 'invitado';
        }
        $view->assign('usuario_rol', $role);

        if (isset($navigation)) {
            $view->navigation($navigation);
            $view->navigation()->setAcl(Zend_Registry::get('Zend_Acl'))->setRole($role);
        }
    }

}
