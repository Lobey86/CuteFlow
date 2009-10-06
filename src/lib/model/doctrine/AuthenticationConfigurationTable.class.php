<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AuthenticationConfigurationTable extends Doctrine_Table {
    /**
     * create new instance of AuthenticationConfigurationTable
     * @return object AuthenticationConfigurationTable
     */
    public static function instance() {
        return Doctrine::getTable('AuthenticationConfiguration');
    }

    /**
     * Loads Auth config
     * @return Doctrine_Collection
     */
    public function getAuthenticationConfiguration() {
        return Doctrine_Query::create()
                ->select('ac.*')
                ->from('AuthenticationConfiguration ac')
                ->execute();
    }

    /**
     * Update Cuteflowdatabase only
     *
     * @param array $data
     * @return true
     */
    public function updateAuthenticationConfigurationCuteflowDatabase (array $data) {
        Doctrine_Query::create()
                    ->update('AuthenticationConfiguration ac')
                    ->set('ac.authenticationtype', '?', $data['authentication_type'])
                    ->set('ac.firstlogin','?',isset($data['authentication_firstlogin']) ? $data['authentication_firstlogin'] : 0)
                    ->where ('ac.id = ?',1)
                    ->execute();
        return true;
    }

    /**
     * Update when Database and LDAP is selected
     * @param array $data
     * @return true
     */
    public function updateAuthenticationConfigurationCuteflowDatabaseAndLDAP (array $data) {
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
            ->set('ac.firstlogin','?',isset($data['authentication_firstlogin']) ? $data['authentication_firstlogin'] : 0)
            ->set('ac.ldapadduser','?',isset($data['auth_ladp_adduser']) ? $data['auth_ladp_adduser'] : 0)
            ->where ('ac.id = ?',1)
            ->execute();
        return true;
    }


    /**
     * Update when openID is selected
     *
     * @param array $data
     * @return true
     */
    public function updateAuthenticationConfigurationCuteflowDatabaseAndOpenId(array $data) {
        Doctrine_Query::create()
                    ->update('AuthenticationConfiguration ac')
                    ->set('ac.openidserver', '?', $data['auth_openid_server'])
                    ->set('ac.authenticationtype', '?', $data['authentication_type'])
                    ->set('ac.firstlogin','?',isset($data['authentication_firstlogin']) ? $data['authentication_firstlogin'] : 0)
                    ->where ('ac.id = ?',1)
                    ->execute();
        return true;
    }


    /**
     * Load Firstlogin flag
     * @return Doctrine_Collection
     */
    public function getFirstLogin() {
        return Doctrine_Query::create()
            ->select('ac.firstlogin')
            ->from('AuthenticationConfiguration ac')
            ->execute();
    }


    /**
     * Load authType
     * @return Doctrine_Collection
     */
    public function getAuthenticationType() {
        return Doctrine_Query::create()
            ->select('ac.authenticationtype')
            ->from('AuthenticationConfiguration ac')
            ->execute();
    }


}