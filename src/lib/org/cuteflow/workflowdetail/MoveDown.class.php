<?php



class MoveDown extends WorkflowSetStation {


    public $station;

    public function  __construct(SetStation $station) {
        $this->station = $station;
        $this->setNewStationActive($this->station->newWorkflowSlotUser,$this->station->version_id, $this->station->workflowtemplate_id);
        $this->calculateStation($this->station->newWorkflowSlotUser->getWorkflowslotId(),$this->station->newWorkflowSlotUser->getPosition()+1);
    }



    public function setNewStationActive (WorkflowSlotUser $user, $version_id, $workflowtemplate_id) {
        $currentWorkflowProcessUser = WorkflowProcessUserTable::instance()->getProcessUserByWorkflowSlotUserId($user->getId());
        WorkflowProcessUserTable::instance()->deleteWorkflowProcessUserByWorkfloSlotUserId($user->getId());
        $workflowPU = new WorkflowProcessUser();
        $workflowPU->setWorkflowprocessId($currentWorkflowProcessUser[0]->getWorkflowprocessId());
        $workflowPU->setWorkflowslotuserId($user->getId());
        $workflowPU->setUserId($user->getUserId());
        $workflowPU->setInprogresssince(time());
        $workflowPU->setDecissionstate('WAITING');
        $workflowPU->setResendet(0);
        $workflowPU->save();

    }
    
    public function calculateStation($workflowslot_id, $position) {
        $nextUser = $this->getNextUser($workflowslot_id, $position);
        if(!empty($nextUser)) { // user has been found
            $currentWorkflowProcessUser = WorkflowProcessUserTable::instance()->getProcessUserByWorkflowSlotUserId($nextUser[0]['id'])->toArray();
            if(!empty($currentWorkflowProcessUser)) { // user has a process, then remove it
                WorkflowProcessUserTable::instance()->deleteWorkflowProcessUserByWorkfloSlotUserId($currentWorkflowProcessUser[0]['workflowslotuser_id']);
                WorkflowProcessTable::instance()->deleteWorkflowProcessById($currentWorkflowProcessUser[0]['workflowprocess_id']);
                $this->calculateStation($nextUser[0]['workflowslot_id'], $nextUser[0]['position']+1);
            }
            else {
                $this->calculateStation($nextUser[0]['workflowslot_id'], $nextUser[0]['position']+1); // user has no process running, the search for next user
            }
        }
        else {
            $this->calculateSlot($workflowslot_id);
        }
    }

    









}
?>