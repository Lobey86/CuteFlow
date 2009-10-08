<?php

/**
 * systemsetting actions.
 *
 * @package    cf
 * @subpackage systemsetting
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class systemsettingActions extends sfActions {
    /**
     *
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Loads Systems settings
     *
     * @param sfRequest $request A request object
     */
    public function executeLoadSystem(sfWebRequest $request) {
        $systemObj = new SystemSetting();
        $email = EmailConfigurationTable::instance()->getEmailConfiguration()->toArray();
        $auth = AuthenticationConfigurationTable::instance()->getAuthenticationConfiguration()->toArray();
        $system = SystemConfigurationTable::instance()->getSystemConfiguration()->toArray();
        $usersettings = UserConfigurationTable::instance()->getUserConfiguration()->toArray();

        $email[0]['smtpencryption'] =  $email[0]['smtpencryption'] == '' ? 'NONE' :  $email[0]['smtpencryption'];
        
        $this->renderText('{"email":'.json_encode($email[0]).',"auth":'.json_encode($auth[0]).',"system" : '.json_encode($system[0]).',"user" : '.json_encode($usersettings[0]).'}');
        return sfView::NONE;
    }


    /**
     *
     * Saves SystemSettings to additional Tables in database
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveSystem(sfWebRequest $request) {
        $sysObj = new SystemSetting();
        $data = $request->getPostParameters();

        // new database
        if (isset($data['productive_checkbox'])) {

        }
        
        // save auth settings
        if (isset($data['authentication_type'])) {
            if($data['authentication_type'] == 'DBONLY') {
                AuthenticationConfigurationTable::instance()->updateAuthenticationConfigurationCuteflowDatabase($data);
            }
            elseif ($data['authentication_type'] == 'DATABASE_LDAP') {
                AuthenticationConfigurationTable::instance()->updateAuthenticationConfigurationCuteflowDatabaseAndLDAP($data);
            }
            else {
                AuthenticationConfigurationTable::instance()->updateAuthenticationConfigurationCuteflowDatabaseAndOpenId($data);
            }
        }

        // save systemsetting
        if (isset($data['systemsetting_language'])) {
            $data = $sysObj->buildSystemSetting($data);
            SystemConfigurationTable::instance()->updateSystemConfiguration($data);
        }

        // store Email tab
        if (isset($data['emailtab_emailtype'])) { 
            $data = $sysObj->buildEmailSetting($data);
            EmailConfigurationTable::instance()->updateEmailConfiguration($data);
        }

        // store user tab
        if (isset($data['userTab_defaultdurationtype'])) {
            $data = $sysObj->buildUserSetting($data);
            UserConfigurationTable::instance()->updateUserConfiguration($data);
        }

        // save worklfow config 
        WorkflowConfigurationTable::instance()->deleteSettings();
        $worklfow = $data['worklfow'];
        $position = 1;
        foreach($worklfow as $item => $key) {
            $workflow = new WorkflowConfiguration();
            $workflow->setColumntext($item);
            $workflow->setIsactive($key);
            $workflow->setPosition($position++);
            $workflow->save();
        }

        $this->renderText('{success:true}');
        return sfView::NONE;
    }

    /**
     * Build store for Column in systemsettings and useredit/creation
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadCirculationColumns(sfWebRequest $request) {
        $sysObj = new SystemSetting();
        $worklfosettings = WorkflowConfigurationTable::instance()->getWorkflowConfigurattion()->toArray();
        $worklfosettings = $sysObj->buildColumns($worklfosettings, $this->getContext());
        
        $this->renderText('{"result":'.json_encode($worklfosettings).'}');
        return sfView::NONE;
    }
}