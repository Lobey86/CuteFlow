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
        $email = Doctrine_Query::create()
                ->select('ec.*')
                ->from('EmailConfiguration ec')
                ->fetchArray();
        $auth = Doctrine_Query::create()
                ->select('ac.*')
                ->from('AuthenticationConfiguration ac')
                ->fetchArray();
        $system = Doctrine_Query::create()
                ->select('sc.*')
                ->from('SystemConfiguration sc')
                ->fetchArray();
        $usersettings = Doctrine_Query::create()
                ->select('uc.*')
                ->from('UserConfiguration uc')
                ->fetchArray();
 

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
        $data = $request->getPostParameters();


        if (isset($data['productive_checkbox'])) {

        }
        
        // save auth settings
        if (isset($data['authentication_type'])) {
            if($data['authentication_type'] == 'DBONLY') {
                Doctrine_Query::create()
                    ->update('AuthenticationConfiguration ac')
                    ->set('ac.authenticationtype', '?', $data['authentication_type'])
                    ->where ('ac.id = ?',1)
                    ->execute();
            }
            elseif ($data['authentication_type'] == 'DATABASE_LDAP') {
                Doctrine_Query::create()
                    ->update('AuthenticationConfiguration ac')
                    ->set('ac.authenticationtype', '?', $data['authentication_type'])
                    ->set('ac.ldaphost', '?', $data['auth_ladp_host'])
                    ->set('ac.ldapdomain', '?', $data['auth_ladp_domain'])
                    ->set('ac.ldapbindusernameandcontext', '?', $data['auth_ladp_bindusernameandcontext'])
                    ->set('ac.ldappassword', '?', $data['auth_ladp_password'])
                    ->set('ac.ldaprootcontext', '?', $data['auth_ladp_rootcontext'])
                    ->set('ac.ldapusersearchattribute', '?', $data['auth_ladp_usersearchattribute'])
                    ->set('ac.ldapfirstname', '?', $data['auth_ladp_firstname'])
                    ->set('ac.ldaplastname', '?', $data['auth_ladp_lastname'])
                    ->set('ac.ldapusername', '?', $data['auth_ladp_username'])
                    ->set('ac.ldapemail', '?', $data['auth_ladp_email'])
                    ->set('ac.ldapoffice', '?', $data['auth_ladp_office'])
                    ->set('ac.ldapphone', '?', $data['auth_ladp_phone'])
                    ->set('ac.ldapcontext', '?', $data['auth_ladp_context'])
                    ->where ('ac.id = ?',1)
                    ->execute();
            }
            else {
                Doctrine_Query::create()
                    ->update('AuthenticationConfiguration ac')
                    ->set('ac.openidserver', '?', $data['auth_openid_server'])
                    ->set('ac.authenticationtype', '?', $data['authentication_type'])
                    ->where ('ac.id = ?',1)
                    ->execute();
            }
        }

        // save systemsetting
        if (isset($data['systemsetting_language'])) {
            $data['systemsetting_showposition'] = isset($data['systemsetting_showposition']) ? $data['systemsetting_showposition'] : 0;
            $data['systemsetting_allowunencryptedrequest'] = isset($data['systemsetting_allowunencryptedrequest']) ? $data['systemsetting_allowunencryptedrequest'] : 0;
            $data['systemsetting_sendreceivermail'] = isset($data['systemsetting_sendreceivermail']) ? $data['systemsetting_sendreceivermail'] : 0;
            $data['systemsetting_sendremindermail'] = isset($data['systemsetting_sendremindermail']) ? $data['systemsetting_sendremindermail'] : 0;
            
            Doctrine_Query::create()
                ->update('SystemConfiguration sc')
                ->set('sc.language','?',$data['systemsetting_language'])
                ->set('sc.showpositioninmail','?',$data['systemsetting_showposition'])
                ->set('sc.allowunencryptedrequest','?',$data['systemsetting_allowunencryptedrequest'])
                ->set('sc.sendreceivermail','?',$data['systemsetting_sendreceivermail'])
                ->set('sc.sendremindermail','?',$data['systemsetting_sendremindermail'])
                ->set('sc.visibleslots','?',$data['systemsetting_slotvisible'])
                ->where ('sc.id = ?',1)
                ->execute();
        }
        // store Email tab
        if (isset($data['emailtab_emailtype'])) { 

            $data['emailtab_encryption'] = $data['emailtab_encryption'] == 'NONE' ? '' : $data['emailtab_encryption'];
            $emailAuth = isset($data['email_smtp_auth']) ? 1 : 0;
            Doctrine_Query::create()
                ->update('EmailConfiguration ec')
                ->set('ec.systemreplyaddress', '?', $data['emailtab_systemreplyaddress'])
                ->set('ec.activetype', '?', $data['emailtab_emailtype'])
                ->set('ec.smtphost', '?', $data['email_smtp_server'])
                ->set('ec.smtpport', '?', $data['email_smtp_port'])
                ->set('ec.smtpusername', '?', $data['email_smtp_username'])
                ->set('ec.smtppassword', '?', $data['email_smtp_password'])
                ->set('ec.smtpencryption', '?', $data['emailtab_encryption'])
                ->set('ec.smtpuseauth', '?', $emailAuth)
                ->set('ec.sendmailpath', '?', $data['email_sendmail'])
                ->where('ec.id = ?',1)
                ->execute();
        }



        if (isset($data['userTab_defaultdurationtype'])) {
            $data['userTab_markred'] = $data['userTab_markred'] == '' ? 12 : $data['userTab_markred'];
            $data['userTab_markyellow'] = $data['userTab_markyellow'] == '' ? 7 : $data['userTab_markyellow'];
            $data['userTab_markorange'] = $data['userTab_markorange'] == '' ? 10 : $data['userTab_markorange'];
            $data['userTab_defaultdurationlength'] = $data['userTab_defaultdurationlength'] == '' ? 3 : $data['userTab_defaultdurationlength'];

            Doctrine_Query::create()
                ->update('UserConfiguration uc')
                ->set('uc.durationtype', '?', $data['userTab_defaultdurationtype'])
                ->set('uc.durationlength', '?', $data['userTab_defaultdurationlength'])
                ->set('uc.displayeditem', '?', $data['userTab_itemsperpage'])
                ->set('uc.refreshtime', '?', $data['userTab_refreshtime'])
                ->set('uc.markyellow', '?', $data['userTab_markyellow'])
                ->set('uc.markred', '?', $data['userTab_markred'])
                ->set('uc.markorange', '?', $data['userTab_markorange'])
                ->set('uc.password', '?', $data['userTab_defaultpassword'])
                ->set('uc.emailformat', '?', $data['userTab_emailformat'])
                ->set('uc.emailtype', '?', $data['userTab_emailtype'])
                ->set('uc.circulationdefaultsortcolumn', '?', $data['userTab_circulationdefaultsortcolumn'])
                ->set('uc.circulationdefaultsortdirection', '?', $data['userTab_circulationdefaultsortdirection'])
                ->set('uc.role_id', '?', $data['userTab_userrole'])
                ->where('uc.id = ?',1)
                ->execute();

            
        }


        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
