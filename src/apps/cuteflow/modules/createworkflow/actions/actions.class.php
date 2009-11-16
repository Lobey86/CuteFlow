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



    /**
     * Load all mailinglists
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $data = MailinglistTemplateTable::instance()->getAllMailinglistTemplates(-1,-1);
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
       # print_r ($result);die;
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }


}
