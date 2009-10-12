<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FieldTextfieldTable extends Doctrine_Table {
    /**
     *
     * create new instance of FieldTextfield
     * @return object FieldTextfield
     */
    public static function instance() {
        return Doctrine::getTable('FieldTextfield');
    }

    /**
     * Update Textfield by id
     *
     * @param int $id, id to update
     * @param array $data, data to update
     * @return true
     */
    public function updateFieldTextfieldById($id, $data) {
        Doctrine_Query::create()
            ->update('FieldTextfield ftf')
            ->set('ftf.defaultvalue','?', $data['fieldTextfield_standard'])
            ->set('ftf.regex','?',$data['fieldTextfield_regularexpression'])
            ->where('ftf.field_id = ?',$id)
            ->execute();
        return true;
    }




}