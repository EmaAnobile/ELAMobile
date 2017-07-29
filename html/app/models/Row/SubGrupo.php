<?php

class Model_Row_SubGrupo extends Model_Row_Abstract {

    /**
     * Devuelve el texto de la letra o palabra
     * 2
     * @return string 
     */
    Public function __toString() {
        if ($this->getLetraId() !== null)
            return '<div class="col-md-4 col-sm-4 col-xs-6">' . $this->getTexto() . '</div>' . PHP_EOL;

        return '<div class="col-md-12">' . $this->getTexto() . '</div>' . PHP_EOL;
    }

}
