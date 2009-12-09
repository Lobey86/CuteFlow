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
        
        $slotObj = new WorkflowEdit();
        $slotObj->setUser($this->getUser());
        $slotObj->setCulture($this->getUser()->getCulture());
        $slotObj->setContext($this->getContext());
        $slotData = $slotObj->buildSlots($workflowsettings, $request->getParameter('versionid'));
        
        #print_r ($slotData);die;
        $this->renderText('{"generalData":'.json_encode($generalData).',"slotData":'.json_encode($slotData).'}');
        return sfView::NONE;
    }



    public function executeSaveWorkflow(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $workflowEdit = new WorkflowEdit();
        if($data['workfloweditAcceptWorkflow_decission'] == 1) { // user accepted Workflow
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
            $slots = $data['slot'];
            foreach($slots as $slot) {
                $wfProcessData = WorkflowProcessUserTable::instance()->getActiveProcessUserForWorkflowSlot($slot['workflowslot_id'],$this->getUser()->getAttribute('id'))->toArray();
                $toChange = WorkflowProcessUserTable::instance()->getWaitingStation($slot['workflowslot_id'],$this->getUser()->getAttribute('id'));
                foreach($toChange as $itemToChange) {
                    $pdoObj = Doctrine::getTable('WorkflowProcessUser')->find($itemToChange->getId());
                    $pdoObj->setDecissionstate('DONE');
                    $pdoObj->setDateofdecission(time());
                    $pdoObj->save();
                }
                $versionId = $request->getParameter('versionid');
                $wfSlotId = $slot['workflowslot_id'];
                $wsUserId = $wfProcessData[0]['workflowslotuser_id'];
                $checkWorkflow = new CreateNextStation($versionId,$wfSlotId,$wsUserId);
            }
            $workflowData = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'))->toArray();
           
            if($workflowData[0]['sendtoallslotsatonce'] == 1) {
                $slots = WorkflowSlotTable::instance()->getSlotByVersionId($request->getParameter('versionid'));
                $isCompleted = true;
                foreach($slots as $slot) {
                    $users = WorkflowSlotUserTable::instance()->getUserBySlotId($slot->getId());
                    foreach($users as $user) {
                        $processUsers = WorkflowProcessUserTable::instance()->getProcessUserByWorkflowSlotUserId($user->getId());
                        foreach($processUsers as $singleUser) {
                            $userArray = $singleUser->toArray();
                            if($userArray['decissionstate'] == 'WAITING') {
                                $isCompleted = false;
                            }
                        }
                    }
                }
                if($isCompleted == true) {
                    WorkflowTemplateTable::instance()->setWorkflowFinished($workflowData[0]['workflowtemplate_id']);
                }
            }
        }
        else { // user denies workflow
            WorkflowTemplateTable::instance()->stopWorkflow($request->getParameter('workflowid'), $this->getUser()->getAttribute('id'));
            WorkflowVersionTable::instance()->setEndReason($request->getParameter('versionid'), $data['workfloweditAcceptWorkflow_reason']);
            $workflowToStop = WorkflowProcessUserTable::instance()->getWaitingStationToStopByUser($request->getParameter('versionid'));
            foreach($workflowToStop as $itemToChange) {
                $pdoObj = Doctrine::getTable('WorkflowProcessUser')->find($itemToChange->getId());
                $pdoObj->setDecissionstate('STOPPEDBYUSER');
                $pdoObj->setDateofdecission(time());
                $pdoObj->save();
            }
            //$workflowEdit->stopWorkflow($request->getParameter('workflowid'),$request->getParameter('versionid'), $this->getUser()->getAttribute('id'), $data['workfloweditAcceptWorkflow_reason']);
        }



        $this->renderText('{success:true}');
        return sfView::NONE;
    }

    /**
     * Action Test
     */
    public function executeTest(sfWebRequest $request) {

        $data = Doctrine_Query::create()
            ->select('wfpu.*')
            ->from('WorkflowProcessUser wfpu')
            ->leftJoin('wfpu.WorkflowProcess wfp')
            ->where('wfp.workflowslot_id = ?', 1)
            ->andWhere('wfpu.decissionstate = ?', 'WAITING')
            ->andWhere('wfpu.user_id = ?',1)
            ->execute()->toArray();

        print_r ($data);die;

        echo Doctrine_Query::create()
            ->update('WorkflowProcessUser wfpu, WorkflowProcess wfp')
            ->set('wfpu.decissionstate','?','DONE')
            ->set('wfpu.dateofdecission','?',time())
            ->where('wfp.workflowslot_id = 1 AND wfpu.decissionstate = "WAITING" AND wfpu.workflowprocess_id = wfp.id AND wfpu.user_id = 1')
            ->getSql();



        /*echo Doctrine_Query::create()
            ->update('WorkflowProcessUser wfpu')
            ->set('wfpu.decissionstate','?','DONE')
            ->set('wfpu.dateofdecission','?',time())
            ->leftJoin('WorkflowProcess wfp ON wfpu.workflowprocess_id = wfp.id ')
            ->where('wfp.workflowslot_id = 1')
            ->andWhere('wfpu.decissionstate = "WAITING"')
            ->andWhere('wfpu.workflowprocess_id = wfp.id')
            ->andWhere('wfpu.user_id = 1')
            ->execute();*/



        die;
            #->set('wfpu.decissionstate','?','DONE')
            #->set('wfpu.dateofdecission','?',time())
            #->where('wfp.workflowslot_id = ?' ,1)
            #->andWhere('wfpu.decissionstate = ?', 'WAITING')
            #->andWhere('wfpu.workflowprocess_id = ?' , 'wfp.id')
            #->andWhere('wfpu.user_id = ?', 1)
            #->execute();
        
        return sfView::NONE;
    }

    









}
