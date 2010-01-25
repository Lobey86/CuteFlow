<?php

/**
 * workflowedit actions.
 *
 * @package    cf
 * @subpackage workflowedit
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class workfloweditActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }



    public function executeLoadWorkflowData(sfWebRequest $request) {
        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());
        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'));
        $generalData = $detailsObj->buildHeadLine($workflowsettings);
        $attachments = $detailsObj->buildAttachments($workflowsettings, $request->getParameter('versionid'));
        
        $slotObj = new WorkflowEdit();
        $slotObj->setUser($this->getUser());
        $slotObj->setCulture($this->getUser()->getCulture());
        $slotObj->setContext($this->getContext());
        $slotData = $slotObj->buildSlots($workflowsettings, $request->getParameter('versionid'));
        $this->renderText('{"generalData":'.json_encode($generalData).',"slotData":'.json_encode($slotData).', "workflowAttachment" : '.json_encode($attachments).'}');
        return sfView::NONE;
    }


    /**
     * Save the workflow out of an IFRAME
     */
    public function executeSaveIFrame (sfWebRequest $request) {
        $workflowSaveObj = new SaveWorkflow();
        $data = $request->getPostParameters();
        if($data['workfloweditAcceptWorkflow_decission'] == 1) {
            if(isset($data['field'])) {
                foreach($data['field'] as $field) {
                    switch ($field['type']) {
                        case 'TEXTFIELD':
                            WorkflowSlotFieldTextfieldTable::instance()->updateTextfieldByWorkflowFieldId($field['field_id'],$field['value']);
                            break;
                        case 'CHECKBOX':
                            $value = isset($field['value']) == true ? 1 : 0;
                            WorkflowSlotFieldCheckboxTable::instance()->updateCheckboxByWorkflowFieldId($field['field_id'],$value);
                            break;
                        case 'NUMBER':
                            WorkflowSlotFieldNumberTable::instance()->updateNumberByWorkflowFieldId($field['field_id'],$field['value']);
                            break;
                        case 'DATE':
                            WorkflowSlotFieldDateTable::instance()->updateDateByWorkflowFieldId($field['field_id'],$field['value']);
                            break;
                        case 'TEXTAREA':
                            WorkflowSlotFieldTextareaTable::instance()->updateTextareaByWorkflowFieldId($field['field_id'],$field['value']);
                            break;
                        case 'RADIOGROUP':
                            $radioGroupId = WorkflowSlotFieldRadiogroupTable::instance()->getRadiogroupById($field['field_id'])->toArray();
                            WorkflowSlotFieldRadiogroupTable::instance()->setToNullByFieldId($radioGroupId[0]['workflowslotfield_id']);
                            if(isset($field['id'])) {
                                WorkflowSlotFieldRadiogroupTable::instance()->updateRadiogroupById($field['id'],1);
                            }
                            break;
                        case 'CHECKBOXGROUP':
                            $checkGroupId = WorkflowSlotFieldCheckboxgroupTable::instance()->getCheckboxgroupById($field['field_id'])->toArray();
                            WorkflowSlotFieldCheckboxgroupTable::instance()->setToNullByFieldId($checkGroupId[0]['workflowslotfield_id']);
                            if(isset($field['items']) == true) {
                                foreach($field['items'] as $singleItem) {
                                    WorkflowSlotFieldCheckboxgroupTable::instance()->updateCheckboxgroupById($singleItem['id'],1);
                                }
                            }
                            break;
                        case 'COMBOBOX':
                            if($field['id'] != '') {
                                $comboboxGroupId = WorkflowSlotFieldComboboxTable::instance()->getComboboxById($field['id'])->toArray();
                                WorkflowSlotFieldComboboxTable::instance()->setToNullByFieldId($comboboxGroupId[0]['workflowslotfield_id']);
                                WorkflowSlotFieldComboboxTable::instance()->updateComboboxById($field['id'],1);
                            }
                            break;
                        case 'FILE':
                            break;
                        }

                }
            }
            $slots = $data['slot'];
            $workflowSaveObj->getNextStation($slots,$request->getParameter('userid'),$request->getParameter('versionid'));
        }
        else {
            $workflowSaveObj->denyWorkflow($data, $request->getParameter('workflowid'), $request->getParameter('userid'), $request->getParameter('versionid'));
        }
        return sfView::NONE;
    }






    public function executeSaveWorkflow(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $workflowEdit = new WorkflowEdit();
        if($data['workfloweditAcceptWorkflow_decission'] == 1) { // user accepted Workflow
            if(isset($data['field'])) {
                foreach($data['field'] as $field) {
                    switch ($field['type']) {
                    case 'TEXTFIELD':
                        WorkflowSlotFieldTextfieldTable::instance()->updateTextfieldByWorkflowFieldId($field['field_id'],$field['value']);
                        break;
                    case 'CHECKBOX':
                        $value = $field['value'] == 'false' ? 0 : 1;
                        WorkflowSlotFieldCheckboxTable::instance()->updateCheckboxByWorkflowFieldId($field['field_id'],$value);
                        break;
                    case 'NUMBER':
                        WorkflowSlotFieldNumberTable::instance()->updateNumberByWorkflowFieldId($field['field_id'],$field['value']);
                        break;
                    case 'DATE':
                        WorkflowSlotFieldDateTable::instance()->updateDateByWorkflowFieldId($field['field_id'],$field['value']);
                        break;
                    case 'TEXTAREA':
                        WorkflowSlotFieldTextareaTable::instance()->updateTextareaByWorkflowFieldId($field['field_id'],$field['value']);
                        break;
                    case 'RADIOGROUP':
                        $items = $field['item'];
                        foreach($items as $item) {
                            $value = $item['value'] == 'false' ? 0 : 1;
                            WorkflowSlotFieldRadiogroupTable::instance()->updateRadiogroupById($item['id'],$value);
                        }
                        break;
                    case 'CHECKBOXGROUP':
                        $items = $field['item'];
                        foreach($items as $item) {
                            $value = $item['value'] == 'false' ? 0 : 1;
                            WorkflowSlotFieldCheckboxgroupTable::instance()->updateCheckboxgroupById($item['id'],$value);
                        }
                        break;
                    case 'COMBOBOX':
                        $items = $field['item'];
                        foreach($items as $item) {
                            $value = $item['value'] == 'false' ? 0 : 1;
                            WorkflowSlotFieldComboboxTable::instance()->updateComboboxById($item['id'],$value);
                        }
                        break;
                    case 'FILE':
                        break;
                    }
                }
            }
            $slots = $data['slot'];
            $workflowSaveObj->getNextStation($slots,$this->getUser()->getAttribute('id'),$request->getParameter('versionid'));
        }
        else { // user denies workflow
            $workflowSaveObj->denyWorkflow($data, $request->getParameter('workflowid'), $this->getUser()->getAttribute('id'), $request->getParameter('versionid'));
        }
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

    









}
