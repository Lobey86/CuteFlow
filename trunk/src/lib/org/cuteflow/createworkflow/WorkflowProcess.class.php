<?php

class WorkflowProcessClass {


    public function  __construct() {
        
    }


    /**
     * Create first workflowproess entry when a new workflow will start
     * @param int $version_id, id of the workflowtemplate verion
     * @param int $template_id, id of the workflowtemplate
     * @return <type>
     */
    public function addWorkflowProcess($version_id, $template_id) {
        $workflowSlots = WorkflowSlotTable::instance()->getFirstSlotOfNewCirculation($version_id, $template_id);
        $documenttamplateSlot = $workflowSlots[0]->getDocumenttemplateSlot()->toArray();
        $users = WorkflowSlotUserTable::instance()->getUserBySlotId($workflowSlots[0]->getId())->toArray();

        $workflowProcess = new WorkflowProcess();
        $workflowProcess->setWorkflowtemplateId(1);
        $workflowProcess->setWorkflowversionId(1);
        $workflowProcess->setWorkflowslotId($workflowSlots[0]->getId());
        $workflowProcess->save();
        $processId = $workflowProcess->getId();
        switch ($documenttamplateSlot[0]['sendtoallreceivers']) {
            case 1:
                $this->addAllReceiver($users, $processId);
                break;
            case 0:
                $this->addSingleReceiver($users, $processId);
                break;
        }
        return true;
    }


    
    public function addAllReceiver(array $users, $processId) {
        foreach($users as $user) {
            $workflowUser = new WorkflowProcessUser();
            $workflowUser->setWorkflowprocessId($processId);
            $workflowUser->setUserId($user['user_id']);
            $workflowUser->setInprogresssince(time());
            $workflowUser->setDecissionstate('WAITING');
            $workflowUser->setDateofdecission(0);
            $workflowUser->setResendet(0);
            $workflowUser->save();
        }
    }

    public function addSingleReceiver(array $users, $processId) {
        $workflowUser = new WorkflowProcessUser();
        $workflowUser->setWorkflowprocessId($processId);
        $workflowUser->setUserId($users[0]['user_id']);
        $workflowUser->setInprogresssince(time());
        $workflowUser->setDecissionstate('WAITING');
        $workflowUser->setDateofdecission(0);
        $workflowUser->setResendet(0);
        $workflowUser->save();
    }

}
?>