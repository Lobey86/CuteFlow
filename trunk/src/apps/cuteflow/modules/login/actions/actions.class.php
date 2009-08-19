<?php

/**
 * login actions.
 *
 * @package    cf
 * @subpackage login
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class loginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }

  /*
  * Dummy Action, to register User......add ldap functionality here...
  *
  */
  public function executeDoLogin(sfWebRequest $request)
  {
    $query = new Doctrine_Query();
    $result = $query->select('u.*')->from('User u')->where('u.username = ?', $request->getParameter('username'))->andwhere('u.password = ?',$request->getParameter('password'))->execute();
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


  public function executeLoadLanguage(sfWebRequest $request)
  {
    $result = array();
    $langObject = new Language();
    $result = $langObject->extractLanguages($this->getRequest()->getLanguages());
    $result = $langObject->buildLanguages($result);
    $this->renderText('({"result":'.json_encode($result).'})');
    return sfView::NONE;
  }

  public function executeChangeLanguage(sfWebRequest $request)
  {
    $this->getUser()->setCulture($request->getParameter('language'));
    $result['login'] = $this->getContext()->getI18N()->__('Login',null,'login');
    $result['username'] = $this->getContext()->getI18N()->__('Username',null,'login');
    $result['password'] = $this->getContext()->getI18N()->__('Password',null,'login');
    $result['language'] = $this->getContext()->getI18N()->__('Language',null,'login');
    $result['close'] = $this->getContext()->getI18N()->__('Close',null,'login');
    $default = Language::buildDefaultLanguage($this->getUser()->getCulture());
    $this->renderText('{"defaultValue":"'.$default.'","result":'.json_encode($result).'}');
    return sfView::NONE;
  }

}
