<?php



class RestartWorkflow {


    private $newValue;

    public function  __construct() {
        
    }

    public function setNewValue($value) {
        $this->newValue = $value;
    }


    public function buildSelectStation(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $slotname = $item->getDocumenttemplateSlot()->toArray();
            $result[$a]['workflowslot_id'] = $item->getId();
            $result[$a]['workflowtemplate_id'] = $item->getWorkflowversionId();
            $result[$a]['slotposition'] = $item->getPosition();
            $result[$a]['slotname'] = $slotname[0]['name'];
            $result[$a]['sendtoallreceivers'] = $slotname[0]['sendtoallreceivers'];
            $result[$a++]['user'] = $this->getUser($slotname[0]['name'], $item->getId(), $a+1);


        }
        $result = $this->mergeArray($result);
        return $result;
    }



    public function getUser($slotname, $workflowslot_id, $slotcounter) {
        $users = WorkflowSlotUserTable::instance()->getUserBySlotId($workflowslot_id);
        $result = array();
        $a = 0;
        foreach($users as $user) {
            $userLogin = UserLoginTable::instance()->findActiveUserById($user->getUserId());
            $userData = $userLogin[0]->getUserData()->toArray();
            $result[$a]['workflowslotuser_id'] = $user->getId();
            $result[$a]['user_id'] = $user->getUserId();
            $result[$a]['userposition'] = $user->getPosition();
            $result[$a]['slotgroup'] = '#' . ($slotcounter-1) . ' : ' . $slotname;
            $result[$a]['plainusername'] = $userData['firstname'] . ' ' . $userData['lastname'];
            $result[$a]['username'] = $userData['firstname'] . ' ' . $userData['lastname'] . ' <i>'.$userLogin[0]->getUsername().'</i>';
            $a++;
        }
        return $result;
    }



    public function mergeArray(array $data) {
        $result = array();
        $c = 0;
        for($a=0;$a<count($data);$a++) {

            for($b=0;$b<count($data[$a]['user']);$b++) {
                $user = $data[$a]['user'][$b];
                $result[$c] = $user;
                $result[$c]['workflowslot_id'] = $data[$a]['workflowslot_id'];
                $result[$c]['slotposition'] = $data[$a]['slotposition'];
                $result[$c]['workflowtemplate_id'] = $data[$a]['workflowtemplate_id'];
                $result[$c]['sendtoallreceivers'] = $data[$a]['sendtoallreceivers'];
                $result[$c++]['slotname'] = $data[$a]['slotname'];
            }
            
        }
        return $result;
    }





    public function buildSaveData(Doctrine_Collection $data) {
        $a = 0;

        foreach($data as $slot) {
            $result[$a]['id'] = $slot->getId();
            $result[$a]['workflowversion_id'] = $slot->getWorkflowversionId();
            $result[$a]['slot_id'] = $slot->getSlotId();
            $result[$a]['position'] = $slot->getPosition();
            $result[$a]['fields'] = $this->getFields($slot->getId());
            $result[$a]['users'] = WorkflowSlotUserTable::instance()->getUserBySlotId($slot->getId())->toArray();
            $a++;


        }
        return $result;
    }



    public function getFields($slot_id) {
        $result = array();
        $a = 0;
        $fields = WorkflowSlotFieldTable::instance()->getWorkflowSlotFieldBySlotId($slot_id);
        foreach($fields as $field) {
            $documentField = $field->getField()->toArray();
            $result[$a]['id'] = $field->getId();
            $result[$a]['workflowslot_id'] = $field->getWorkflowslotId();
            $result[$a]['field_id'] = $field->getFieldId();
            $result[$a]['position'] = $field->getPosition();
            $result[$a]['type'] = $documentField[0]['type'];
            $result[$a]['items'] = $this->getItems($documentField[0]['type'],$field->getFieldId(),$field->getId());
            $a++;
        }
        return $result;
    }


    public function getItems($type, $field_id, $workflowslotfield_id) {
        $result = array();
        $a = 0;
        $this->newValue = 1;
        switch($type) {
            case 'TEXTFIELD':
                if($this->newValue == 1) {
                    $data = FieldTextfieldTable::instance()->getTextfieldByFieldId($field_id)->toArray();
                    $result[0]['value'] = $data[0]['defaultvalue'];
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldTextfieldTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    $result[0]['value'] = $data[0]['value'];
                    return $result;
                }
                break;
            case 'CHECKBOX':
                if($this->newValue == 1) {
                    $result[0]['value'] = 0;
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldCheckboxTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    $result[0]['value'] = $data[0]['value'];
                    return $result;
                }
                break;
            case 'NUMBER':
                if($this->newValue == 1) {
                    $data = FieldNumberTable::instance()->getNumberByFieldId($field_id)->toArray();
                    $result[0]['value'] = $data[0]['defaultvalue'];
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldNumberTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    $result[0]['value'] = $data[0]['value'];
                    return $result;
                }
                break;
            case 'DATE':
                if($this->newValue == 1) {
                    $data = FieldDateTable::instance()->getDateByFieldId($field_id)->toArray();
                    $result[0]['value'] = $data[0]['defaultvalue'];
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldDateTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    $result[0]['value'] = $data[0]['value'];
                    return $result;
                }
                break;
            case 'TEXTAREA':
                if($this->newValue == 1) {
                    $data = FieldTextareaTable::instance()->getTextareaByFieldId($field_id)->toArray();
                    $result[0]['value'] = $data[0]['content'];
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldTextareaTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    $result[0]['value'] = $data[0]['value'];
                    return $result;
                }
                break;
            case 'RADIOGROUP':
                if($this->newValue == 1) {
                    $data = FieldRadiogroupTable::instance()->findRadiogroupByFieldId($field_id)->toArray();
                    foreach($data as $item) {
                        $result[$a]['value'] = $item['isactive'];
                        $result[$a]['fieldradiogroup_id'] = $item['id'];
                        $result[$a++]['position'] = $item['position'];
                    }
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldRadiogroupTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    foreach($data as $item) {
                        $result[$a]['value'] = $item['value'];
                        $result[$a]['fieldradiogroup_id'] = $item['fieldradiogroup_id'];
                        $result[$a++]['position'] = $item['position'];
                    }
                    return $result;
                }
                break;
            case 'CHECKBOXGROUP':
                if($this->newValue == 1) {
                    $data = FieldCheckboxgroupTable::instance()->findCheckboxgroupByFieldId($field_id)->toArray();
                    foreach($data as $item) {
                        $result[$a]['value'] = $item['isactive'];
                        $result[$a]['fieldradiogroup_id'] = $item['id'];
                        $result[$a++]['position'] = $item['position'];
                    }
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldCheckboxgroupTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    foreach($data as $item) {
                        $result[$a]['value'] = $item['value'];
                        $result[$a]['fieldradiogroup_id'] = $item['fieldcheckboxgroup_id'];
                        $result[$a++]['position'] = $item['position'];
                    }
                    return $result;
                }
                break;
            case 'COMBOBOX':
                if($this->newValue == 1) {
                    $data = FieldComboboxTable::instance()->findComboboxByFieldId($field_id)->toArray();
                    foreach($data as $item) {
                        $result[$a]['value'] = $item['isactive'];
                        $result[$a]['fieldradiogroup_id'] = $item['id'];
                        $result[$a++]['position'] = $item['position'];
                    }
                    return $result;
                }
                else {
                    $data = WorkflowSlotFieldComboboxTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                    foreach($data as $item) {
                        $result[$a]['value'] = $item['value'];
                        $result[$a]['fieldradiogroup_id'] = $item['fieldcombobox_id'];
                        $result[$a++]['position'] = $item['position'];
                    }
                    return $result;
                }
                break;
            case 'FILE':
                $data = WorkflowSlotFieldFileTable::instance()->getAllItemsByWorkflowFieldId($workflowslotfield_id)->toArray();
                return $data;
                break;
        }
    }




    public function getRestartData($version_id) {
        $result = array();
        $a = 0;
       
        $slots = WorkflowSlotTable::instance()->getSlotByVersionId($version_id);
        foreach($slots as $slot) {
            $documentSlot = $slot->getDocumenttemplateSlot()->toArray();
            $b = 0;
            $result[$a]['slot_id'] = $slot->getId();
            $result[$a]['sendtoallreceivers'] = $documentSlot[0]['sendtoallreceivers'];
            $result[$a]['version_id'] = $slot->getWorkflowversionId();
            $wfProcess = WorkflowProcessTable::instance()->getWorkflowProcessBySlotId($slot->getId())->toArray();
            if(!empty($wfProcess)) {
                foreach($wfProcess as $process) {
                    $result[$a]['userprocess'][$b]  = $process;
                    $result[$a]['userprocess'][$b]['process'] = $this->addWorkflowProcessUser($process);
                    $b++;
                }

            }
            else {
                $result[$a]['userprocess'][$b] = '';
                $b++;
            }
            $a++;
        }
        #print_r ($result);die;
        return $result;
    }






    public function addWorkflowProcessUser(array $wfProcess) {
        $result = array();
        $userprocess = WorkflowProcessUserTable::instance()->getWorkflowProcessUserByWorklflowProcessId($wfProcess['id'])->toArray();
        $a = 0;
        foreach ($userprocess as $process) {
            $result[$a]['decissionstate'] = $process['decissionstate'];
            $result[$a]['isuseragentof'] = $process['isuseragentof'];
            $result[$a++]['user_id'] = $process['user_id'];
        }
        return $result;
    }






    public function restartAtLastStation(array $lastStationData, array $newData, $version_id, $workflow_id) {
        #print_r ($lastStationData);
        #print_r ($newData);die;


        for($a = 0;$a<count($lastStationData);$a++) {
            $lastSlots = $lastStationData[$a];
            $newSlots = $newData[$a];

            if($lastSlots['userprocess'] != '') {
                for($b=0;$b<count($lastSlots['userprocess']);$b++) {
                    $lastProcess = $lastSlots['userprocess'][$b];
                    $wfProcess = new WorkflowProcess();
                    $wfProcess->setWorkflowtemplateId($workflow_id);
                    $wfProcess->setWorkflowversionId($version_id);
                    $wfProcess->setWorkflowslotId($newSlots['slot_id']);
                    $wfProcess->save();
                    $wfprocessId = $wfProcess->getId();
                    $newProcessUser = $newSlots['slotuser_id'][$b];
                    $processCounter = 0;
                    
                    for($c=0;$c<count($lastProcess['process']);$c++){
                        
                        $lastProcessUser = $lastProcess['process'][$c];
                        $user_id = $lastProcessUser['user_id'];
                        $wfsUid = $newProcessUser['id'];
                        /*if($lastSlots['sendtoallreceivers'] == 1) {
                            $wfsUid = $newSlots['slotuser_id'][($b+$processCounter)]['id'];
                            if($lastProcessUser['decissionstate'] != 'USERAGENTSET') {
                                $processCounter++;
                            }
                            $processCounter++;
                        }
                        else {
                            $wfsUid = $newProcessUser['id'];
                        }*/

                        if($lastProcessUser['decissionstate'] == 'STOPPEDBYADMIN' OR $lastProcessUser['decissionstate'] == 'STOPPEDBYUSER') {
                            $setDecission = 'WAITING';
                        }
                        else if ($lastProcessUser['decissionstate'] == 'WAITING') {
                            $setDecission = 'WAITING';
                        }
                        else if ($lastProcessUser['decissionstate'] == 'SKIPPED') {
                            $setDecission = 'SKIPPED';
                        }
                        else if ($lastProcessUser['decissionstate'] == 'USERAGENTSET') {
                            $setDecission = 'USERAGENTSET';
                        }
                        else {
                            
                        }
                        $wfProcessUser = new WorkflowProcessUser();
                        $wfProcessUser->setWorkflowprocessId($wfprocessId);
                        $wfProcessUser->setWorkflowslotuserId($wfsUid);
                        $wfProcessUser->setUserId($user_id);
                        $wfProcessUser->setInprogresssince(time());
                        $wfProcessUser->setDecissionstate($setDecission);
                        $wfProcessUser->setDateofdecission(time());
                        $wfProcessUser->setResendet(0);
                        $wfProcessUser->setIsuseragentof($lastProcessUser['isuseragentof']);
                        $wfProcessUser->save();

                    }
                }
            }
        }
        



    }






}










?>