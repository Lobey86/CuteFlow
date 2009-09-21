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
        //$data = sfYaml::Load(sfConfig::get('sf_app_dir') . '/config/i18n.yml');
        $this->getUser()->setCulture(Language::loadDefaultLanguage());
        return sfView::SUCCESS;
    }

    /**
     * 
    * Dummy Action, to login User......add ldap functionality here...
    *
    */
    public function executeDoLogin(sfWebRequest $request) {
    
    $result = Doctrine_Query::create()
                ->select('u.*')
                ->from('User u')
                ->where('u.username = ?', $request->getPostParameter('username'))
                ->andwhere('u.password = ?',$request->getPostParameter('userpassword'))
                ->execute();
        
    if($result[0]->getUserName() == $request->getPostParameter('username') AND $result[0]->getPassword() == $request->getPostParameter('userpassword')) {
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->setAttribute('id',$result[0]->getId());
        $this->getUser()->setAttribute('userrole',$result[0]->getRoleId());
        $this->getUser()->setCulture($request->getPostParameter('hiddenfield_language'));
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

}