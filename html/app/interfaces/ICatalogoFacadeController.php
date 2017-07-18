<?php

/**
 * Interfaz para la implementacion de facade pattern
 */
interface Interface_ICatalogoFacadeController {

    /**
     * Fachada de listado
     */
    public function indexAction();

    /**
     * Fachada de alta
     */
    public function altaAction();
    
    /**
     * Fachada de modificar
     */
    public function modificarAction();

    /**
     * Fachada de baja
     */
    public function bajaAction();
}
