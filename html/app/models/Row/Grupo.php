<?php

class Model_Row_Grupo extends Model_Row_Abstract {

    Public function __toString() {

        $html = '<div class="col-md-4 col-sm-4 col-xs-6">' . PHP_EOL;
        $html .= '<div id="boton-' . $this->getId() . '" data-grupo="' . $this->getId() . '" class="Boton_select boton_' . $this->getColor() . ' h3">' . PHP_EOL;
        $html .= '<p>' . PHP_EOL;

        foreach ($this->getSubGrupos() as $subgrupos) {
            $html .= (string) $subgrupos . PHP_EOL;
        }
        $html .= '</p>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;

        return $html;
    }

    /**
     * Devuelve los subgrupos del grupo actual
     *
     * @return Model_Row_SubGrupo[]
     */
    Public Function getSubGrupos() {
        return $this->findDependentRowset('Model_SubGrupos');
    }

    public function toArrayData() {
        $items = [];
        foreach ($this->getSubGrupos() as $item) {
            $items[] = $item->getTexto();
        }

        return $items;
    }

}
