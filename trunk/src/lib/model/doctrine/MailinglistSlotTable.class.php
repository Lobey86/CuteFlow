<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MailinglistSlotTable extends Doctrine_Table {


    /**
     * create new instance of FormTemplate
     * @return object FormTemplate
     */
    public static function instance() {
        return Doctrine::getTable('MailinglistSlot');
    }

    /**
     * delete slot by its id
     * @param array $ids, ids
     * @return true
     */
    public function deleteSlotByIdInArray(array $ids) {
        Doctrine::getTable('MailinglistSlot')
                ->createQuery('mls')
                ->whereIn('mls.slot_id', $ids)
                ->execute()
                ->delete();
        return true;
    }





    

}