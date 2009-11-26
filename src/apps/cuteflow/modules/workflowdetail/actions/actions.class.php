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

    /**
     * Load details for a single workflow
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadWorkflowDetails(sfWebRequest $request) {
        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());
        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'));
        $generalData = $detailsObj->buildHeadLine($workflowsettings);
        $userData = $detailsObj->buildUserData($workflowsettings, $request->getParameter('versionid'));
        #print_r ($userData);die;
        #print_r ($userData);die;
        $this->renderText('{"generalData":'.json_encode($generalData).', "detailData" : '.json_encode($userData).'}');
        return sfView::NONE;
    }



    public function executeSkipStation(sfWebRequest $request) {
        WorkflowProcessUserTable::instance()->skipStation($request->getParameter('workflowprocessuserid'));
        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());
        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'));

        $userData = $detailsObj->buildUserData($workflowsettings, $request->getParameter('versionid'));
       
        
        $this->renderText('{"detailData" : '.json_encode($userData).'}');
        return sfView::NONE;
    }
}
