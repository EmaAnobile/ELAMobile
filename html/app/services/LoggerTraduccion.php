<?php

class Service_LoggerTraduccion extends Zend_Log {

    protected static $_handler = [];

    public function log($message, $priority, $extras = null) {
        preg_match("/Untranslated message within \'([^\\\]+)\': (.*)/", $message, $grupos);
        $idioma = $grupos[1];
        $mensaje = $grupos[2];

        fputcsv(self::getHandler($idioma), [$mensaje, $mensaje], ';');
    }

    public static function getHandler($idioma) {
        if (isset(self::$_handler[$idioma]) == false) {
            if (!is_dir(APPLICATION_PATH . '/data/langs/' . $idioma)) {
                mkdir(APPLICATION_PATH . '/data/langs/' . $idioma, 0777, true);
            }
            $archivo = APPLICATION_PATH . '/data/langs/' . $idioma . DS . 'untranslate.csv';
            $r = fopen($archivo, 'a');
            self::$_handler[$idioma] = $r;
        }
        return self::$_handler[$idioma];
    }

    public static function closeHandler($idioma) {
        if (isset(self::$_handler[$idioma])) {
            fclose(self::$_handler[$idioma]);
            unset(self::$_handler[$idioma]);
        }
    }

}
