<?php



class SetStation {

    public $version_id;
    public $workflowtemplate_id;
    public $newWorkflowSlotUser_id;
    public $currentWorkflowSlotUser_id;
    public $move;

    public $currenWorkflowSlot;
    public $currentWorkflowSlotUser;

    public $newWorkflowSlot;
    public $newWorkflowSlotUser;


    public function  __construct($version_id, $newWorkflowSlotUser_id, $currentWorkflowSlotUser_id, $move) {
        $this->version_id = $version_id;
        $this->newWorkflowSlotUser_id = $newWorkflowSlotUser_id;
        $this->currentWorkflowSlotUser_id = $currentWorkflowSlotUser_id;
        $this->move = $move;
        $this->setWorkflowTemplateId();
        $this->setCurrentWorkflowSlotUser();
        $this->setCurrentWorkflowSlot();
        $this->setNewWorkflowslotUser();
        $this->setNewSlot();
        $this->makeDecission();
    }


    public function setWorkflowTemplateId() {
        $template = WorkflowVersionTable::instance()->getWorkflowVersionById($this->version_id);
        $this->workflowtemplate_id = $template[0]->getWorkflowtemplateId();
    }

    public function setNewWorkflowslotUser() {
        $user = WorkflowSlotUserTable::instance()->getUserById($this->newWorkflowSlotUser_id);
        $this->newWorkflowSlotUser = $user[0];
    }

    public function setNewSlot() {
        $slot = WorkflowSlotTable::instance()->getSlotById($this->newWorkflowSlotUser->getWorkflowslotId());
        $this->newWorkflowSlot = $slot[0];
    }


    public function setCurrentWorkflowSlotUser() {
        $user = WorkflowSlotUserTable::instance()->getUserById($this->currentWorkflowSlotUser_id);
        $this->currentWorkflowSlotUser = $user[0];
    }


    public function setCurrentWorkflowSlot() {
        $slot = WorkflowSlotTable::instance()->getSlotById($this->currentWorkflowSlotUser->getWorkflowslotId());
        $this->currenWorkflowSlot = $slot[0];
    }



    public function makeDecission() {
        if($this->move == 'DOWN') {
            $calc = new MoveDown($this);
        }
        else { // move = UP
            $calc = new MoveUp($this);
        }
        
    }


    

    










}

















?>