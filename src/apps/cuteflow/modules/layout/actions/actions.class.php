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

        $this->theTheme = $userSettings[0]->getTheme();
        // Load credentials and store them to session here
        $credentials = CredentialTable::instance()->getAllCredentials();
        $userrights = CredentialRoleTable::instance()->getCredentialRoleById($this->getUser()->getAttribute('id'));
        $rights = $loginObject->loadUserRight($credentials, $userrights);
        $this->getUser()->setAttribute('credential', $rights);
        $this->version_id  = $request->getParameter('versionid',-1);
        $this->workflow_id  = $request->getParameter('workflow',-1);
        $this->window  = $request->getParameter('window',-1);
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
        $versionId = 1;
        $templateId = 1;
        $user_id = 1;
        $test = new PrepareStationEmail($versionId, $templateId, $user_id);
        die;
        return sfView::NONE;
    }


    
    public function executeLinklogin(sfWebRequest $request) {

        $settings = AuthenticationConfigurationTable::instance()->getAuthenticationConfiguration()->toArray();
        $user_id = $request->getParameter('userid');
        if($settings[0]['allowdirectlogin'] == 1) { // allow direct login, without using login form
            $userLogin = UserLoginTable::instance()->findUserById($user_id);
            $arr = $userLogin->toArray(); // load User Data
            if($this->getUser()->isAuthenticated() == false) { // check if user is already logged in
                if(empty($arr) == false) { // a user has been found, -> user is not deleted
                    $settings = UserSettingTable::instance()->getUserSettingById($user_id); // user is not logged in, set the settings
                    $this->getUser()->setAuthenticated(true);
                    $this->getUser()->setAttribute('id',$user_id);
                    $this->getUser()->setAttribute('userrole',$userLogin[0]->getRoleId());
                    $this->getUser()->setCulture($settings[0]->getLanguage());
                    $this->getUser()->setAttribute('env', str_replace('/', '', $request->getScriptName()));
                    $this->redirect($this->generateUrl('default', array('module' => 'layout', 'action' => 'index', 'versionid' => $request->getParameter('versionid'), 'workflow' => $request->getParameter('workflowid'), 'window' => $request->getParameter('window'))));
                }
                else { // user is not found or is deleted
                    $this->redirect('login/index');
                }
            }
            else { // user is already logged in
                $this->redirect($this->generateUrl('default', array('module' => 'layout', 'action' => 'index', 'versionid' => $request->getParameter('versionid'), 'workflow' => $request->getParameter('workflowid'), 'window' => $request->getParameter('window'))));
            }
        }
        else { // allow direct login is denied
            if($this->getUser()->isAuthenticated() == true) { // user is already logged in
                $this->redirect($this->generateUrl('default', array('module' => 'layout', 'action' => 'index', 'versionid' => $request->getParameter('versionid'), 'workflow' => $request->getParameter('workflowid'), 'window' => $request->getParameter('window'))));
            }
            else { // move to login page
                $this->redirect($this->generateUrl('default', array('module' => 'login', 'action' => 'index', 'versionid' => $request->getParameter('versionid'), 'workflow' => $request->getParameter('workflowid'), 'window' => $request->getParameter('window'))));
            }
        }
        return sfView::NONE;
    }






}
