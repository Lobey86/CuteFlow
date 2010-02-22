<?php

class FilterFieldTable extends Doctrine_Table {



    /**
     * create new instance of FormTemplate
     * @return object FormTemplate
     */
    public static function instance() {
        return Doctrine::getTable('FilterField');
    }



    public function deleteFieldsByFilterId($id) {
        Doctrine_Query::create()
            ->delete('FilterField')
            ->from('FilterField f')
            ->where('f.filter_id = ?', $id)
            ->execute();
        return true;
    }




}