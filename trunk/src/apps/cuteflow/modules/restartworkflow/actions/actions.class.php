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


        
        /*
        * $
        * wfRestart = new RestartWorkflow();
        $data = $wfRestart->startAtLastStation(1);


        die;*/
        $createWorkObj = new PrepareWorkflowData();
        $startDate = array();

        $version_id = $request->getParameter('versionid');
        $newValue = $request->getParameter('restartWorkflowFirstTab_useoldvalues',0);
        $endreason = $createWorkObj->createEndreason($request->getPostParameter('restartWorkflowFirstTabSettings', array()));
        $startDate = $createWorkObj->createStartDate('');
        $content = $createWorkObj->createRestartContenttype($request->getPostParameters());

        $workflowtemplate_id = WorkflowVersionTable::instance()->getWorkflowVersionById($version_id)->toArray();
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


        foreach($data as $slot) {
            $singleSlot = new WorkflowSlot();
            $singleSlot->setWorkflowversionId($newVersionId);
            $singleSlot->setSlotId($slot['slot_id']);
            $singleSlot->setPosition($slot['position']);
            $singleSlot->save();
            $slotId = $singleSlot->getId();
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
                        break;
                    
                }
            }


            foreach($users as $user) {
                $wfSlotUser = new WorkflowSlotUser();
                $wfSlotUser->setWorkflowslotId($slotId);
                $wfSlotUser->setPosition($user['position']);
                $wfSlotUser->setUserId($user['user_id']);
                $wfSlotUser->save();
            }

        }
        $workflowTemplate = WorkflowTemplateTable::instance()->getWorkflowTemplateByVersionId($version_id)->toArray();
        $files = $_FILES;
        $file1 = $files['restart_uploadfile1'];
        $file2 = $files['restart_uploadfile2'];
        $file3 = $files['restart_uploadfile3'];
        $file4 = $files['restart_uploadfile4'];
        $fileUpload = new FileUpload();
        $fileUpload->uploadFile($file1,$newVersionId,$workflowtemplate_id[0]['workflowtemplate_id']);
        $fileUpload->uploadFile($file2,$newVersionId,$workflowtemplate_id[0]['workflowtemplate_id']);
        $fileUpload->uploadFile($file3,$newVersionId,$workflowtemplate_id[0]['workflowtemplate_id']);
        $fileUpload->uploadFile($file4,$newVersionId,$workflowtemplate_id[0]['workflowtemplate_id']);

        $sendToAllSlotsAtOnce = MailinglistVersionTable::instance()->getActiveVersionById($workflowTemplate[0]['mailinglisttemplateversion_id'])->toArray();
        if($request->getPostParameter('restartWorkflowFirstTab_startpointid') == 'BEGINNING'){
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
            
        }
        else {

        }
        echo '{"success":true}';die;
        $this->renderText('{success:true}');
        return sfView::NONE;
    }


}
