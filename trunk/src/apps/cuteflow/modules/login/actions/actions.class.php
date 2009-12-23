<?php

/**
 * login actions.
 *
 * @package    cf
 * @subpackage login
 * @author     Manuel Schaefer
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class loginActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        sfLoader::loadHelpers('Url');
        $this->getUser()->setCulture(Language::loadDefaultLanguage());
        $tm = new ThemeManagement();
        $systemTheme = UserConfigurationTable::instance()->getUserConfiguration()->toArray();
        $this->theTheme = $systemTheme[0]['theme'];
        $this->version_id = $request->getParameter('versionid',-1);
        $this->workflow_id = $request->getParameter('workflow',-1);
        

        return sfView::SUCCESS;
    }

    /**
     * 
    * Dummy Action, to login User......add ldap functionality here...
    *
    */
    public function executeDoLogin(sfWebRequest $request) {
    $result = UserLoginTable::instance()->findUserByNameAndPassword($request->getPostParameter('username'), $request->getPostParameter('userpassword'));
    if($result[0]->getUserName() == $request->getPostParameter('username') AND $result[0]->getPassword() == $request->getPostParameter('userpassword')) {
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->setAttribute('id',$result[0]->getId());
        $this->getUser()->setAttribute('userrole',$result[0]->getRoleId());
        $this->getUser()->setCulture($request->getPostParameter('hiddenfield_language'));
        $this->getUser()->setAttribute('env', str_replace('/', '', $request->getPostParameter('hidden_symfonyurl')));
        $this->renderText('{success:true,value:"1"}');
    }
    else {
        $this->renderText('{success:true, text:"'.$this->getContext()->getI18N()->__('Failure during login process',null,'login').'", title: "'.$this->getContext()->getI18N()->__('Error',null,'login').'"}');
    }
    return sfView::NONE;
  }


    /**
    * Function loads new Language for the combobox on Login Page
    *
    * @param sfWebRequest $request
    * @return <type>
    */
    public function executeLoadLanguage(sfWebRequest $request) {
        $result = array();
        $langObject = new Language();
        $result = $langObject->extractLanguages($this->getRequest()->getLanguages());
        $result = $langObject->buildLanguages($result);
        $this->renderText('({"result":'.json_encode($result).'})');
        return sfView::NONE;
    }

    /**
    *
    * Function loads all language files to change the
    * labels of the textfields and buttons.
    *
    * @param sfWebRequest $request
    * @return <type>
    */
    public function executeChangeLanguage(sfWebRequest $request) {
        $language = new Language();
        $this->getUser()->setCulture($request->getParameter('language'));
        $result = $language->loadAjaxLanguage($this->getContext());
        $default = Language::buildDefaultLanguage($this->getUser()->getCulture());
        $this->renderText('{"defaultValue":"'.$default.'","result":'.json_encode($result).'}');
        return sfView::NONE;
    }



    public function executeAuth(sfWebRequest $request) {

        
        

         $openid = sfOpenID::simplifyURL($this->getRequestParameter('openid_identity'));
        print_r ($openid);




        



         /*
        * $c
        * redentials = new ezcAuthenticationPasswordCredentials( 'manu@apk.local', 'gisbert' );
         $ldap = new ezcAuthenticationLdapInfo( 'apk.local', 's=%s%', 'dc=apk,dc=local', 389 );
         $authentication = new ezcAuthentication( $credentials );
         $authentication->addFilter( new ezcAuthenticationLdapFilter( $ldap ) );

         if ( !$authentication->run() )  {

             echo "test";
         }*/


        die;
        $ds = ldap_connect( 'apk.local' );
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) ;
        $login = ldap_bind( $ds, 'manu@apk.local', 'gisbert' );

        $filter="(objectClass=*)";
        $justthese = array("ou", "sn", "givenname", "mail");

        $sr=ldap_read($ds, "","(objectClass=*)");
        $entry = ldap_get_entries($ds, $sr);
        print_r ($entry);die;









        die;
        return sfView::NONE;
    }

}