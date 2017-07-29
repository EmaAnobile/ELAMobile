<?php

function __($messageid = null) {
    if ($messageid == null) {
        return '';
    }

    $translate = null;
    if (Zend_Registry::isRegistered('Zend_Translate')) {
        $translate = Zend_Registry::get('Zend_Translate');
    }

    $options = func_get_args();

    array_shift($options);
    $count = count($options);
    $locale = null;
    if ($count > 0) {
        if (Zend_Locale::isLocale($options[($count - 1)], null, false) !== false) {
            $locale = array_pop($options);
        }
    }

    if ((count($options) === 1) and ( is_array($options[0]) === true)) {
        $options = $options[0];
    }

    if ($translate !== null) {
        $messageid = $translate->translate($messageid, $locale);
    }

    if (count($options) === 0) {
        return $messageid;
    }

    return vsprintf($messageid, $options);
}
