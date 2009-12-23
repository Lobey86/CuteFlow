<?php

/**
 * cronjob actions.
 *
 * @package    cf
 * @subpackage cronjob
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class cronjobActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeStartWorkflow(sfWebRequest $request) {
        $workflows = WorkflowVersionTable::instance()->getWorkflowsToStart(time())->toArray();
        foreach($workflows as $workflow) {
            $workflowTemplate = WorkflowTemplateTable::instance()->getWorkflowTemplateByVersionId($workflow['id']);
            WorkflowVersionTable::instance()->startWorkflowInFuture($workflow['id']);
            $sendToAllSlotsAtOnce = $workflowTemplate[0]->getMailinglistVersion()->toArray();
            if($sendToAllSlotsAtOnce[0]['sendtoallslotsatonce'] == 1) {
                $calc = new CreateWorkflow($workflow['id']);
                $calc->addAllSlots();
            }
            else {
                $calc = new CreateWorkflow($workflow['id']);
                $calc->addSingleSlot();
            }

        }
        return sfView::NONE;
    }




    public function executeSendReminderEmail(sfWebRequest $request) {
        $wfSettings = SystemConfigurationTable::instance()->getSystemConfiguration()->toArray();
        
        if($wfSettings[0]['sendremindermail'] == 1) {

            $sendMail = new PrepareReminderEmail();
            $stillOpenWorkflows = array();
            $a = 0;
            $openWorkflows = WorkflowTemplateTable::instance()->getAllRunningWorkflows();
            foreach($openWorkflows as $workflow) {
                $openStations = WorkflowProcessUserTable::instance()->getWaitingStationByVersionId($workflow['WorkflowVersion']['id'])->toArray();
                $data = $sendMail->prepareData($openStations);
                $stillOpenWorkflows[$a]['workflow_id'] = $workflow['id'];
                $stillOpenWorkflows[$a]['name'] = $workflow['name'];
                $stillOpenWorkflows[$a]['workflowversion_id'] = $workflow['WorkflowVersion']['id'];
                $stillOpenWorkflows[$a++]['users'] = $data;
            }
            $stillOpenWorkflows = $sendMail->sortByUser($stillOpenWorkflows);
            foreach($stillOpenWorkflows as $userToSend) {
                $umSettings = new UserMailSettings($userToSend['user_id']);
                $reminder = new SendReminderEmail($umSettings, $this, $userToSend);
            }

        }
        return sfView::NONE;
    }



}