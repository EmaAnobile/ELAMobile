<?php
class PerfilesController extends Zend_Controller_Action implements Interface_ICatalogoFacadeController {
    
    public function indexAction() {
        // Obtengo el usuario actual
        $usuario = Zend_Registry::get('Usuario');
        $this->view->assign("listado", Model_Perfiles::getSingleton()->fetchAll());
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }
    public function altaAction() {
        $this->_forward('modificar');
    }
    
    public function bajaAction() {
        // Obtengo el usuario actual
        $usuario = Zend_Registry::get('Usuario');
        $id = $this->getRequest()->getParam("id");
        //Obtenemos todos los roles que tienen este perfil
        $perfil = Model_Perfiles::getSingleton()->find($id)->current();
        $roles = array();
        foreach ($perfil->getRoles() as $rol) {
            $roles[] = $rol->getNombre();
        }
        
        try {
            if (count($roles) > 0) {
                throw new Exception('El perfil esta relacionado a/los roles: ' . implode(', ', $roles));
            }
            Model_PerfilesPermisos::getSingleton()->delete(array(
                'perfil_id = ?' => $id
            ));
            Model_Perfiles::getSingleton()->delete(array(
                'id = ?' => $id
            ));
            $this->getHelper('FlashMessenger')->addMessage('Perfil borrado correctamente');
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
        
        $perfil = Model_Perfiles::getSingleton()->find($id)->current();
       
        if ($perfil == null) {
            $perfil = Model_Perfiles::getSingleton()->createRow();
        }
       
        if ($this->getRequest()->isPost()) {
            $datos = $this->getRequest()->getPost();
            $perfil->setFromArray(array(
                'nombre' => $datos['nombre']
            ));
            $perfil->save();
            /* @var $Perfilespermisos Model_PerfilesPermisos */
            $Perfilespermisos = Model_PerfilesPermisos::getSingleton();
            foreach ($datos['permisos'] as $recurso => $permisos) {
                foreach ($permisos as $privilegio => $estado) {
                    if ($estado == 0) {
                        //Quitamos permiso
                        $Perfilespermisos->delete(array(
                            'perfil_id = ?' => $perfil->getId(),
                            'recurso = ?' => $recurso,
                            'privilegio=?' => $privilegio,
                        ));
                    } else {
                        //Intentamos Agregar permiso
                        try {
                            $Perfilespermisos->insert(array(
                                'perfil_id' => $perfil->getId(),
                                'recurso' => $recurso,
                                'privilegio' => $privilegio,
                            ));
                        } catch (Exception $ex) {
                            
                        }
                    }
                }
            }
            $this->getHelper('FlashMessenger')->addMessage('Perfil guardado correctamente');
            $url = $this->view->url(array('id' => null, 'action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }
        $this->view->assign("Perfil", $perfil);
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }
}