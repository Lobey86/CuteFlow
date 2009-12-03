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
        $workflowData = $detailsObj->buildWorkflowData($workflowsettings, $request->getParameter('versionid'));

        #print_r ($userData);die;
        #print_r ($workflowData);die;
        $this->renderText('{"generalData":'.json_encode($generalData).', "detailData" : '.json_encode($userData).', "workflowData" : '.json_encode($workflowData).'}');
        return sfView::NONE;
    }



    public function executeSkipStation(sfWebRequest $request) {
        WorkflowProcessUserTable::instance()->skipStation($request->getParameter('workflowprocessuserid'));
        $checkWorkflow = new CreateNextStation($request->getParameter('versionid'),$request->getParameter('workflowslotid'),$request->getParameter('workflowslotuserid'));
        
        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());
        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'));
        $userData = $detailsObj->buildUserData($workflowsettings, $request->getParameter('versionid'));
       
        
        $this->renderText('{"detailData" : '.json_encode($userData).'}');
        return sfView::NONE;
    }


    /**
     * Load all stations to set them
     * 
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllStations(sfWebRequest $request) {
        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());
        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($request->getParameter('versionid'));
        $userData = $detailsObj->buildUserData($workflowsettings, $request->getParameter('versionid'));
        #print_r ($userData);die;
        $this->renderText('{"detailData" : '.json_encode($userData).'}');
        return sfView::NONE;
    }




    public function executeSetUseragent(sfWebRequest $request) {
        $useragent_id = $request->getParameter('userid');
        $workflowprocess_id = $request->getParameter('workflowprocessuserid');
        $version_id = $request->getParameter('versionid');
        $currentVersion = WorkflowProcessUserTable::instance()->getProcessById($workflowprocess_id)->toArray();

        $processObj = new WorkflowProcessUser();
        $processObj->setWorkflowprocessId($currentVersion[0]['workflowprocess_id']);
        $processObj->setWorkflowslotuserId($currentVersion[0]['workflowslotuser_id']);
        $processObj->setUserId($useragent_id);
        $processObj->setInprogresssince(time());
        $processObj->setDecissionstate('WAITING');
        $processObj->setIsuseragentof($workflowprocess_id);
        $processObj->setResendet(0);
        $processObj->save();
        WorkflowProcessUserTable::instance()->setProcessToUseragentSet($workflowprocess_id);


        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());

        $workflowsettings = WorkflowVersionTable::instance()->getWorkflowVersionById($version_id);
        $userData = $detailsObj->buildUserData($workflowsettings, $version_id);
        $this->renderText('{"detailData" : '.json_encode($userData).'}');
        return sfView::NONE;
    }

    
    public function executeSetNewStation(sfWebRequest $request) {
        $calc = new SetStation($request->getParameter('versionid'), $request->getParameter('newworkflowuserslotid'), $request->getParameter('currentworkflowuserslotid'), $request->getParameter('direction'));
        $detailsObj = new WorkflowDetail();
        $detailsObj->setUser($this->getUser());
        $detailsObj->setCulture($this->getUser()->getCulture());
        $detailsObj->setContext($this->getContext());

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
