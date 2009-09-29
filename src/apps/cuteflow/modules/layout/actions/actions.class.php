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
         // Loads Userrights to session
        $loginObject = new Login();
        // stores usersettings to session
        $userSettings = Doctrine_Query::create()
                        ->select('us.*')
                        ->from('UserSetting us')
                        ->where('us.user_id = ?',$this->getUser()->getAttribute('id'))
                        ->fetchArray();
        //$userData = $loginObject->loadUserData();
        $this->getUser()->setAttribute('userSettings', $userSettings[0]);


     
        $credentials = Doctrine_Query::create()
            ->select('c.*')
            ->from('Credential c')
            ->execute();

        $userrights = Doctrine_Query::create()
            ->select('cr.*')
            ->from('CredentialRole cr, Role r, UserLogin ul')
            ->where ('ul.role_id = r.id')
            ->andwhere('r.id = cr.role_id')
            ->andwhere('ul.id = ?', $this->getUser()->getAttribute('id'))
            ->execute();

        $rights = $loginObject->loadUserRight($credentials, $userrights);
        $this->getUser()->setAttribute('credential', $rights);
        return sfView::SUCCESS;
    }
}
