<?php

    class Model_Row_Tablero extends Model_Row_Abstract {
    
    Public function __toString() {
        $html = '';
        foreach ($this->getGrupos() as $grupo) {
            $html .= (string) $grupo;
        }

        return $html;
    }

    Public Function getUsuarios() {
        return $this->findManytoManyRowset('Model_Usuarios', 'Model_UsuariosTableros');
    }

    /**
     * Grupos relacionados al tablero mediante la clase Model_TablerosGrupos.
     * Obtiene los grupos relacionados de la tabla tableros_grupos
     * 
     * @return Model_Row_Grupo[]
     */
    Public Function getGrupos() {
        return $this->findManytoManyRowset('Model_Grupos', 'Model_TablerosGrupos');
    }

    public function toArrayData() {
        $grupos = [];
        foreach ($this->getGrupos() as $grupo) {
            $grupos[$grupo->getId()] = $grupo->toArrayData();
        }

        return $grupos;
    }

}
