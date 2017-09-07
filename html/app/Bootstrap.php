<?php

require_once 'funciones.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected $_config;
    static protected $_logger = null;
    static protected $_writer = null;

    const MAX_LOG_SIZE = 1310720;

    protected function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Front_',
            'basePath' => APPLICATION_PATH . '/front',
        ));
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Back_',
            'basePath' => APPLICATION_PATH . '/back',
        ));
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH . '/',
        ));

        $autoloader->addResourceTypes(array(
            'interfaces' => array(
                'namespace' => 'Interface',
                'path' => 'interfaces',
        )));

        $autoloader->addResourceTypes(array(
            'menu' => array(
                'namespace' => 'Menu',
                'path' => 'menus',
        )));

        $autoloader->addResourceTypes(array(
            'service' => array(
                'namespace' => 'Service',
                'path' => 'services',
        )));
    }

    protected function _initActionHelpers() {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/back/helpers');
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/front/helpers');
    }

    protected function _initControllers() {
        $this->bootstrap('FrontController');
        $ctrl = $this->getResource('FrontController');
        $ctrl->setParam('disableOutputBuffering', true);
        $ctrl->setControllerDirectory(array(
            'default' => APPLICATION_PATH . '/front/ctrl',
            'back' => APPLICATION_PATH . '/back/ctrl',
        ));

        $ctrl->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
            'module' => 'default',
            'controller' => 'error',
            'action' => 'error'
        )));
        $ctrl->registerPlugin(new Plugin_AclBack());
    }

    protected function _initConfig() {
        try {
            $opts = $this->getApplication()->getOptions();
            if (isset($opts['resources']) && isset($opts['resources']['session'])) {
                Zend_Session::setOptions($opts['resources']['session']);
            }
        } catch (Exception $e) {
            
        }

        /**
         * Config
         */
        Zend_Registry::set('Zend_Config', $opts);
    }

    protected function _initDB() {
        /**
         * Base de datos
         */
        $rsc = $this->getOption('resources');
        if (isset($rsc['db'])) {
            $dbAdapter = Zend_Db::factory($rsc['db']['adapter'], $rsc['db']['params']);

            $dbAdapter->query("SET NAMES 'utf8'");

            Zend_Db_Table_Abstract::setDefaultAdapter($dbAdapter);
        }
    }

    protected function _initSmtp() {
        Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
            'ssl' => 'tls',
            'port' => 587,
            'auth' => 'login',
            'username' => 'reporte@error403.com.ar',
            'password' => 'R3p0rt3s'
        )));
    }

    protected function _initAcl() {
        $acl = new Zend_Acl();
        /*
         * GRUPO      = ROLES
         * FORMULARIO = RECURSOS
         * PERFIL     = PRIVILEGIO
         */
        //Agregamos los recursos
        $acl->add(new Zend_Acl_Resource('front'));
        $acl->add(new Zend_Acl_Resource('back'));
        $acl->add(new Zend_Acl_Resource('tablero'));
        $acl->add(new Zend_Acl_Resource('usuarios'));
        $acl->add(new Zend_Acl_Resource('instituciones'));
        $acl->add(new Zend_Acl_Resource('asignaciones'));
        $acl->add(new Zend_Acl_Resource('licencias'));
        $acl->add(new Zend_Acl_Resource('perfiles'));
        $acl->add(new Zend_Acl_Resource('roles'));
        $acl->add(new Zend_Acl_Resource('compras'));

        //Se determinan los Roles de la APP
        //$acl->addRole('admin');
        //$acl->addRole('usuario');

        $acl->addRole('invitado');

        //Obtenemos los Roles.
        foreach (Model_Roles::getSingleton()->fetchAll() as /* @var $Rol Model_Row_Rol */ $Rol) {
            //Agregamos cada rol Framework.
            $acl->addRole($Rol->getNombre());
            if ($Rol->getNombre() == 'Paciente') {
                $acl->allow($Rol->getNombre(), 'tablero', 'usar');
                $acl->allow($Rol->getNombre(), 'front', ['invitado']);
            }
            if ($Rol->getAdminGeneral()) {
                $acl->allow($Rol->getNombre());
            } else {
                //Por cada Permiso del Rol, damos permisos.
                foreach ($Rol->getPermisos() as $Permiso) {
                    $acl->allow($Rol->getNombre(), $Permiso->getRecurso(), $Permiso->getPrivilegio());
                }
            }

            $acl->deny($Rol->getNombre(), 'front', ['login']);
        }

        //Se determinan los Permisos para los Roles
        $acl->allow('invitado', 'front');

        //Se setean a través del Framework para 
        //luego poder obtenerlos en otra instancia.
        Zend_Registry::set('Zend_Acl', $acl);
    }

    protected function _initLocale() {
        $config = [
            'scan' => Zend_Translate::LOCALE_DIRECTORY,
            'disableNotices' => false,
        ];

        $log = true;
        if ($log) {
            $config['log'] = new Service_LoggerTraduccion();
            $config['logUntranslated'] = true;
        }

        $tr = new Zend_Translate('csv', APPLICATION_PATH . '/data/langs/', null, $config);
        Zend_Registry::set('Zend_Translate', $tr);
    }

    protected function _initRouters() {

        $ctrl = $this->getResource('FrontController');
        $router = $ctrl->getRouter();

//FRONT
        // Agregamos este route para forzar a que aparezca el idioma 
        // y no se rompa cuando no viene
        $router->addRoute('default_idioma', new Zend_Controller_Router_Route(
                ':locale', array(
            'locale' => APPLICATION_LANG
        )));

        $router->addRoute('default', new Zend_Controller_Router_Route(
                ':locale/:@controller/:@action/*', array(
            'controller' => 'index',
            'action' => 'index'
        )));

        $idioma = APPLICATION_LANG;

        if (Zend_Locale::isLocale($idioma)) {
            $idioma = APPLICATION_LANG;
        }

        $_alias = [
            'es' => 'es_AR',
            'en' => 'en_US',
        ];

        $idioma_locale = isset($_alias[$idioma]) ? $_alias[$idioma] : $idioma;

        $adp = Zend_Registry::get('Zend_Translate')->getAdapter();
        $adp->setLocale($idioma);
        Zend_Locale::setDefault($idioma_locale);
        Zend_Registry::set('Zend_Locale', new Zend_Locale($idioma_locale));
        $router->setGlobalParam('locale', $idioma);
    }

    protected function _initViewHelpers() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->headTitle()->setSeparator(' | ')->set('ELA Mobile');
        $view->addHelperPath(APPLICATION_PATH . '/front/views/helpers', '');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);

        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    protected function _initDoctype() {
        $view = $this->getResource('view');
        $view->doctype('XHTML5');
    }

    static function logException(Exception $e, $prioridad = Zend_Log::WARN) {
        $msj = $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
        self::log($msj, $prioridad, '-exception');
    }

    static function log($msj, $prioridad = Zend_Log::WARN, $prefix = '') {
        $_logger = self::getLogger($prefix);
        $_logger->log($msj, $prioridad);

        self::mail($msj, $prioridad);
    }

    static function getLogger($prefix = '') {
        if (!self::$_logger instanceof Zend_Log_Writer) {
            $tmp = $arc = DATA_PATH . '/logs/bootstrap' . $prefix . date('-Ymd');
            $i = 0;
            while (is_file($tmp . '.log') && filesize($tmp . '.log') > self::MAX_LOG_SIZE) {
                $tmp = $arc . '-' . ((string) ++$i);
            }
            $arc = $tmp . '.log';
            self::$_writer = new Zend_Log_Writer_Stream($arc);
            self::$_logger = new Zend_Log(self::$_writer);
        }
        return self::$_logger;
    }

    static function logVar($var) {
        self::log(var_export($var, true), Zend_Log::WARN, '-vars');
    }

    public static function getLapsoVigencia() {
        return 6;
    }

}
