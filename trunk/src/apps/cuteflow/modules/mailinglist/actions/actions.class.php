<?php

/**
 * mailinglist actions.
 *
 * @package    cf
 * @subpackage mailinglist
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class mailinglistActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Load all mailininglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllMailinglists(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $limit = $this->getUser()->getAttribute('userSettings');
        $data = MailinglistTemplateTable::instance()->getAllMailinglistTemplates($request->getParameter('limit',$limit['displayeditem']),$request->getParameter('start',0));
        $anz = MailinglistTemplateTable::instance()->getTotalSumOfMailingListTemplates();
        $json_result = $mailinglist->buildAllMailinglists($data);
        $this->renderText('({"total":"'.$anz[0]->getAnzahl().'","result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }


    /**
     * Load all Documents
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllDocuments(sfWebRequest $request) {
        $result = FormTemplateTable::instance()->getAllFormTemplates(-1,-1)->toArray();
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }

    /**
     * Loads all users, which are allowed to send workflows
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllSender(sfWebRequest $request) {
        $result = UserLoginTable::instance()->getAllSenderUser()->toArray();
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }



    /**
     * Load a form without user
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadFormWithoutUser(sfWebRequest $request) {
        $form = new Form();
        $data = FormTemplateTable::instance()->getFormTemplateById($request->getParameter('id'));
        $result = $form->buildSingleForm($data, false);
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }



    /**
     * Save mailinglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $user = array();
        $data = $request->getPostParameters();
        $mailinglist->saveMailinglist($data);
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

    /**
     * Set standard to mailinglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSetStandard(sfWebRequest $request) {
        MailinglistTemplateTable::instance()->setAllTemplatesDisabledById();
        MailinglistTemplateTable::instance()->activateTemplateById($request->getParameter('id'));
        return sfView::NONE;
    }

    /**
     * Delete Mailinglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeDeleteMailinglist(sfWebRequest $request) {
        MailinglistTemplateTable::instance()->deleteMailinglistTemplateById($request->getParameter('id'));
        return sfView::NONE;
    }

    /**
     * Load Mailinglist by filter
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllMailinglistsFilter(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $limit = $this->getUser()->getAttribute('userSettings');
        $search = $request->getParameter('name');
        $data = MailinglistTemplateTable::instance()->getAllMailinglistTemplatesByFilter($request->getParameter('limit',$limit['displayeditem']),$request->getParameter('start',0),$search);
        $anz = MailinglistTemplateTable::instance()->getTotalSumOfMailingListTemplatesByFilter($search);
        $json_result = $mailinglist->buildAllMailinglists($data);
        $this->renderText('({"total":"'.$anz[0]->getAnzahl().'","result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }

    /**
     * Load Authorization settings for an exisiting template
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAuthorization(sfWebRequest $request) {
        $sysObj = new SystemSetting();
        $data = MailinglistAuthorizationSettingTable::instance()->getAuthorizationById($request->getParameter('id'))->toArray();
        $worklfosettings = $sysObj->buildAuthorizationColumns($data, $this->getContext());
        $this->renderText('{"result":'.json_encode($worklfosettings).'}');
        return sfView::NONE;
    }

    /**
     * Load all sender with sending rights
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllAllowedSender(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $data = MailinglistAllowedSenderTable::instance()->getAllowedSenderById($request->getParameter('id'))->toArray();
        $data = $mailinglist->buildAllowedSender($data);

        $this->renderText('{"result":'.json_encode($data).'}');
        return sfView::NONE;
    }


    /**
     * Load a Mailinglist template including users (when in editmode)
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadFormWithUser(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $data = MailinglistTemplateTable::instance()->getMailinglistById($request->getParameter('id'));
        $json_result = $mailinglist->buildSingleMailinglist($data);
        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }
    /**
     * Load name of an single Mailinglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadSingleMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $data = MailinglistTemplateTable::instance()->getMailinglistById($request->getParameter('id'));
        $json_data = $mailinglist->buildAllMailinglists($data);
        $this->renderText('{"result":'.json_encode($json_data[0]).'}');
        return sfView::NONE;
    }


    public function executeDummy(sfWebRequest $request) {
        $json_data = array();
        $this->renderText('{"result":'.json_encode($json_data).'}');
        return sfView::NONE;
    }
    /**
     * Update an mailinglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeUpdateMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $user = array();
        $data = $request->getPostParameters();
        $template_id = $request->getParameter('id');
        $mailinglist->updateMailinglist($template_id, $data);
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
