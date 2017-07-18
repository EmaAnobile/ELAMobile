<?php

class IndexController extends Zend_Controller_Action {

    public function indexAction() {
        // Verificar que este logeado.
        //Obteniendo el usuario registrado en 
        // el archivo Bootstrap.php
        $usuario = Zend_Registry::get('Usuario');

        if ($usuario != null) {
            $this->_forward('index', 'tablero');
        }
    }

}
