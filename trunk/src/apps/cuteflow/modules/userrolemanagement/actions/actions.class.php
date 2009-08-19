<?php

/**
 * userrolemanagement actions.
 *
 * @package    cf
 * @subpackage userrolemanagement
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class userrolemanagementActions extends sfActions
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


  public function executeLoadAllRoles(sfWebRequest $request)
  {
      $userrolemanagement = new UserRolemanagement();
      $query = new Doctrine_Query();
      $result = $query->select('r.*, count(u.id) AS users')->from('Role r')->leftJoin('r.User u')->groupby('r.id')->execute();
      $json_result = $userrolemanagement->buildRole($result, 1);

      $this->renderText('({"result":'.json_encode($json_result).'})');
      return sfView::NONE;
  }
  
  public function executeLoadDeletableRoles(sfWebRequest $request)
  {
      $userrolemanagement = new UserRolemanagement();
      $query = new Doctrine_Query();
      $result = $query->select('r.*')->from('Role r')->where('r.id != ?', $request->getParameter('id'))->execute();


      $json_result = $userrolemanagement->buildRoleCombobox($result);

      $this->renderText('({"result":'.json_encode($json_result).'})');
      return sfView::NONE;
  }


  public function executeDeleteRole(sfWebRequest $request)
  {
      $update = new Doctrine_Query();
      $rows = $update->update('User')->set('role_id',$request->getParameter('updateid'))->where('role_id = ?', $request->getParameter('deleteid'))->execute();

      $del_credentialrole = new Doctrine_Query();
      $del_credentialrole->delete('CredentialRole')->from ('CredentialRole cr')-> where('role_id = ?', $request->getParameter('deleteid'))->execute();
      $del_role = new Doctrine_Query();
      $del_role->delete('Role')->from('Role r')->where('id = ?', $request->getParameter('deleteid'))->execute();
      $this->renderText($rows);
      return sfView::NONE;
  }


  public function executeLoadRoleTree(sfWebRequest $request)
  {

   $query = new Doctrine_Query();
   $result = $query->from('Credential c')->orderby('c.usermodule asc,c.usergroup asc')->execute();
   $credentialmanagement = new CredentialRolemanagement($result);
   $json_result = $credentialmanagement->buildTabpanel();

   $this->renderText('{"result":'.json_encode($json_result).'}');
   return sfView::NONE;
  }

}
