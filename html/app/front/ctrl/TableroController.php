<?php

class TableroController extends Zend_Controller_Action {

    public function indexAction() {
        // Obtengo el usuario actual
        $usuario = Zend_Registry::get('Usuario');

        foreach ($usuario->getTableros() as $tablero) {
            $this->view->assign(
                    'tablero_' . $tablero->getCodigo(), // Nombre en la vista
                    $tablero                            // Contenido
            );
        }
    }

}
