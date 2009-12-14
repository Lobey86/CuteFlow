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
            $result[$a]['slotgroup'] = '#' . $slotcounter . ' : ' . $slotname;
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
                break;
        }
    }




    public function startAtLastStation($version_id) {
        $result = array();
        $a = 0;
        $processData = WorkflowProcessTable::instance()->getWorkflowProcessByVersionId($version_id);
        foreach($processData as $process) {
            $userProcess = WorkflowProcessUserTable::instance()->getProcessUserByWorkflowSlotUserId($process->getId())->toArray();
            $result[$a++]['userprocess'] = $this->addWorkflowProcessUser($userProcess);
        }

        print_r ($result);die;
    }


    public function addWorkflowProcessUser(array $userProcess) {
        $result = array();
        $a = 0;
        foreach ($userProcess as $process) {
            $result[$a]['decissionstate'] = $process['decissionstate'];
            $result[$a]['isuseragentof'] = $process['isuseragentof'];
            $result[$a++]['user_id'] = $process['user_id'];
        }
        return $result;
    }













}










?>