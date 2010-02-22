<?php

class FilterTable extends Doctrine_Table {

    /**
     *
     * create new instance of AdditionalText
     * @return object UserLoginTable
     */
    public static function instance() {
        return Doctrine::getTable('Filter');
    }



    public function getAllFilter() {
        return Doctrine_Query::create()
           ->select('f.*')
           ->from('Filter f')
           ->orderBy('f.id ASC')
           ->execute();
        
    }

}