<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowSlotFieldCheckboxTable extends Doctrine_Table {
    public static function instance() {
        return Doctrine::getTable('WorkflowSlotFieldCheckbox');
    }



    public function getAllItemsByWorkflowFieldId($id) {
        return Doctrine_Query::create()
            ->from('WorkflowSlotFieldCheckbox wsfcb')
            ->select('wsfcb.*,')
            ->where('wsfcb.workflowslotfield_id = ?' ,$id)
            ->execute();
    }

}