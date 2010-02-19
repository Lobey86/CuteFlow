<?php

/**
 * createworkflow actions.
 *
 * @package    cf
 * @subpackage createworkflow
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class createworkflowActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    public function executeCreateWorkflow(sfWebRequest $request) {        

        $this->getResponse()->setHttpHeader('Content-type', 'text/plain');
        sfLoader::loadHelpers('Url');
        //$response->setStatusCode(200);

        $createWorkObj = new PrepareWorkflowData();
        $data = array();
        $startDate = array();
        $userslot_id = array();

        $endreason = $createWorkObj->createEndreason($request->getPostParameter('createWorkflowFirstTabSettings', array()));
        $startDate = $createWorkObj->createStartDate($request->getPostParameter('createWorkflowFirstTab_datepicker'));
        $content = $createWorkObj->createContenttype($request->getPostParameters());

        $sendToAllSlotsAtOnce = MailinglistVersionTable::instance()->getActiveVersionById($request->getPostParameter('createWorkflowFirstTab_mailinglist'))->toArray();
        

        $workflow = new WorkflowTemplate();
        $workflow->setMailinglisttemplateversionId($sendToAllSlotsAtOnce[0]['id']);
        $workflow->setName($request->getPostParameter('createWorkflowFirstTab_name'));
        $workflow->setSenderId($this->getUser()->getAttribute('id'));
        $workflow->setIsarchived(0);
        $workflow->setIsstopped(0);
        $workflow->setIscompleted(0);
        $workflow->setEndaction($endreason);
        $workflow->save();
        $workflow_id = $workflow->getId();

        $workflowtemplate = new WorkflowVersion();
        $workflowtemplate->setWorkflowtemplateId($workflow_id);
        $workflowtemplate->setActiveversion(1);
        $workflowtemplate->setContent($content['content']);
        $workflowtemplate->setStartworkflowAt($startDate['startworkflowat']);
        $workflowtemplate->setContenttype($content['contenttype']);
        $workflowtemplate->setVersion(1);
        $workflowtemplate->setWorkflowisstarted($startDate['workflowisstarted']);
        $workflowtemplate->save();
        $template_id = $workflowtemplate->getId();


        $data = $request->getPostParameter('slot');
        $slotposition = 1;

        foreach($data as $slot) {
            $slotObj = new WorkflowSlot();
            $slotObj->setSlotId($slot['slot']['id']);
            $slotObj->setWorkflowversionId($template_id);
            $slotObj->setPosition($slotposition++);
            $slotObj->save();
            $slot_id = $slotObj->getId();
            $users = $slot['user'];
            $fields = $slot['slot']['field'];
            $userposition = 1;
            foreach($users as $user) {
                $userObj = new WorkflowSlotUser();
                $userObj->setWorkflowslotId($slot_id);
                $user['id'] = $user['id'] == -2 ? $this->getUser()->getAttribute('id') : $user['id'];
                $userObj->setUserId($user['id']);
                $userObj->setPosition($userposition++);
                $userObj->save();
                if(($slotposition-1) == 1) {
                    $userslot_id[] = $userObj->getId();
                }
            }
            $fieldposition = 1;
            foreach($fields as $field) {
                $fieldObj = new WorkflowSlotField();
                $fieldObj->setWorkflowslotId($slot_id);
                $fieldObj->setFieldId($field['field_id']);
                $fieldObj->setPosition($fieldposition++);
                $fieldObj->save();
                $field_id = $fieldObj->getId();
                switch ($field['type']) {
                    case 'TEXTFIELD':
                        $textfield = new WorkflowSlotFieldTextfield();
                        $textfield->setWorkflowslotfieldId($field_id);
                        $textfield->setValue($field['value']);
                        $textfield->save();
                        break;
                    case 'CHECKBOX':
                        $textfield = new WorkflowSlotFieldCheckbox();
                        $textfield->setWorkflowslotfieldId($field_id);
                        $textfield->setValue($field['value'] == 'true' ? 1 : 0);
                        $textfield->save();
                        break;
                    case 'NUMBER':
                        $textfield = new WorkflowSlotFieldNumber();
                        $textfield->setWorkflowslotfieldId($field_id);
                        $textfield->setValue($field['value']);
                        $textfield->save();
                        break;
                    case 'DATE':
                        $textfield = new WorkflowSlotFieldDate();
                        $textfield->setWorkflowslotfieldId($field_id);
                        $textfield->setValue($field['value']);
                        $textfield->save();
                        break;
                    case 'TEXTAREA':
                        $textfield = new WorkflowSlotFieldTextarea();
                        $textfield->setWorkflowslotfieldId($field_id);
                        $textfield->setValue($field['value']);
                        $textfield->save();
                        break;
                    case 'RADIOGROUP':
                        $items = $field['item'];
                        $counter = 1;
                        foreach($items as $item) {
                            $userObj = new WorkflowSlotFieldRadiogroup();
                            $userObj->setWorkflowslotfieldId($field_id);
                            $userObj->setFieldradiogroupId($item['id']);
                            $userObj->setValue($item['value'] == 'true' ? 1 : 0);
                            $userObj->setPosition($counter++);
                            $userObj->save();
                        }
                        break;
                    case 'CHECKBOXGROUP':
                        $items = $field['item'];
                        $counter = 1;
                        foreach($items as $item) {
                            $userObj = new WorkflowSlotFieldCheckboxgroup();
                            $userObj->setWorkflowslotfieldId($field_id);
                            $userObj->setFieldcheckboxgroupId($item['id']);
                            $userObj->setValue($item['value'] == 'true' ? 1 : 0);
                            $userObj->setPosition($counter++);
                            $userObj->save();
                        }
                        break;
                    case 'COMBOBOX':
                        $items = $field['item'];
                        $counter = 1;
                        foreach($items as $item) {
                            $userObj = new WorkflowSlotFieldCombobox();
                            $userObj->setWorkflowslotfieldId($field_id);
                            $userObj->setFieldcomboboxId($item['id']);
                            $userObj->setValue($item['value'] == 'true' ? 1 : 0);
                            $userObj->setPosition($counter++);
                            $userObj->save();
                        }
                        break;
                    case 'FILE':
                        $fieldToStore =  $field['filearray'];
                        $allFiles = $_FILES;
                        
                        $file = $allFiles[$fieldToStore];
                        $upload = new FileUpload();
                        $upload->uploadFormFile($file, $field_id,$template_id,$workflow_id);
                    }
            }

        }
        
        $files = $_FILES;
        $keys = array();
        $keys = array_keys($files);

        for($a=0;$a<count($keys);$a++) {
	$key = $keys[$a];
            if(substr_count($key, 'uploadfile') == 1) {
                $fileUpload = new FileUpload();
                $fileUpload->uploadFile($files[$key],$template_id,$workflow_id);
            }
        }

        $context = sfContext::getInstance();
        $context->getConfiguration()->loadHelpers('Partial', 'I18N', 'Url', 'Date', 'CalculateDate', 'ColorBuilder', 'Icon', 'EndAction');
        $sendToAllSlotsAtOnce = MailinglistVersionTable::instance()->getActiveVersionById($request->getPostParameter('createWorkflowFirstTab_mailinglist'))->toArray();
        if($startDate['workflowisstarted'] == 1) {
            if($sendToAllSlotsAtOnce[0]['sendtoallslotsatonce'] == 1) {
                $calc = new CreateWorkflow($template_id);
                $calc->setContext($context);
                $calc->setServerUrl(str_replace('/layout', '', url_for('layout/index',true)));
                $calc->addAllSlots();
            }
            else {
                $calc = new CreateWorkflow($template_id);
                $calc->setContext($context);
                $calc->setServerUrl(str_replace('/layout', '', url_for('layout/index',true)));
                $calc->addSingleSlot();
            }
        }
        $this->getResponse()->setHttpHeader('Content-Type','text/html; charset=utf-8');
        $json = array('success' => true);
        $string = '<textarea>'.json_encode($json).'</textarea>';
        $this->renderText($string);
        return sfView::NONE;
    }


    /**
     * Load all mailinglists
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $data = MailinglistTemplateTable::instance()->getAllMailinglistTemplates(-1,-1);
        $data = MailinglistTemplateTable::instance()->getAllowedMailinglistTemplates($this->getUser()->getAttribute('id'));
        $json_result = $mailinglist->buildAllMailinglists($data);
        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }



    public function executeLoadAllField(sfWebRequest $request) {
        $workflowObj = new Workflow();
        $workflowObj->setContext($this->getContext());
        $mailinglistVersion = MailinglistVersionTable::instance()->getSingleVersionById($request->getParameter('id'))->toArray();
        $documenttemplateVersion = DocumenttemplateVersionTable::instance()->getVersionById($mailinglistVersion[0]['documenttemplateversion_id'])->toArray();
        $result = $workflowObj->buildSlots($documenttemplateVersion);
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }


}
