<?php

Class Model_SubGrupos extends Model_Abstract{


 Protected $_name = 'subgrupos';
Protected $_rowClass = 'Model_Row_SubGrupo';

 protected $_referenceMap = array(
       	'SubGrupo' => array(
            'columns' => array('grupo_id'),
            'refTableClass' => 'Model_Grupos',
            'refColumns' => array('id')
        ),   
    );


}