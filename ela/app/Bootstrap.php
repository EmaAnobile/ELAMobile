<?php


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
            'menu' => array(
                'namespace' => 'Menu',
                'path' => 'menus',
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
            'back'    => APPLICATION_PATH . '/back/ctrl',
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

    protected function _initAcl() {
        $acl = new Zend_Acl();

        $acl->add(new Zend_Acl_Resource('front'));
        $acl->add(new Zend_Acl_Resource('back'));
        $acl->add(new Zend_Acl_Resource('tablero'));

        $acl->addRole('admin');
        $acl->addRole('usuario');
        $acl->addRole('invitado');

        $acl->allow('invitado', 'front');
        $acl->allow('admin');
        $acl->deny(['admin','usuario'],'front', ['login']);

        Zend_Registry::set('Zend_Acl', $acl);
    }

    protected function _initRouters() {
        $ctrl = $this->getResource('FrontController');
        $router = $ctrl->getRouter();

//FRONT
        $router->addRoute('default', new Zend_Controller_Router_Route(
                ':controller/:action/*', array(
                'controller' => 'index',
                'action' => 'index'
        )));

//BACK
        $router->addRoute('administracion', new Zend_Controller_Router_Route(
            'backend/:controller/:action/*', array(
            'module' => 'back',
            'controller' => 'index',
            'action' => 'index'
        )));
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
}
