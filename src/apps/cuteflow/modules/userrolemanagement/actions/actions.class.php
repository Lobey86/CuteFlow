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


  // loads all roles for usergrid
  public function executeLoadAllRoles(sfWebRequest $request)
  {
      $userrolemanagement = new UserRolemanagement();
      $query = new Doctrine_Query();
      $result = $query->select('r.*, count(u.id) AS users')->from('Role r')->leftJoin('r.User u')->groupby('r.id')->execute();
      $json_result = $userrolemanagement->buildRole($result, 1);

      $this->renderText('({"result":'.json_encode($json_result).'})');
      return sfView::NONE;
  }


 // loads all roles for the popup, to which the members of the deleted role can be added
  public function executeLoadDeletableRoles(sfWebRequest $request)
  {
      $userrolemanagement = new UserRolemanagement();
      $query = new Doctrine_Query();
      $result = $query->select('r.*')->from('Role r')->where('r.id != ?', $request->getParameter('id'))->execute();


      $json_result = $userrolemanagement->buildRoleCombobox($result);

      $this->renderText('({"result":'.json_encode($json_result).'})');
      return sfView::NONE;
  }

  // removes role from database
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


  // loads role tabs, groups and rights to build dynamically
  public function executeLoadRoleTree(sfWebRequest $request)
  {

   $query = new Doctrine_Query();
   $result = $query->from('Credential c')->orderby('c.usermodule asc,c.usergroup asc')->execute();
   $credentialmanagement = new CredentialRolemanagement($result);
   $json_result = $credentialmanagement->buildTabpanel();

   $this->renderText('{"result":'.json_encode($json_result).'}');
   return sfView::NONE;
  }


  public function executeCheckForExistingRole(sfWebRequest $request) 
  {
    $query = new Doctrine_Query();
    $result = $query->from('Role r')->where('r.description = ?', $request->getParameter('description'))->execute();
    if($result[0]->getDescription() == $request->getParameter('description')) {
        $this->renderText('0'); // no write access
    }
    else {
        $this->renderText('1'); // write access
    }
    return sfView::NONE;
  }



  // stores new role in database
  public function executeAddRole(sfWebRequest $request)
  {
   
   $data = $request->getPostParameters();
   if(count($data) > 1) { // some rights are set
        unset($data['userrole_title_name']);
        $values = array_keys($data);

        $roleObj = new Role();
        $roleObj->setDescription($request->getParameter('userrole_title_name'));
        $roleObj->save();
        $id = $roleObj->getId();

        foreach($values as $item) {
            $rolecredObj = new CredentialRole();
            $rolecredObj->setRole_id($id);
            $rolecredObj->setCredential_id($item);
            $rolecredObj->save();
        }
   }
   else { // Only Userrole is written in textfield, nothing else
        $obj = new Role();
        $obj->setDescription($request->getParameter('userrole_title_name'));
        $obj->save();
   }
   return sfView::NONE;
  }



  
}
