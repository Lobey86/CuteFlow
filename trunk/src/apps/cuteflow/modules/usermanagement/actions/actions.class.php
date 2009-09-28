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
     * Function loads Users for left grid when adding user agents
     * 
     * @param sfWebRequest $reques
     * @return <type> 
     */
    public function executeLoadUserGrid(sfWebRequest $request) {
        $usermanagement = new Usermanagement();

        $result = Doctrine_Query::create()
            ->select('ud.user_id, CONCAT(ud.firstname,\' \',ud.lastname) AS text')
            ->from('UserData ud')
            ->execute();

        $json_result = $usermanagement->buildUserGrid($result);
        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }

    /**
     * Functions loads useragents when in edit mode
     *
     * @param sfWebRequest $request
     */
    public function executeLoadUserAgentGrid(sfWebRequest $request) {
        $usermanagement = new Usermanagement();
        $result = Doctrine_Query::create()
            ->select('ua.*')
            ->from('UserAgent ua')
            ->where('ua.user_id = ?', $request->getParameter('id'))
            ->orderBy('ua.position asc')
            ->execute();
        
        $json_result = $usermanagement->builUserAgentGrid($result);
        $this->renderText('{"result":'.json_encode($json_result).'}');
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
            ->from('UserLogin ul')
            ->where('ul.username = ?', $request->getParameter('username'))
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
        $result = Doctrine_Query::create()
            ->select('ul.*')
            ->from('UserLogin ul')
            ->where('ul.id = ?',$request->getParameter('id'))
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
        $this->renderText('{success:true}');
        return sfView::NONE;
    }
}
