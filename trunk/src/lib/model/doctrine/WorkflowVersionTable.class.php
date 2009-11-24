<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WorkflowVersionTable extends Doctrine_Table {
    /**
     *
     * create new instance of AdditionalText
     * @return object UserLoginTable
     */
    public static function instance() {
        return Doctrine::getTable('WorkflowVersion');
    }



    public function getAllVersionByWorkflowId($workflow_id) {
        return Doctrine_Query::create()
            ->from('WorkflowVersion wfv')
            ->select('wfv.*,')
            ->where('wfv.workflowtemplate_id = ?' ,$workflow_id)
            ->orderBy('wfv.version DESC')
            ->execute();
    }

    public function getWorkflowVersionById($id) {
        return Doctrine_Query::create()
            ->from('WorkflowVersion wfv')
            ->select('wfv.*,')
            ->where('wfv.id = ?' ,$id)
            ->execute();
    }
}