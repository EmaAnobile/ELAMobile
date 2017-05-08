<?php
class Menu_Front extends Zend_Navigation
{
    public function __construct()
    {
        $this->setPages(array(
                array(
                    'label' => 'Inicio',
                    'reset_params' => true,
                    'route'     => 'default',
                    'resource'  => 'front',
                    'privilege' => 'invitado',
                    'controller'=> 'index',
                    'action'    => 'index',
                ),
                array(
                    'label' => 'Acceder',
                    'reset_params' => true,
                    'route'     => 'administracion',
                    'resource'  => 'front',
                    'privilege' => 'login',
                    'controller'=> 'auth',
                    'action'    => 'login',
                ),

                array(
                    'label' => 'Tablero',
                    'reset_params' => true,
                    'route'     => 'default',
                    'resource'  => 'tablero',
                    'privilege' => 'usar',
                    'controller'=> 'tablero',
                    'action'    => 'index',
                ),                
        ));
        return $this;
    }
}
