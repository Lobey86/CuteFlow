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
                ->from('UserLogin ul')
                ->where('ul.deleted = ?', 0)
                ->execute();

        $limit = $this->getUser()->getAttribute('userSettings');
        $result = Doctrine_Query::create()
                ->select('ul.*')
                ->from('UserLogin ul')
                ->orderby('ul.id DESC')
                ->limit($request->getParameter('limit',$limit['displayeditem']))
                ->offset($request->getParameter('start',0))
                ->where('ul.deleted = ?', 0)
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
              ->where('ul.deleted = ?', 0)
              ->leftJoin('ul.UserData ud')
              ->from('UserLogin ul, UserData ud');

        if($request->hasParameter('username')){
            $query->andWhere('ul.username LIKE ?','%'.$request->getParameter('username').'%');
        }
        if($request->hasParameter('firstname')){
            $query->andWhere('ud.firstname LIKE ?','%'.$request->getParameter('firstname').'%');
        }
        if($request->hastParameter('lastname')){
            $query->andWhere('ud.lastname LIKE ?','%'.$request->getParameter('lastname').'%');
        }
        if($request->hasParameter('email')){
            $query->andWhere('ul.email LIKE ?','%'.$request->getParameter('email').'%');
        }

        if($request->hasParameter('userrole')){
            $query->andWhere('ul.role_id = ?',$request->getParameter('userrole'));
        }

        $anz = $query->execute();
        $limit = $this->getUser()->getAttribute('userSettings');
        $result = $query->select('ul.*')
                        ->orderby('ul.id DESC')
                        ->limit($request->getParameter('limit',$limit['displayeditem']))
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
                    ->where('r.deleted = ?', 0)
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
                    ->update('UserLogin ul')
                    ->set('ul.deleted','?',1)
                    ->where('ul.id = ?', $request->getParameter('id'))
                    ->andWhere('ul.id != ?', $this->getUser()->getAttribute('id'))
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
            ->leftJoin('ud.UserLogin ul')
            ->where('ul.deleted = ?', 0)
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
            ->leftJoin('ua.UserData ud')
            ->leftJoin('ud.UserLogin ul')
            ->orderBy('ua.position asc')
            ->where('ul.deleted = ?', 0)
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
            ->andWhere('ul.deleted = ?', 0)
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
    public function executeUpdateUser(sfWebRequest $request) {
        $store = new UserCRUD();
        $data = $request->getPostParameters();
        $store->updateLoginDataTab($data, $request->getParameter('id')); // update first tab
        $store->updateAdditionalDataTab($data, $request->getParameter('id'));
        $store->updateGUISettingsTab($data, $request->getParameter('id'));
        $store->updateUseragentSettings($data, $request->getParameter('id'));
        $store->deleteWorklfowSettings($request->getParameter('id'));
        $store->saveWorklfowSettings($data, $request->getParameter('id'));
        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     * Create new user
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveUser(sfWebRequest $request) {
        $store = new UserCRUD();
        $result = Doctrine_Query::create()
            ->select('uc.*')
            ->from('UserConfiguration uc')
            ->fetchArray();
        $data = $request->getPostParameters();
        $user_id = $store->saveLoginDataTab($data);
        $store->updateAdditionalDataTab($data, $user_id);
        $store->saveGUISettingsTab($data, $user_id, $result[0]);
        $store->saveUseragentSettings($data, $user_id, $result[0]);
        $store->saveWorklfowSettings($data, $user_id);

        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     * Load default system settings for creating new user.
     * 
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadDefaultData(sfWebRequest $request) {
        $result = Doctrine_Query::create()
            ->select('uc.*')
            ->from('UserConfiguration uc')
            ->fetchArray();
        $this->renderText('{"result":'.json_encode($result[0]).'}');
        return sfView::NONE;
    }







}
