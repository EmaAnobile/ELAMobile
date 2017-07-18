<?php

class RolesController extends Zend_Controller_Action implements Interface_ICatalogoFacadeController {

    public function indexAction() {
        // Obtengo el usuario actual
        $usuario = Zend_Registry::get('Usuario');

        $this->view->assign("listado", Model_Roles::getSingleton()->fetchAll());
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

    public function altaAction() {
        $this->_forward('modificar');
    }

    public function bajaAction() {

        $id = $this->getRequest()->getParam("id");

        //Obtenemos todos los roles que tienen este perfil
        $_rol = Model_Roles::getSingleton()->find($id)->current();

        $usuarios = array();
        foreach ($_rol->getUsuarios() as $usuario) {
            $usuarios[] = $usuario->getUsuario();
        }

        try {
            if ($_rol->getsistema() == 1) {
                throw new Exception('El rol es de sistema y no puede ser borrado');
            }
            if (count($usuarios) > 0) {
                throw new Exception('El rol esta relacionado a/los usuarios: ' . implode(', ', $usuarios));
            }

            Model_RolesPerfiles::getSingleton()->delete(array(
                'rol_id = ?' => $id
            ));

            Model_Roles::getSingleton()->delete(array(
                'id = ?' => $id
            ));

            $this->getHelper('FlashMessenger')->addMessage('Rol borrado correctamente');
        } catch (Exception $ex) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . $ex->getMessage());
        }
        $url = $this->view->url(array('id' => null, 'action' => 'index'));
        $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
    }

    public function modificarAction() {

        // Obtengo el usuario actual
        $usuario = Zend_Registry::get('Usuario');

        $id = $this->getRequest()->getParam("id");

        //Obtenemos todos los roles que tienen este usuario
        $rol = Model_Roles::getSingleton()->find($id)->current();
 
        if ($rol == null) {
            $rol = Model_Roles::getSingleton()->createRow();
        }

        if ($rol->getsistema() == 1) {
            $this->getHelper('FlashMessenger')->addMessage('danger|El rol es de sistema y no puede ser editado');
            $url = $this->view->url(array('id' => null, 'action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }

        if ($this->getRequest()->isPost()) {
            $datos = $this->getRequest()->getPost();
            $rol->setFromArray(array(
                'nombre' => $datos['nombre']
            ));
            $rol->save();

            /* @var $rolesPerfiles Model_RolesPerfiles */
            $rolesPerfiles = Model_RolesPerfiles::getSingleton();

            foreach ($datos['perfiles'] as $perfil => $estado) {

                if ($estado == 0) {

                    //Quitamos permiso

                    $rolesPerfiles->delete(array(
                        'rol_id = ?' => $rol->getId(),
                        'perfil_id=?' => $perfil,
                    ));
                } else {

                    //Intentamos Agregar permiso
                    try {

                        $rolesPerfiles->insert(array(
                            'rol_id' => $rol->getId(),
                            'perfil_id' => $perfil,
                        ));
                    } catch (Exception $ex) {
                        
                    }
                }
            }
            $this->getHelper('FlashMessenger')->addMessage('Rol guardado correctamente');
            $url = $this->view->url(array('id' => null, 'action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }

        $this->view->assign("Perfil", $rol);
        $this->view->assign("Perfiles", Model_Perfiles::getSingleton()->fetchAll());
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

}
