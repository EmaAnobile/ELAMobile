<?php

class InstitucionesController extends Zend_Controller_Action implements Interface_ICatalogoFacadeController {

    public function indexAction() {
        $this->view->assign("listado", Model_Instituciones::getSingleton()->fetchAll([
                    'borrado = 0'
        ]));
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

    public function altaAction() {
        $this->_forward('modificar');
    }

    public function bajaAction() {
        $id = $this->getRequest()->getParam("id");
        try {
            Model_Instituciones::getSingleton()->update(array(
                'borrado' => 1
                    ), array(
                'id = ?' => $id
            ));

            $this->getHelper('FlashMessenger')->addMessage('Institucion borrada correctamente');
        } catch (Exception $ex) {
            $this->getHelper('FlashMessenger')->addMessage('danger|' . $ex->getMessage());
        }
        $url = $this->view->url(array('id' => null, 'action' => 'index'));
        $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
    }

    public function modificarAction() {
        $id = $this->getRequest()->getParam("id");

        $item = Model_Instituciones::getSingleton()->find($id)->current();

        if ($item == null) {
            $item = Model_Instituciones::getSingleton()->createRow();
        }

        if ($this->getRequest()->isPost()) {
            $datos = $this->getRequest()->getPost();
            $item->setFromArray(array(
                'razon_social' => $datos['razon_social']
            ));
            $item->save();

            $this->getHelper('FlashMessenger')->addMessage('Institucion guardada correctamente');
            $url = $this->view->url(array('id' => null, 'action' => 'index'));
            $this->getHelper('redirector')->gotoUrlAndExit($this->view->serverUrl($url));
            return;
        }

        $this->view->assign("item", $item);
        $this->view->assign('mensajes', $this->getHelper('FlashMessenger')->getMessages());
    }

}
