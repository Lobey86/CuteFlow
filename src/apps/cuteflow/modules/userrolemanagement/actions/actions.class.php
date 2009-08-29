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


  /**
   *
   * Function loads all Roles for the Grid.
   * Actually no Filter and Paging is needed
   *
   * @param sfWebRequest $request
   * @return <type>
   */
  public function executeLoadAllRoles(sfWebRequest $request)
  {
      $userrolemanagement = new UserRolemanagement();
      $query = new Doctrine_Query();
      $result = $query->select('r.*, count(u.id) AS users')->from('Role r')->leftJoin('r.User u')->groupby('r.id')->execute();
      $json_result = $userrolemanagement->buildRole($result, 1);

      $this->renderText('({"result":'.json_encode($json_result).'})');
      return sfView::NONE;
  }


 /**
  * Function loads all roles for the pop 'Delete Role'.
  * The Role, user want to delete is not loaded
  *
  * @param sfWebRequest $request
  * @return <type>
  */
  public function executeLoadDeletableRoles(sfWebRequest $request)
  {
      $userrolemanagement = new UserRolemanagement();
      $query = new Doctrine_Query();
      $result = $query->select('r.*')->from('Role r')->where('r.id != ?', $request->getParameter('id'))->execute();

      $json_result = $userrolemanagement->buildRoleCombobox($result);

      $this->renderText('({"result":'.json_encode($json_result).'})');
      return sfView::NONE;
  }

  /**
   *
   * Function Deletes a Role, and sets all users of the deleted Role
   * to the selected Role from Combobox
   *
   * @param sfWebRequest $request
   * @return <type>
   */
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


  /**
   * Function builds the Tabs, Groups and Rights for the Pop 'Add Role' and 'Edit Role'
   *
   * @param sfWebRequest $request
   * @return <type>
   */
  public function executeLoadRoleTree(sfWebRequest $request)
  {
   $credentialmanagement = new CredentialRolemanagement();
   if ($request->getParameter('role_id') != '') { // Flag if Role is edited
       $credentialQuery = new Doctrine_Query();
       $res = $credentialQuery->select('cr.credential_id')->from('CredentialRole cr')->where('cr.role_id = ?', $request->getParameter('role_id'))->execute();
       $credentials = $credentialmanagement->buildCredentials($res);

       $roleQuery = new Doctrine_Query();
       $roleName = $roleQuery->select('r.description')->from('Role r')->where('r.id = ?', $request->getParameter('role_id'))->execute();
   }
   
   $query = new Doctrine_Query();
   $result = $query->from('Credential c')->orderby('c.usermodule asc,c.usergroup asc')->execute();
   $credentialmanagement->setRecords($result);
   $json_result = $credentialmanagement->buildTabpanel($credentials);

   if ($request->getParameter('role_id') != '') {
       $this->renderText('{"result":'.json_encode($json_result).',"name":"'.$roleName[0]->getDescription().'"}');
   }
   else {
       $this->renderText('{"result":'.json_encode($json_result).',"name":""}');
   }
   return sfView::NONE;
  }


  /**
   * Function checks, when user is trying to create a new userrole , if its
   * name is already stored in database.
   * When alreasy storen, no save process is done.
   *
   *
   * @param sfWebRequest $request
   * @return <type>
   */
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



  /**
   * Function adds a role to database
   *
   * @param sfWebRequest $request
   * @return <type>
   */
  public function executeAddRole(sfWebRequest $request)
  {
   
   $data = $request->getPostParameters();
   if(count($data) > 2) { // some rights are set
        unset($data['userrole_title_name']);
        unset($data['hiddenfield']);
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
   $this->renderText('{success:true}');
   return sfView::NONE;
  }


  /**
   * Changes an Edited Role
   *
   * @param sfWebRequest $request
   * @return <type>
   */
  public function executeEditRole(sfWebRequest $request)
  {
   $data = $request->getPostParameters();
   $id = $this->getRequestParameter('hiddenfield');
   $delQuery = new Doctrine_Query();
   $delQuery->delete('CredentialRole')->from('CredentialRole cr')->where('cr.role_id = ?',$id)->execute();
   unset($data['hiddenfield']);
   $values = array_keys($data);

   foreach($values as $item) {
        $rolecredObj = new CredentialRole();
        $rolecredObj->setRole_id($id);
        $rolecredObj->setCredential_id($item);
        $rolecredObj->save();
    }
   $this->renderText('{success:true}');
   return sfView::NONE;
  }


  
}
