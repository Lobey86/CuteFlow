<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowSlotFieldFileTable extends Doctrine_Table {

    public static function instance() {
        return Doctrine::getTable('WorkflowSlotFieldFile');
    }
    

    public function getAllItemsByWorkflowFieldId($id) {
        return Doctrine_Query::create()
            ->from('WorkflowSlotFieldFile wsff')
            ->select('wsff.*,')
            ->where('wsff.workflowslotfield_id = ?' ,$id)
            ->execute();
    }

}