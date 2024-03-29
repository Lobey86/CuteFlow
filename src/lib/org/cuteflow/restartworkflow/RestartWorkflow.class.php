<?php


/**
 * Class restarts a workflow
 */
class RestartWorkflow {


    private $newValue;
    public $context;
    public $serverUrl;

    public function  __construct() {
        
    }


    public function setContext(sfContext $context) {
        $this->context = $context;
    }

    public function setServerUrl($url) {
        $this->serverUrl = $url;
    }

    /**
     * Set flag, if the workflow uses values form previous version or not!
     * @param boolean $value
     */
    public function setNewValue($value) {
        $this->newValue = $value;
    }

    /**
     * Loads all users and slots for a worklfow.
     * Out of this function a specific user in a slot can be seleced, where worfklwo will start
     *
     * @param Doctrine_Collection $data
     * @return array $result, Slot und Users
     */
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


    /**
     * Add Users to a slot
     *
     * @param String $slotname, slotname
     * @param int $workflowslot_id, id of the slot
     * @param int $slotcounter, number of the slot
     * @return array $result
     */
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


    /**
     *
     * Build out of the tree with slots and users a single branch, that contains only users
     * which are linked to a slot. So this datastrcutre can be used in a GroupingGrid
     *
     * @param array $data
     * @return array $result
     */
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


    /**
     * Function loads all slots, fields and users for a slot and returns all slots for a workflow
     * @param Doctrine_Collection $data
     * @return array $result
     */
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


    /**
     * Get all Fields for a slot
     * @param int $slot_id, id of the slot
     * @return array $result
     */
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

    /**
     * Add all fields with its values to the slot. Value depends on the flag if old values are used or not
     * @param <type> $type
     * @param <type> $field_id
     * @param <type> $workflowslotfield_id
     * @return <type>
     */
    public function getItems($type, $field_id, $workflowslotfield_id) {
        $result = array();
        $a = 0;
        switch($type) {
            case 'TEXTFIELD':
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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
                if($this->newValue == 0) {
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



    /**
     * Get the workflowprocess and workflowprocessuser entries of an old version, to rebuild the structure
     * when workflow will start at current station
     *
     * @param int $version_id, old id of the workflow
     * @return array $result
     */
    public function getRestartData($version_id) {
        $result = array();
        $a = 0;
        // load the slots
        $slots = WorkflowSlotTable::instance()->getSlotByVersionId($version_id);
        foreach($slots as $slot) {
            $documentSlot = $slot->getDocumenttemplateSlot()->toArray();
            $b = 0;
            $result[$a]['slot_id'] = $slot->getId();
            $result[$a]['sendtoallreceivers'] = $documentSlot[0]['sendtoallreceivers'];
            $result[$a]['version_id'] = $slot->getWorkflowversionId();
            // load the processtable
            $wfProcess = WorkflowProcessTable::instance()->getWorkflowProcessBySlotId($slot->getId())->toArray();
            if(!empty($wfProcess)) {
                foreach($wfProcess as $process) {
                    $result[$a]['userprocess'][$b]  = $process;
                    // add its processes for a user
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
        return $result;
    }





    /**
     * Load all Workflowproecsses and Workflowprcessuser entries
     *
     * @param array $wfProcess, processdata
     * @return array $result
     */
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





    /**
     *
     * This function writes the structure of workflowprocess and workflowprocessusers of an old version and
     * replaces this structre with the id's of the new version and its slots, and users
     *
     * @param array $lastStationData, data of the old workflow with its processes and processusers
     * @param array $newData, contains the id's of the new users and slots
     * @param <type> $version_id
     * @param <type> $workflow_id
     */
    public function restartAtLastStation(array $lastStationData, array $newData, $version_id, $workflow_id) {
        for($a = 0;$a<count($lastStationData);$a++) {
            $lastSlots = $lastStationData[$a];
            $newSlots = $newData[$a];

            if($lastSlots['userprocess'] != '') {
                for($b=0;$b<count($lastSlots['userprocess']);$b++) {
                    $lastProcess = $lastSlots['userprocess'][$b];
                    if(isset($lastProcess['process']) == true) {
                        $wfProcess = new WorkflowProcess(); // write the process
                        $wfProcess->setWorkflowtemplateId($workflow_id); // wf id
                        $wfProcess->setWorkflowversionId($version_id); // new id of the worklflow
                        $wfProcess->setWorkflowslotId($newSlots['slot_id']); //the id of the new slot is used
                        $wfProcess->save();
                        $wfprocessId = $wfProcess->getId();
                        $newProcessUser = $newSlots['slotuser_id'][$b];
                        $processCounter = 0;

                        // write processes of the user
                        for($c=0;$c<count($lastProcess['process']);$c++){
                            $lastProcessUser = $lastProcess['process'][$c];
                            $user_id = $lastProcessUser['user_id'];
                            $wfsUid = $newProcessUser['id'];

                            // create the new states of the
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
                            else if ($lastProcessUser['decissionstate'] == 'DONE') {
                                $setDecission = 'DONE';
                            }
                            else if ($lastProcessUser['decissionstate'] == 'ARCHIVED') {
                                $setDecission = 'ARCHIVED';
                            }
                            else {
                                $setDecission = 'SKIPPED';
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

                            if($setDecission == 'WAITING') {
                                $mail = new PrepareStationEmail($version_id, $workflow_id, $user_id, $this->context, $this->serverUrl);
                            }

                        }
                    }
                }
            }
        }
    }


    /**
     * Function retuns flag if slot is send to all at once
     * @param int $versionid, version id
     * @return boolean
     */
    public function getSendToAllSlots($versionid) {
        $template = WorkflowTemplateTable::instance()->getWorkflowTemplateByVersionId($versionid)->toArray();
        $mailinglist = MailinglistTemplateTable::instance()->getMailinglistByVersionId($template[0]['mailinglisttemplateversion_id']);
        return $mailinglist[0]['MailinglistVersion']['sendtoallslotsatonce'];
    }






}










?>