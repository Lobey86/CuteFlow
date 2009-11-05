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
     * Load Mailinglist by filter
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllMailinglistsByFilter(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $limit = $this->getUser()->getAttribute('userSettings');
        $data = MailinglistTemplateTable::instance()->getAllMailinglistTemplatesByFilter($request->getParameter('limit',$limit['displayeditem']),$request->getParameter('start',0),$request->getParameter('name'));
        $anz = MailinglistTemplateTable::instance()->getTotalSumOfMailingListTemplatesByFilter($request->getParameter('name'));
        $json_result = $mailinglist->buildAllMailinglists($data);
        $this->renderText('({"total":"'.$anz[0]->getAnzahl().'","result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }


    public function executeBuildReceiver(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $result = UserDataTable::instance()->getAllUserFullname();
        $json_result = $mailinglist->buildReceiver($result);
        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }

    /**
     * Load all Documents
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllDocuments(sfWebRequest $request) {
        $result = DocumenttemplateTemplateTable::instance()->getAllDocumentTemplates(-1,-1)->toArray();
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
        $docObj = new Documenttemplate();
        $id = DocumenttemplateVersionTable::instance()->getActiveVersionById($request->getParameter('id'))->toArray();
        $data = DocumenttemplateTemplateTable::instance()->getDocumentTemplateById($id[0]['id']);
        $result = $docObj->buildSingleDocumenttemplates($data, $id[0]['id'], 'SLOTSONLY');
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
        $data = $request->getPostParameters();
        $mailinglisttemplate = new MailinglistTemplate();
        $mailinglisttemplate->setName($data['mailinglistFirstTab_nametextfield']);
        $mailinglisttemplate->setIsactive(0);
        $mailinglisttemplate->setDocumenttemplatetemplateId($data['mailinglistFirstTab_documenttemplate']);
        $mailinglisttemplate->save();
        $mailinglisttemplate_id = $mailinglisttemplate->getId();
        $mailinglistversion_id = $mailinglist->storeVersion($mailinglisttemplate_id, 1);
        $mailinglist->createAuthorizationEntry($mailinglistversion_id);
        $mailinglist->saveAuthorization($mailinglistversion_id,isset($data['mailinglistFirstTab']) ? $data['mailinglistFirstTab'] : array());
        $mailinglist->saveUser($mailinglistversion_id, isset($data['user']) ? $data['user'] : array());
        $slots = $data['slot'];
        $mailinglist->storeMailinglist($slots, $mailinglistversion_id);
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

        /**
     * Update an mailinglist
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeUpdateMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $data = $request->getPostParameters();
        $mailingsdata = MailinglistVersionTable::instance()->getVersionById($request->getParameter('id'))->toArray();
        MailinglistVersionTable::instance()->setMailinglistInactiveById($request->getParameter('id'));
        $mailinglistversion_id = $mailinglist->storeVersion($mailingsdata[0]['mailinglisttemplate_id'],$mailingsdata[0]['version']+1);
        $mailinglist->createAuthorizationEntry($mailinglistversion_id);
        $mailinglist->saveAuthorization($mailinglistversion_id, isset($data['mailinglistFirstTab']) ? $data['mailinglistFirstTab'] : array());
        $mailinglist->saveUser($mailinglistversion_id, isset($data['user']) ? $data['user'] : array());
        $slots = $data['slot'];
        $mailinglist->storeMailinglist($slots, $mailinglistversion_id);
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
        $mailObj = new Mailinglist();
        $mailObj->setContext($this->getContext());
        $mailinglist = MailinglistTemplateTable::instance()->getMailinglistByVersionId($request->getParameter('id'));
        $data = $mailObj->buildSingleMailinglist($mailinglist, $request->getParameter('id'));
        $this->renderText('{"result":'.json_encode($data).'}');
        return sfView::NONE;
    }


    public function executeDummy(sfWebRequest $request) {
        $json_data = array();
        $this->renderText('{"result":'.json_encode($json_data).'}');
        return sfView::NONE;
    }



    /**
     * Load all versions of an existing template
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllVersion(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $mailinglist->setContext($this->getContext());
        $result = MailinglistVersionTable::instance()->getAllVersionsById($request->getParameter('id'));
        $json_result = $mailinglist->buildAllVersion($result, $this->getUser()->getCulture());
        $this->renderText('({"result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }


    /**
     * Activate a mailinglist from history
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeActivateMailinglist(sfWebRequest $request) {
        $id = $request->getParameter('id');
        $mailinglist_id = $request->getParameter('mailinglistid');
        MailinglistVersionTable::instance()->setMailinglistInactiveById($mailinglist_id);
        MailinglistVersionTable::instance()->setMailinglistActiveById($id);
        return sfView::NONE;
    }


    /**
     * Adapt mailinglist to current version
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeAdaptMailinglist(sfWebRequest $request) {
        $mailinglist = new Mailinglist();
        $docuObj = new Documenttemplate();
        $mailinglistdata = MailinglistTemplateTable::instance()->getMailinglistByVersionTemplateId($request->getParameter('id'))->toArray();
        $currentdocumenttemplateversion = DocumenttemplateVersionTable::instance()->getActiveVersionById($mailinglistdata[0]['documenttemplatetemplate_id'])->toArray();
        $slots = $docuObj->buildSlots($currentdocumenttemplateversion[0]['id'], 'SLOTSONLY');
        $mailinglistversiondata = MailinglistVersionTable::instance()->getActiveVersionById($request->getParameter('id'))->toArray();
        MailinglistVersionTable::instance()->setMailinglistInactiveById($mailinglistversiondata[0]['mailinglisttemplate_id']);
        $mailinglistversion_id = $mailinglist->storeVersion($mailinglistversiondata[0]['mailinglisttemplate_id'],$mailinglistversiondata[0]['version']+1);
        $userdata = MailinglistAllowedSenderTable::instance()->getAllowedSenderById($mailinglistversiondata[0]['id']);
        $users = $mailinglist->buildAllowedUser($userdata);
        $mailinglist->saveUser($mailinglistversion_id, isset($users) ? $users: array());
        $authdata = MailinglistAuthorizationSettingTable::instance()->getAuthorizationById($mailinglistversiondata[0]['id'])->toArray();
        $mailinglist->adaptAuthorizationEntry($authdata, $mailinglistversion_id);
        $mailinglist->storeMailinglist($slots, $mailinglistversion_id);
        return sfView::NONE;
    }



}
