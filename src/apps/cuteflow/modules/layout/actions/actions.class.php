<?php

/**
* layout actions.
*
* @package    cf
* @subpackage layout
* @author     Your name here
* @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
*/
class layoutActions extends sfActions {
    /**
     *
    * Load Userrights and User Id to Sesseion.
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $loginObject = new Login();

        // Load UserSetting and Store to session here
        $userSettings = UserSettingTable::instance()->getUserSettingById($this->getUser()->getAttribute('id'));
        $this->getUser()->setAttribute('userSettings', $userSettings[0]->toArray());

        
        // Load credentials and store them to session here
        $credentials = CredentialTable::instance()->getAllCredentials();
        $userrights = CredentialRoleTable::instance()->getCredentialRoleById($this->getUser()->getAttribute('id'));
        $rights = $loginObject->loadUserRight($credentials, $userrights);
        $this->getUser()->setAttribute('credential', $rights);
        //UserSettingTable::instance()->setFirstLogin($this->getUser()->getAttribute('id'));
        return sfView::SUCCESS;
    }
}
