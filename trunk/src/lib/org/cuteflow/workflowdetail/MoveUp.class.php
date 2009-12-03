<?php


class MoveUp extends WorkflowSetStation{


    public $station;

    public function  __construct(SetStation $station) {
        $this->station = $station;
        $this->setStationInactive($this->station->currentWorkflowSlotUser->getId());
        $this->calculateStation($this->station->currentWorkflowSlotUser->getWorkflowslotId(),$this->station->currentWorkflowSlotUser->getPosition()+1);
    }


    public function setStationInactive($workflowslotuser_id) {
        WorkflowProcessUserTable::instance()->skipAllStation($workflowslotuser_id);
    }


    public function calculateStation($workflowslot_id, $position) {
        $nextUser = $this->getNextUser($workflowslot_id, $position);
         if(!empty($nextUser)) {
             if($nextUser[0]['id'] != $this->station->newWorkflowSlotUser_id) {
                 $wfp = new WorkflowProcess();
                 $wfp->setWorkflowtemplateId($this->station->workflowtemplate_id);
                 $wfp->setWorkflowversionId($this->station->version_id);
                 $wfp->setWorkflowslotId($nextUser[0]['workflowslot_id']);
                 $wfp->save();
                 $wfoId = $wfp->getId();

                 $wfpu = new WorkflowProcessUser();
                 $wfpu->setWorkflowprocessId($wfoId);
                 $wfpu->setWorkflowslotuserId($nextUser[0]['id']);
                 $wfpu->setUserId($nextUser[0]['user_id']);
                 $wfpu->setInprogresssince(time());
                 $wfpu->setDecissionstate('SKIPPED');
                 $wfpu->setResendet(0);
                 $wfpu->save();
                 $this->calculateStation($nextUser[0]['workflowslot_id'], $nextUser[0]['position']+1);
             }
             else {
                 $wfp = new WorkflowProcess();
                 $wfp->setWorkflowtemplateId($this->station->workflowtemplate_id);
                 $wfp->setWorkflowversionId($this->station->version_id);
                 $wfp->setWorkflowslotId($nextUser[0]['workflowslot_id']);
                 $wfp->save();
                 $wfoId = $wfp->getId();

                 $wfpu = new WorkflowProcessUser();
                 $wfpu->setWorkflowprocessId($wfoId);
                 $wfpu->setWorkflowslotuserId($nextUser[0]['id']);
                 $wfpu->setUserId($nextUser[0]['user_id']);
                 $wfpu->setInprogresssince(time());
                 $wfpu->setDecissionstate('WAITING');
                 $wfpu->setResendet(0);
                 $wfpu->save();
                 return true;
             }
         }
         else {
            $this->calculateSlot($workflowslot_id);
         }
    }
















}
?>