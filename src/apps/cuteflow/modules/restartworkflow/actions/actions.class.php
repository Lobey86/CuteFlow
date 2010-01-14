<?php

/**
 * restartworkflow actions.
 *
 * @package    cf
 * @subpackage restartworkflow
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class restartworkflowActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request){
        $this->forward('default', 'module');
    }



    public function executeLoadAllStation(sfWebRequest $request) {
        $workObj = new RestartWorkflow();
        $data = WorkflowSlotTable::instance()->getSlotByVersionId($request->getParameter('versionid'));
        $json_data = $workObj->buildSelectStation($data);
        $this->renderText('{"result" : '.json_encode($json_data).'}');
        return sfView::NONE;
    }



    public function executeRestartWorkflow(sfWebRequest $request) {
        if($request->getPostParameter('restartWorkflowFirstTabSettings') != 'BEGINNING' AND $request->getPostParameter('restartWorkflowFirstTabSettings') != 'LASTSTATION') {
            $slotOrder = array();
            $slotOrder = explode('__', $request->getPostParameter('restartWorkflowFirstTab_startpointid'));
        }

        
        
        $createWorkObj = new PrepareWorkflowData();
        $startDate = array();

        $version_id = $request->getParameter('versionid');
        $newValue = $request->getParameter('restartWorkflowFirstTab_useoldvalues',0);
        $endreason = $createWorkObj->createEndreason($request->getPostParameter('restartWorkflowFirstTabSettings', array()));
        $startDate = $createWorkObj->createStartDate('');
        $content = $createWorkObj->createRestartContenttype($request->getPostParameters());

        $workflowtemplate_id = WorkflowVersionTable::instance()->getWorkflowVersionById($version_id)->toArray();
        WorkflowTemplateTable::instance()->updateEndaction($workflowtemplate_id,$endreason);
        $currentVersion = WorkflowVersionTable::instance()->getLastVersionById($workflowtemplate_id[0]['workflowtemplate_id'])->toArray();
        $slots = WorkflowSlotTable::instance()->getSlotByVersionId($version_id);

        WorkflowVersionTable::instance()->setVersionInactive($version_id);
        WorkflowTemplateTable::instance()->restartWorkflow($workflowtemplate_id[0]['workflowtemplate_id']);

        $wfRestart = new RestartWorkflow();
        $wfRestart->setNewValue($newValue);
        $data = $wfRestart->buildSaveData($slots);

        $wfVersion = new WorkflowVersion();
        $wfVersion->setWorkflowtemplateId($workflowtemplate_id[0]['workflowtemplate_id']);
        $wfVersion->setActiveversion(1);
        $wfVersion->setContent($content['content']);
        $wfVersion->setStartworkflowAt($startDate['startworkflowat']);
        $wfVersion->setContenttype($content['contenttype']);
        $wfVersion->setWorkflowisstarted($startDate['workflowisstarted']);
        $wfVersion->setVersion($currentVersion[0]['version']+1);
        $wfVersion->save();
        $newVersionId = $wfVersion->getId();
        
        $dataStore = array();
        $slotCounter = 0;

        foreach($data as $slot) {

            $singleSlot = new WorkflowSlot();
            $singleSlot->setWorkflowversionId($newVersionId);
            $singleSlot->setSlotId($slot['slot_id']);
            $singleSlot->setPosition($slot['position']);
            $singleSlot->save();

            $slotId = $singleSlot->getId();
            $dataStore[$slotCounter]['slot_id'] = $slotId;

            $fields = $slot['fields'];
            $users = $slot['users'];

            foreach($fields as $field) {
                $newField = new WorkflowSlotField();
                $newField->setWorkflowslotId($slotId);
                $newField->setFieldId($field['field_id']);
                $newField->setPosition($field['position']);
                $newField->save();
                $fieldId = $newField->getId();
                switch($field['type']) {
                    case 'TEXTFIELD':
                        $newField = new WorkflowSlotFieldTextfield();
                        $newField->setWorkflowslotfieldId($fieldId);
                        $newField->setValue($field['items'][0]['value']);
                        $newField->save();
                        break;
                    case 'CHECKBOX':
                        $newField = new WorkflowSlotFieldCheckbox();
                        $newField->setWorkflowslotfieldId($fieldId);
                        $newField->setValue($field['items'][0]['value']);
                        $newField->save();
                        break;
                    case 'NUMBER':
                        $newField = new WorkflowSlotFieldNumber();
                        $newField->setWorkflowslotfieldId($fieldId);
                        $newField->setValue($field['items'][0]['value']);
                        $newField->save();
                        break;
                    case 'DATE':
                        $newField = new WorkflowSlotFieldDate();
                        $newField->setWorkflowslotfieldId($fieldId);
                        $newField->setValue($field['items'][0]['value']);
                        $newField->save();
                        break;
                    case 'TEXTAREA':
                        $newField = new WorkflowSlotFieldTextarea();
                        $newField->setWorkflowslotfieldId($fieldId);
                        $newField->setValue($field['items'][0]['value']);
                        $newField->save();
                        break;
                    case 'RADIOGROUP':
                        $items = $field['items'];
                        foreach($items as $item) {
                            $newField = new WorkflowSlotFieldRadiogroup();
                            $newField->setWorkflowslotfieldId($fieldId);
                            $newField->setFieldradiogroupId($item['fieldradiogroup_id']);
                            $newField->setValue($item['value']);
                            $newField->setPosition($item['position']);
                            $newField->save();
                        }
                        break;
                    case 'CHECKBOXGROUP':
                        $items = $field['items'];
                        foreach($items as $item) {
                            $newField = new WorkflowSlotFieldCheckboxgroup();
                            $newField->setWorkflowslotfieldId($fieldId);
                            $newField->setFieldcheckboxgroupId($item['fieldradiogroup_id']);
                            $newField->setValue($item['value']);
                            $newField->setPosition($item['position']);
                            $newField->save();
                        }
                        break;
                    case 'COMBOBOX':
                        $items = $field['items'];
                        foreach($items as $item) {
                            $newField = new WorkflowSlotFieldCombobox();
                            $newField->setWorkflowslotfieldId($fieldId);
                            $newField->setFieldcomboboxId($item['fieldradiogroup_id']);
                            $newField->setValue($item['value']);
                            $newField->setPosition($item['position']);
                            $newField->save();
                        }
                        break;
                    case 'FILE':
                        $moveFile = new FileUpload();
                        $moveFile->moveFile($field['items'][0], $newVersionId,$workflowtemplate_id[0]['workflowtemplate_id'], $request->getParameter('versionid'));
                        $newField = new WorkflowSlotFieldFile();
                        $newField->setWorkflowslotfieldId($fieldId);
                        $newField->setFilename($field['items'][0]['filename']);
                        $newField->setHashname($field['items'][0]['hashname']);
                        $newField->save();
                        break;
                    
                }
            }

            $userCounter = 0;
            foreach($users as $user) {

                $wfSlotUser = new WorkflowSlotUser();
                $wfSlotUser->setWorkflowslotId($slotId);
                $wfSlotUser->setPosition($user['position']);
                $wfSlotUser->setUserId($user['user_id']);
                $wfSlotUser->save();
                $dataStore[$slotCounter]['slotuser_id'][$userCounter]['id'] = $wfSlotUser->getId();
                $dataStore[$slotCounter]['slotuser_id'][$userCounter++]['user_id'] = $user['user_id'];
            }
            $slotCounter++;

        }

        $files = $_FILES;
        $keys = array();
        $keys = array_keys($files);

        for($a=0;$a<count($keys);$a++) {
	$key = $keys[$a];
            if(substr_count($key, 'uploadfile') == 1) {
                $fileUpload = new FileUpload();
                $fileUpload->uploadFile($files[$key],$newVersionId,$workflowtemplate_id[0]['workflowtemplate_id']);
            }
        }
        $workflowTemplate = WorkflowTemplateTable::instance()->getWorkflowTemplateByVersionId($version_id)->toArray();




        $sendToAllSlotsAtOnce = MailinglistVersionTable::instance()->getActiveVersionById($workflowTemplate[0]['mailinglisttemplateversion_id'])->toArray();
        if($request->getPostParameter('restartWorkflowFirstTab_startpoint') == 'BEGINNING'){
            if($sendToAllSlotsAtOnce[0]['sendtoallslotsatonce'] == 1) {
                $calc = new CreateWorkflow($newVersionId);
                $calc->addAllSlots();
            }
            else {
                $calc = new CreateWorkflow($newVersionId);
                $calc->addSingleSlot();
            }
        }
        else if ($request->getPostParameter('restartWorkflowFirstTab_startpoint') == 'LASTSTATION') {
            $wfRestart = new RestartWorkflow();
            $lastStationdata = $wfRestart->getRestartData($version_id);

            $wfRestart->restartAtLastStation($lastStationdata, $dataStore, $newVersionId, $workflowtemplate_id[0]['workflowtemplate_id']);
        }
        else {
            $slotOrder = array();
            $slotOrder = explode('__', $request->getPostParameter('restartWorkflowFirstTab_startpointid'));
            $slotPosition = $slotOrder[1]; // Slot Position worklfow must start
            $userPosition = $slotOrder[3]; // position of the user in the slot
            $currentUserSlotId = $dataStore[0]['slotuser_id'][0]['id']; // get Id of the first WorkflowSlot of the restarted Workflow
            $newUserSlotId = $dataStore[$slotPosition-1]['slotuser_id'][$userPosition-1]['id']; // get Id of the first WorkflowSlotUser of the restarted Workflow
            $direction = 'UP'; // direction is UP!


            // write first Process
            $wfProcess = new WorkflowProcess();
            $wfProcess->setWorkflowtemplateId($workflowtemplate_id[0]['workflowtemplate_id']);
            $wfProcess->setWorkflowversionId($newVersionId);
            $wfProcess->setWorkflowslotId($dataStore[0]['slot_id']);
            $wfProcess->save();
            $wfProcessId = $wfProcess->getId();

            // write first user
            $wfProcessUser = new WorkflowProcessUser();
            $wfProcessUser->setWorkflowprocessId($wfProcessId);
            $wfProcessUser->setWorkflowslotuserId($dataStore[0]['slotuser_id'][0]['id']);
            $wfProcessUser->setUserId($dataStore[0]['slotuser_id'][0]['user_id']);
            $wfProcessUser->setInprogresssince(time());
            $wfProcessUser->setDecissionstate('WAITING');
            $wfProcessUser->setDateofdecission(time());
            $wfProcessUser->setResendet(0);
            $wfProcessUser->save();
            $calc = new SetStation($newVersionId, $newUserSlotId, $currentUserSlotId, $direction);

        }
        #die;
        #echo '{"success":true}';die;
        $this->renderText('{"success":true}');
        return sfView::NONE;
    }


}
