<?php

abstract class Model_Abstract extends Zend_Db_Table_Abstract {

    static $_instances = array();
    protected $_prefix = '';

    /**
     *
     * @return Model_Abstract
     */
    final public static function getSingleton() {
        $class = get_called_class();

        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }

        return self::$_instances[$class];
    }

    public function getName() {
        return $this->_name;
    }

    public function __toString() {
        return $this->getName();
    }

    protected function _setupTableName() {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $cfgdb = $db->getConfig();
        if(isset($cfgdb['prefix']))
        $this->_prefix = $cfgdb['prefix'];

        $this->_name = $this->_prefix . $this->_name;
    }

}
