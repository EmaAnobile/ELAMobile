<?php

class Api_TableroController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->disableLayout();
        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
        $this->getResponse()
                ->setHeader('Content-Type', 'application/json');
    }

    public function indexAction() {
        // Obtengo el usuario actual
        $usuario = Zend_Registry::get('Usuario');
        $array = array();
        foreach ($usuario->getTableros() as /* @var $tablero Model_Row_Tablero */ $tablero) {
            $array['tablero_' . $tablero->getCodigo()] = $tablero->toArrayData();
        }
        $this->getResponse()->setBody(Zend_Json::encode($array));
    }

}
