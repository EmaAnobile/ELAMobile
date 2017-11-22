<?php

/**
 * Interfaz para la implementacion de facade pattern
 */
interface Interface_IMetodoPagoFacade {

    function __construct($compra);

    function getRedirect();

    function getFormularioPago();

    function isRedirect();

    function getUrlNotificacion();

    function setUrlNotificacion($url);

    function getUrlOk();

    function setUrlOk($url);

    function getUrlKo();

    function setUrlKO($url);

    function notify(Model_Row_Compra $compra);
}
