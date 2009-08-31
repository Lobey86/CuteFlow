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
                ->where('u.username = ?', $request->getParameter('username'))
                ->andwhere('u.password = ?',$request->getParameter('password'))
                ->execute();
      
    if($result[0]->getUserName() == $request->getParameter('username') AND $result[0]->getPassword() == $request->getParameter('password') AND trim($request->getParameter('password')) != '' AND trim($request->getParameter('username'))) {
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->setAttribute('id',$result[0]->getId());
        $this->getUser()->setAttribute('userrole',$result[0]->getRoleId());
        $this->getUser()->setCulture($request->getParameter('language'));
        $this->renderText('1');
    }
    else {
        $return['errorMessage'] = $this->getContext()->getI18N()->__('Failure during login process',null,'login');
        $return['errorTitle'] = $this->getContext()->getI18N()->__('Error',null,'login');
        $this->renderText('{"result":'.json_encode($return).'}');
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