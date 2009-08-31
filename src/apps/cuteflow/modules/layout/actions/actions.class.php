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


 

 
      
       // LOAD USERRIGHTS HERE
      // stores userRigts to session
      //$userRole = Doctrine::getTable('UserRole')->find($this->getUser()->getAttribute('id'));
      //$this->getUser()->setAttribute('userRole', $userRole);

        return sfView::SUCCESS;
    }
}
