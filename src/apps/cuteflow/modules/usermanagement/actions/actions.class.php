<?php

/**
* usermanagement actions.
*
* @package    cf
* @subpackage usermanagement
* @author     Your name here
* @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
*/
class usermanagementActions extends sfActions {
    
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        return sfView::NONE;
    }

    /**
    *
    * Function loads all Users for Datagrid overview.
    *
    * @param sfWebRequest $request
    * @return <type>
    */
    public function executeLoadAllUser(sfWebRequest $request) {
        $json_result = array();
        $usermanagement = new Usermanagement();


        $anz = Doctrine_Query::create()
                ->select('COUNT(*) AS anzahl')
                ->from('User u')
                ->execute();

        $result = Doctrine_Query::create()
                ->select('u.*')
                ->from('User u')
                ->orderby('u.id DESC')
                ->limit($request->getParameter('limit',$this->getUser()->getAttribute('userSettings')->getDisplayeditem()))
                ->offset($request->getParameter('start',0))
                ->execute();

        $json_result = $usermanagement->buildUser($result, $this->getRequestParameter('start',0)+1);

        $data = '({"total":"'.$anz[0]->getAnzahl().'","result":'.json_encode($json_result).'})';
        $this->renderText($data);

        return sfView::NONE;
    }


    /**
    *
    * Filter functionality for User Grid
    *
    * @param sfWebRequest $request
    * @return <type>
    */
    public function executeLoadAllUserFilter(sfWebRequest $request) {
        $json_result = array();
        $usermanagement = new Usermanagement();

        $query = new Doctrine_Query();
        $query->select('COUNT(*) AS anzahl')
              ->from('User u');

        if($request->getParameter('username')){
            $query->andwhere('u.username LIKE ?','%'.$request->getParameter('username').'%');
        }
        if($request->getParameter('firstname')){
            $query->andwhere('u.firstname LIKE ?','%'.$request->getParameter('firstname').'%');
        }
        if($request->getParameter('lastname')){
            $query->andwhere('u.lastname LIKE ?','%'.$request->getParameter('lastname').'%');
        }
        if($request->getParameter('email')){
            $query->andwhere('u.email LIKE ?','%'.$request->getParameter('email').'%');
        }

        if($request->getParameter('userrole')){
            $query->andwhere('u.role_id = ?',$request->getParameter('userrole'));
        }

        $anz = $query->execute();
        $result = $query->select('u.*')
                        ->orderby('u.id DESC')
                        ->limit($request->getParameter('limit',$this->getUser()
                        ->getAttribute('userSettings')->getDisplayeditem()))
                        ->offset($request->getParameter('start',0))
                        ->execute();

        $json_result = $usermanagement->buildUser($result, $this->getRequestParameter('start',0)+1);

        $data = '({"total":"'.$anz[0]->getAnzahl().'","result":'.json_encode($json_result).'})';
        $this->renderText($data);

        return sfView::NONE;
    }


    /**
    *
    * Loads all Roles for the Combobox in the filter.
    * Is only called when combo is opend first time
    *
    * @param sfWebRequest $request
    * @return <type>
    */
    public function executeLoadAllRole(sfWebRequest $request) {
        $usermanagement = new Usermanagement();

        $result = Doctrine_Query::create()
                    ->select('r.*')
                    ->from('Role r')
                    ->execute();
        $json_result = $usermanagement->buildRole($result,0);

        $this->renderText('({"result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }

    /**
    *
    * Function removes user from database.
    *
    * @param sfWebRequest $request
    * @return <type>
    */
    public function executeDeleteUser(sfWebRequest $request) {
    
        Doctrine_Query::create()
            ->delete('User')
            ->from('User u')
            ->where('u.id = ?',$request->getParameter('id'))
            ->andwhere('u.id != ?', $this->getUser()->getAttribute('id'))
            ->execute();
        return sfView::NONE;
    }

    
    /**
     * Function loads Users for SuperComboBox
     * 
     * @param sfWebRequest $reques
     * @return <type> 
     */
    public function executeLoadSuperComboboxUser(sfWebRequest $request) {
        $usermanagement = new Usermanagement();

        $result = Doctrine_Query::create()
            ->select('u.id, CONCAT(u.firstname,\' \',u.lastname) AS text')
            ->from('User u')
            ->execute();

        $json_result = $usermanagement->buildSuperBoxUser($result);
        $this->renderText('({"result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }


    /**
     * Checks if an user is already in database stored
     *
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeCheckForExistingUser(sfWebRequest $request) {
        $result = Doctrine_Query::create()
            ->from('User u')
            ->where('u.username = ?', $request->getParameter('username'))
            ->execute();

        if($result[0]->getUsername() == $request->getParameter('username')) {
            $this->renderText('0'); // no write access
        }
        else {
            $this->renderText('1'); // write access
        }
        return sfView::NONE;
    }


    /**
     * Stores new user to databse
     * 
     * @param sfWebRequest $reques
     */
    public function executeAddUser(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $userObj = new User();
        $userObj->setFirstname($data['firstname']);
        $userObj->setLastname($data['lastname']);
        $userObj->setUsername($data['username']);
        $userObj->setEmail($data['email']);
        $userObj->setRoleId($data['roleid']);
        $userObj->setPassword($data['password']);
        $userObj->setStreet($data['street']);
        $userObj->setZip($data['zip']);
        $userObj->setCity($data['city']);
        $userObj->setCountry($data['country']);
        $userObj->setPhone1($data['phone1']);
        $userObj->setPhone2($data['phone2']);
        $userObj->setMobile($data['mobil']);
        $userObj->setFax($data['fax']);
        $userObj->setDepartment($data['department']);
        $userObj->setBurdencenter($data['burdencenter']);
        $userObj->setOrganisation($data['organisation']);
        $userObj->setComment($data['comment']);
        $userObj->save();
        $id = $userObj->getId();
        $agent = $data['agent'];

        if (count($agent)>1) {
            array_pop($agent);
            foreach($agent as $item) {
                $agentObj = new UserAgent();
                $agentObj->setUserId($id);
                $agentObj->setUseragentId($item);
                $agentObj->setDurationtype($data['durationtype']);
                $agentObj->setDurationlength($data['durationlength']);
                $agentObj->save();
            }
        }

        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     *
     * Loads Data to edit a single User
     * 
     * @param sfWebRequest $request
     */
    public function executeLoadSingleUser(sfWebRequest $request) {

        $usermanagement = new Usermanagement();

        //$this->renderText($request->getParameter('id'));
        $result = Doctrine_Query::create()
            ->select('u.*')
            ->from('User u')
            ->where('u.id = ?',$request->getParameter('id'))
            ->execute();
        $json_result = $usermanagement->buildSingleUser($result);
        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }

    /**
     * Store function when edit user
     *
     * @param sfWebRequest $request
     */
    public function executeEditUser(sfWebRequest $request) {
        $data = $request->getPostParameters();

        Doctrine_Query::create()
            ->delete('UserAgent')
            ->from('UserAgent ua')
            ->where('ua.user_id = ?',$data['hiddenfield'])
            ->execute();

        Doctrine_Query::create()
            ->update('User u')
            ->set('u.firstname','?',$data['firstname'])
            ->set('u.lastname','?',$data['lastname'])
            ->set('u.email','?',$data['email'])
            ->set('u.role_id','?',$data['roleid'])
            ->set('u.password','?',$data['password'])
            ->set('u.street','?',$data['street'])
            ->set('u.zip','?',$data['zip'])
            ->set('u.city','?',$data['city'])
            ->set('u.country','?',$data['country'])
            ->set('u.phone1','?',$data['phone1'])
            ->set('u.phone2','?',$data['phone2'])
            ->set('u.mobile','?',$data['mobil'])
            ->set('u.fax','?',$data['fax'])
            ->set('u.department','?',$data['department'])
            ->set('u.organisation','?',$data['organisation'])
            ->set('u.comment','?',$data['comment'])
            ->set('u.burdencenter','?',$data['burdencenter'])
            ->where ('u.id = ?',$data['hiddenfield'])
            ->execute();

        $agent = $data['agent'];
        if (count($agent)>1) {
            array_pop($agent);
            foreach($agent as $item) {
                $agentObj = new UserAgent();
                $agentObj->setUserId($data['hiddenfield']);
                $agentObj->setUseragentId($item);
                $agentObj->setDurationtype($data['durationtype']);
                $agentObj->setDurationlength($data['durationlength']);
                $agentObj->save();
            }
        }
        
        $this->renderText('{success:true}');
        return sfView::NONE;
    }
}
