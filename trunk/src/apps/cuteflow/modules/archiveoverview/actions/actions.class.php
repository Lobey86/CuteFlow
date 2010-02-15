<?php

/**
 * archiveoverview actions.
 *
 * @package    cf
 * @subpackage archiveoverview
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class archiveoverviewActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Action LoadAllArchivedWorkflow
     */
    public function executeLoadAllArchivedWorkflow(sfWebRequest $request) {
        $limit = $this->getUser()->getAttribute('userSettings');
        $workflow = new WorkflowOverview($this->getContext(), $this->getUser());
        $workflow->setUserId($this->getUser()->getAttribute('id'));
        $workflow->setCulture($this->getUser()->getCulture());
        $anz = WorkflowTemplateTable::instance()->getSumArchivedWorkflowTemplates();
        $data = WorkflowTemplateTable::instance()->getArchivedWorkflowTemplates($request->getParameter('limit',$limit['displayeditem']),$request->getParameter('start',0));
        $json_data = $workflow->buildData($data, $request->getParameter('start',0));
        $this->renderText('({"total":"'.$anz[0]->getAnzahl().'","result":'.json_encode($json_data).'})');
        return sfView::NONE;
    }

    
    public function executeRemoveFromArchive(sfWebRequest $request) {
        WorkflowTemplateTable::instance()->removeFromArchive($request->getParameter('workflowtemplateid'), $this->getUser()->getAttribute('id'));
        return sfView::NONE;
    }

}