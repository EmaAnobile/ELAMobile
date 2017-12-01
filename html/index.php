<?php

umask(0);

error_reporting(E_ALL & ~E_DEPRECATED);

define('DS', DIRECTORY_SEPARATOR);

/**
 * Directorio donde esta ubicado el index.php
 */
defined('DOC_ROOT') || define('DOC_ROOT', dirname(__FILE__));

/**
 * Directorio donde ira a buscar el bootstrap, y los controladores
 */
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(DOC_ROOT . '/app'));

defined('DATA_PATH') || define('DATA_PATH', APPLICATION_PATH . '/data');

defined('CONFIG_PATH') || define('CONFIG_PATH', APPLICATION_PATH . '/configs');

/**
 * Constante de entorno, definida por lo general en el htaccess el cual no debe
 * modificarse, esto sirve para distinguir entre los diferentes servidores donde
 * se corre la aplicacion y tomar diferentes configuraciones
 */
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'default'));

/**
 * Configuracion del include path para tomar las librerias
 */
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(DOC_ROOT . '/libs'),
    get_include_path(),
)));

/**
 * Idioma por defecto de la aplicacion
 */
require_once 'Zend/Locale.php';
$langs = array_keys(Zend_Locale::getOrder());
$lang = isset($langs[0]) ? preg_replace('/\_.*/', '', $langs[0]) : 'es';

if (isset($_SERVER['REQUEST_URI'])) {
    if (preg_match('#/(([\w][\w])$|([\w][\w])/|([\w][\w])$|([\w][\w])/)#', $_SERVER['REQUEST_URI'], $matches)) {
        $lang = str_replace('/', '', $matches[1]);
    }
}

if (!file_exists(APPLICATION_PATH . '/data/langs/' . DS . $lang)) {
    $lang = 'es';
}

defined('APPLICATION_LANG') || define('APPLICATION_LANG', $lang);


/**
 * Carga la aplicacion con el entorno y el path del archivo de configuracion
 */
require_once 'Zend/Application.php';
try {
    $application = new Zend_Application(
            APPLICATION_ENV, CONFIG_PATH . '/application.ini'
    );
    $application->bootstrap()->run();
} catch (Exception $exc) {
    echo $exc->getMessage() . '<br />';
    echo nl2br($exc->getTraceAsString());
}
