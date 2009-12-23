<?php

/**
 * workflowoverview actions.
 *
 * @package    cf
 * @subpackage workflowoverview
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class workflowoverviewActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request){
        $this->forward('default', 'module');
    }


    public function executeLoadAllWorkflow(sfWebRequest $request) {
        $workflow = new WorkflowOverview($this->getContext(), $this->getUser());
        $workflow->setUserId($this->getUser()->getAttribute('id'));
        $workflow->setCulture($this->getUser()->getCulture());
        $data = WorkflowTemplateTable::instance()->getAllWorkflowTemplates(-1, -1);
        $json_data = $workflow->buildData($data);
        $this->renderText('({"result":'.json_encode($json_data).'})');
        return sfView::NONE;
    }


    public function executeStopWorkflow(sfWebRequest $request) {
        WorkflowTemplateTable::instance()->stopWorkflow($request->getParameter('workflowtemplateid'), $this->getUser()->getAttribute('id'));
        $workflow = new WorkflowOverview($this->getContext(), $this->getUser());
        $workflow->setUserId($this->getUser()->getAttribute('id'));
        $workflow->setCulture($this->getUser()->getCulture());
        $data = WorkflowProcessUserTable::instance()->getWaitingStationToStopByUser($request->getParameter('versionid'));
        foreach($data as $itemToChange) {
                $pdoObj = Doctrine::getTable('WorkflowProcessUser')->find($itemToChange->getId());
                $pdoObj->setDecissionstate('STOPPEDBYADMIN');
                $pdoObj->setDateofdecission(time());
                $pdoObj->save();
        }
        return sfView::NONE;
    }


    public function executeStartWorkflow(sfWebRequest $request) {
        WorkflowVersionTable::instance()->startWorkflow($request->getParameter('versionid'));
        $workflowVersion = WorkflowTemplateTable::instance()->getWorkflowTemplateByVersionId($request->getParameter('versionid'));
        $template = MailinglistVersionTable::instance()->getSingleVersionById($workflowVersion[0]->getMailinglisttemplateversionId())->toArray();
        if($template[0]['sendtoallslotsatonce'] == 1) {
            $calc = new CreateWorkflow($request->getParameter('versionid'));
            $calc->addAllSlots();
        }
        else {
           $calc = new CreateWorkflow($request->getParameter('versionid'));
           $calc->addSingleSlot();
        }
        return sfView::NONE;
    }






}