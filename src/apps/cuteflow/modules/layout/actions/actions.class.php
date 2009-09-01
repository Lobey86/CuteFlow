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

        // stores usersettings to session
        $userSettings = Doctrine::getTable('UserSetting')->find($this->getUser()->getAttribute('id'));
        $this->getUser()->setAttribute('userSettings', $userSettings);


        // Loads Userrights to session
        $loginObject = new Login();
        $credentials = Doctrine_Query::create()
            ->select('c.*')
            ->from('Credential c')
            ->execute();

        $userrights = Doctrine_Query::create()
            ->select('cr.*')
            ->from('CredentialRole cr, Role r, User u')
            ->where ('u.role_id = r.id')
            ->andwhere('r.id = cr.role_id')
            ->andwhere('u.id = ?', $this->getUser()->getAttribute('id'))
            ->execute();

        $rights = $loginObject->loadUserRight($credentials, $userrights);
        #print_r ($rights);die;
        $this->getUser()->setAttribute('credential', $rights);
        return sfView::SUCCESS;
    }
}
