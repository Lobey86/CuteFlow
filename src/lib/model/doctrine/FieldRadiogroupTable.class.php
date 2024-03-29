<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FieldRadiogroupTable extends Doctrine_Table {

    /**
     * create new instance of FieldRadiogroupTable
     * @return object FieldRadiogroupTable
     */
    public static function instance() {
       return Doctrine::getTable('FieldRadiogroup');
    }


    /**
     * Find all groups for a parent field
     * @param int $id, id of the parent field
     * @return Doctrine_Collection
     */
    public function findRadiogroupByFieldId($id) {
        return Doctrine_Query::create()
            ->from('FieldRadiogroup frg')
            ->select('frg.*')
            ->where('frg.field_id = ?', $id)
            ->orderBy('frg.position asc')
            ->execute();
    }

    /**
     * Sets all radiogroups to inactive for an field id
     * @param int $id , id of field
     * @return true
     */
    public function setRadiogroupToNullById($id) {
        Doctrine_Query::create()
            ->update('FieldRadiogroup frg')
            ->set('frg.isactive','?',0)
            ->where('frg.field_id = ?', $id)
            ->execute();
        return true;
    }

    public function getRadiogroupItemById($id) {
        return Doctrine_Query::create()
            ->from('FieldRadiogroup frg')
            ->select('frg.*')
            ->where('frg.id = ?', $id)
            ->execute();
    }
    



}