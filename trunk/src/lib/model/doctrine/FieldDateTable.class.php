<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FieldDateTable extends Doctrine_Table {
    /**
     *
     * create new instance of FieldDateTable
     * @return object FieldDateTable
     */
    public static function instance() {
        return Doctrine::getTable('FieldDate');
    }

    /**
     * Update Date by id
     * @param int $id, id of number
     * @param array $data, data to update
     * @return true
     */
    public function updateFieldDateById($id, $data) {
        Doctrine_Query::create()
            ->update('FieldDate fd')
            ->set('fd.defaultvalue','?', $data['fieldDate_date'])
            ->set('fd.regex','?',$data['fieldDate_regularexpression'])
            ->set('fd.dateformat','?',$data['fieldDate_format'])
            ->where('fd.field_id = ?',$id)
            ->execute();
        return true;
    }

    /**
     * Get content of a field by its id
     * @param int $id, id of the field
     * @return Doctrine_Collection
     */
    public function getDateByFieldId($id) {
        return Doctrine_Query::create()
            ->select('fd.*')
            ->from('FieldDate fd')
            ->where('fd.field_id = ?', $id)
            ->execute();
    }


}