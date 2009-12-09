<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowSlotFieldTextfieldTable extends Doctrine_Table {

    public static function instance() {
        return Doctrine::getTable('WorkflowSlotFieldTextfield');
    }



    public function getAllItemsByWorkflowFieldId($id) {
        return Doctrine_Query::create()
            ->from('WorkflowSlotFieldTextfield wsft')
            ->select('wsft.*,')
            ->where('wsft.workflowslotfield_id = ?' ,$id)
            ->execute();
    }


    public function updateTextfieldByWorkflowFieldId($id, $value) {
        Doctrine_Query::create()
            ->update('WorkflowSlotFieldTextfield wsft')
            ->set('wsft.value','?',$value)
            ->where ('wsft.workflowslotfield_id = ?',$id)
            ->execute();
    }

}