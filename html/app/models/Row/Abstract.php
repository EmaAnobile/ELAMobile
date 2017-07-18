<?php

class Model_Row_Abstract extends Zend_Db_Table_Row_Abstract {

    public function __toString() {
        return sprintf('%s', $this->getId());
    }

    // Magia
    public function __call($method, array $args) {
        switch (substr($method, 0, 3)) {
            case 'get' :
                $key = $this->_underscore(substr($method, 3));
                return $this->__get($key);
            case 'set' :
                $key = $this->_underscore(substr($method, 3));
                $this->__set($key, isset($args[0]) ? $args[0] : null);
                return $this;
            case 'uns' :
                $key = $this->_underscore(substr($method, 3));
                $this->__unset($key);
                return $this;
            case 'has' :
                $key = $this->_underscore(substr($method, 3));
                return $this->__isset($key);
        }
        throw new Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }

    protected function _underscore($name) {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
    }

}
