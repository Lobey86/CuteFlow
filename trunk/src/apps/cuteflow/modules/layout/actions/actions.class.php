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
        return sfView::SUCCESS;
    }

    /**
     * Sets firstlogin to 0
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeChangeFirstLogin(sfWebRequest $request) {
        UserSettingTable::instance()->setFirstLogin($this->getUser()->getAttribute('id'));
        return sfView::NONE;
    }

    public function executeTest(sfWebRequest $request) {
        $array[0] = 4;
        $array[1] = 5;

       //$fields_to_delete = Doctrine::getTable('FormField')->find($array)->delete();
      //$slots_to_delete = Doctrine::getTable('FormField')->createQuery('ff')->whereIn('ff.formslot_id', $array)->execute()->delete();
        
      $slots_to_delete = Doctrine::getTable('FormSlot')->createQuery('fs')->whereIn('fs.id', $array)->execute()->delete();
      //$slots_to_delete = Doctrine::getTable('FormSlot')->createQuery('fs')->whereIn('fs.id', $array);
die;


        print_r ($slots_to_delete);die;
      

       //print_r ($fields_to_delete);die;
       

/*$user = Doctrine::getTable('User')
  ->createQuery('u')
  ->leftJoin('u.Address a')
  ->leftJoin('a.AddressType t')
  ->findOneById(1);*/

        return sfView::NONE;
    }






}
