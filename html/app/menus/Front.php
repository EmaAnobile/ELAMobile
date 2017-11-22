<?php

class Menu_Front extends Zend_Navigation {

    public function __construct() {
        $this->setPages(array(
            array(
                'label' => 'Inicio',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'front',
                'privilege' => 'invitado',
                'controller' => 'index',
                'action' => 'index',
            ),
            /* array(
              'label' => 'Backend',
              'reset_params' => true,
              'route' => 'administracion',
              'resource' => 'back',
              'privilege' => 'ver',
              'module' => 'back',
              'controller' => 'index',
              'action' => 'index',
              ), */
            array(
                'label' => 'Acceder',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'front',
                'privilege' => 'login',
                'controller' => 'usuarios',
                'action' => 'acceder',
            ),
            array(
                'label' => 'Instituciones',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'instituciones',
                'privilege' => 'listado',
                'controller' => 'instituciones',
                'action' => 'index',
                'pages' => array(
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'instituciones',
                        'privilege' => 'alta',
                        'controller' => 'instituciones',
                        'action' => 'alta',
                    ),
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'instituciones',
                        'privilege' => 'baja',
                        'controller' => 'instituciones',
                        'action' => 'baja',
                    ),
                )
            ),
            array(
                'label' => 'Perfiles',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'perfiles',
                'privilege' => 'listado',
                'controller' => 'perfiles',
                'action' => 'index',
                'pages' => array(
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'perfiles',
                        'privilege' => 'alta',
                        'controller' => 'perfiles',
                        'action' => 'alta',
                    ),
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'perfiles',
                        'privilege' => 'baja',
                        'controller' => 'perfiles',
                        'action' => 'baja',
                    ),
                )
            ),
            array(
                'label' => 'Roles',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'roles',
                'privilege' => 'listado',
                'controller' => 'roles',
                'action' => 'index',
                'pages' => array(
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'roles',
                        'privilege' => 'alta',
                        'controller' => 'roles',
                        'action' => 'alta',
                    ),
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'roles',
                        'privilege' => 'baja',
                        'controller' => 'roles',
                        'action' => 'baja',
                    ),
                )
            ),
            array(
                'label' => 'Usuarios',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'usuarios',
                'privilege' => 'listado',
                'controller' => 'usuarios',
                'action' => 'index',
                'pages' => array(
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'usuarios',
                        'privilege' => 'alta',
                        'controller' => 'usuarios',
                        'action' => 'alta',
                    ),
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'usuarios',
                        'privilege' => 'baja',
                        'controller' => 'usuarios',
                        'action' => 'baja',
                    ),
                )
            ),
            array(
                'label' => 'Carrito',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'compras',
                'privilege' => 'alta',
                'controller' => 'carrito',
                'action' => 'index',
            ),
            array(
                'label' => 'Compras',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'compras',
                'privilege' => 'listado',
                'controller' => 'compras',
                'action' => 'index',
                'pages' => array(
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'compras',
                        'privilege' => 'ver',
                        'controller' => 'compras',
                        'action' => 'ver',
                    ),
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'compras',
                        'privilege' => 'baja',
                        'controller' => 'compras',
                        'action' => 'baja',
                    ),
                ),
            ),
            array(
                'label' => 'Asignar Licencia',
                'reset_params' => true,
                'route' => 'default',
                'resource' => 'asignaciones',
                'privilege' => 'listado',
                'controller' => 'asignaciones',
                'action' => 'index',
                'pages' => array(
                    array(
                        'visible' => false,
                        'reset_params' => true,
                        'route' => 'default',
                        'resource' => 'asignaciones',
                        'privilege' => 'asignar',
                        'controller' => 'asignaciones',
                        'action' => 'asignar',
                    ),
                ),
            ),
        ));
        return $this;
    }

}
