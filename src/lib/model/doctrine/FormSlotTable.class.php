<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FormSlotTable extends Doctrine_Table {


    /**
     * create new instance of FormSlot
     * @return object FormSlot
     */
    public static function instance() {
        return Doctrine::getTable('FormSlot');
    }

    /**
     * Deletes slots by template id
     * 
     * @param int $id, template id
     * @return true
     */
    public function deleteFormSlotByTemplateId($id) {
        Doctrine_Query::create()
            ->delete('FormSlot')
            ->from ('FormSlot fs')
            ->where('fs.formtemplate_id = ?', $id)
            ->execute();

        return true;
    }




}