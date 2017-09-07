<?php

class AsignacionesController extends Zend_Controller_Action {

    public function indexAction() {
        $usuario = Zend_Registry::get('Usuario');

        if (!$usuario->getInstitucionId()) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . __('Usted no tiene permitido Asignar'));
            $url = $this->view->url(array('action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));

            return;
        }

        $tb = new Model_InstitucionesLicencias();

        $licencias = $tb->fetchAll(Array(
            'institucion_id =?' => $usuario->getInstitucionId(),
            'usuario_id is not null'), 'fecha_vigencia desc');

        $this->view->assign("Licencias", $licencias);
    }

    public function asignarAction() {

        /* @var $usuario Model_Row_Usuario */
        $usuario = Zend_Registry::get('Usuario');

        if (!$usuario->getInstitucionId()) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . __('Usted no tiene permitido Asignar'));
            $url = $this->view->url(array('action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));

            return;
        }

        $tb = new Model_InstitucionesLicencias();
        $tb_usuarios = new Model_Usuarios();

        if ($this->getRequest()->isPost()) {
            $tipoLicencia = $this->getRequest()->getPost('tipo_licencia');
            $usuarios = $this->getRequest()->getPost('usuarios');
            try {
                $licencias = $tb->getLicenciasDisponible($usuario->getInstitucionId(), $tipoLicencia, count($usuarios));

                $idx = 0;
                $fechaVigencia = Zend_Date::now()->addMonth(Bootstrap::getLapsoVigencia());

                foreach ($licencias as $licencia) {
                    $idUsuario = $usuarios[$idx++];
                    $licencia->setFromArray(array(
                        'usuario_id' => $idUsuario,
                        'fecha_vigencia' => $fechaVigencia->toString(Zend_Date::ISO_8601),
                    ))->save();
                    
                    $datos = array(
                        'fecha_vigencia' => $fechaVigencia->toString(Zend_Date::ISO_8601),
                        'tipo_licencia_id' => $licencia->getTipoLicenciaId()
                    );
                    $tb_usuarios->update($datos, array(
                        'id = ?' => $idUsuario
                    ));
                }
                $this->getHelper('FlashMessenger')->addMessage(__('Asignacion correcta'));
                $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($this->view->url()));
                return;
            } catch (Exception $ex) {
                $this->getHelper('FlashMessenger')->addMessage('danger|' . $ex->getMessage());
                $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($this->view->url()));
                return;
            }
        }

        $fecha = new Zend_Date();
        $this->view->assign("TiposLicencias", $tb->getTiposLicenciasDisponibles($usuario->getInstitucionId()));
        $this->view->assign("usuarios_sin", $tb_usuarios->getUsuariosAsignables($usuario->getInstitucionId(), $fecha));
        $this->view->assign("usuarios_con", $tb_usuarios->getUsuariosAsignados($usuario->getInstitucionId(), $fecha));
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

}
