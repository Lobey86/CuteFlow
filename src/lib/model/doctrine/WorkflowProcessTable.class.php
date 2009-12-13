<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowProcessTable extends Doctrine_Table {

    /**
     *
     * create new instance of AdditionalText
     * @return object UserLoginTable
     */
    public static function instance() {
        return Doctrine::getTable('WorkflowProcess');
    }


    public function getCurrentStation($version_id) {
        return Doctrine_Query::create()
            ->from('WorkflowProcess wfp')
            ->select('wfp.*')
            ->leftJoin('wfp.WorkflowProcessUser wfpu')
            ->where('wfp.workflowversion_id = ?' ,$version_id)
            ->andWhere('wfpu.decissionstate = ?', 'WAITING')
            ->execute();
        
    }

    public function deleteWorkflowProcessById($id) {
        Doctrine_Query::create()
            ->delete('WorkflowProcess')
            ->from('WorkflowProcess wfp')
            ->where('wfp.id = ?', $id)
            ->execute();
        return true;
    }


    public function deleteWorkflowProcessByWorkflowSlotId($slot_id) {
        Doctrine_Query::create()
            ->delete('WorkflowProcess')
            ->from('WorkflowProcess wfp')
            ->where('wfp.workflowslot_id = ?', $slot_id)
            ->execute();
        return true;
    }


    
}