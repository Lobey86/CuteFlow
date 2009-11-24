<?php

/**
 * workflowdetail actions.
 *
 * @package    cf
 * @subpackage workflowdetail
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class workflowdetailActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeLoadWorkflowDetails(sfWebRequest $request) {
        $detailsObj = new WorkflowDetail();
        $detailsObj->setCulture($this->getUser()->getCulture());
        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'));
        $details = $detailsObj->buildHeadLine($workflowsettings);
        
        $this->renderText('{"generaldata":'.json_encode($details).'}');
        return sfView::NONE;
    }
}
