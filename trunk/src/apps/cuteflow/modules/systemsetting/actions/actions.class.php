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
        $email = Doctrine_Query::create()
                ->select('ec.*')
                ->from('EmailConfiguration ec')
                ->fetchArray();
        $auth = Doctrine_Query::create()
                ->select('ac.*')
                ->from('AuthenticationConfiguration ac')
                ->fetchArray();
        $email[0]['smtpencryption'] =  $email[0]['smtpencryption'] == '' ? 'NONE' :  $email[0]['smtpencryption'];
        $this->renderText('{"email":'.json_encode($email[0]).',"auth":'.json_encode($auth[0]).'}');
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

        if (isset($data['systemsetting_language'])) {
            Doctrine_Query::create()
                ->update('SystemConfiguration sc')
                ->set('sc.language','?',$data['systemsetting_language'])
                ->where ('sc.id = ?',1)
                ->execute();
        }
        
        if (isset($data['emailtab_emailtype'])) { // store Email tab

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
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
