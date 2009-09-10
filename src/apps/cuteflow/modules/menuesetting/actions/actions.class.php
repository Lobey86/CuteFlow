<?php

/**
 * menuesetting actions.
 *
 * @package    cf
 * @subpackage menuesetting
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class menuesettingActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request){
        $this->forward('default', 'module');
    }


    /**
     * loads all exisitng Modules
     *
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadModule (sfWebRequest $request) {
        $settingObj = new MenueSetting();


        $module = Doctrine_Query::create()
            ->select('DISTINCT c.usermodule as usermodule')
            ->from('Credential c')
            ->orderBy('c.usermoduleposition asc')
            ->execute();

        $settingObj->setContext($this->getContext());
        $json_result = $settingObj->buildModule($module);

        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;

    }

    /**
     * Save new order of Module
     *
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveModule(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $order = $data['grid'];

        $position = 1;
        foreach($order as $item) {
            $module = Doctrine_Query::create()
                ->update('Credential c')
                ->set('c.usermoduleposition','?',$position++)
                ->where('c.usermodule = ?', $item)
                ->execute();
        }

        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    
    public function executeLoadGroup (sfWebRequest $request) {
        $settingObj = new MenueSetting();


        $groups = Doctrine_Query::create()
            ->select('c.*')
            ->from('Credential c')
            ->where('c.usermodule = ?', $request->getParameter('id'))
            ->andWhere('c.userright = ?', 'showModule')
            ->orderBy ('c.usergroupposition ASC')
            ->execute();

        $settingObj->setContext($this->getContext());
        $json_result = $settingObj->buildGroup($groups);

        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;

    }

    /**
     * Save new order of Group
     *
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveGroup(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $order = $data['grid'];
        $position = 1;

        foreach($order as $item) {
            $module = Doctrine_Query::create()
                ->update('Credential c')
                ->set('c.usergroupposition','?',$position++)
                ->where('c.usermodule = ?', $data['module'])
                ->andWhere('c.usergroup = ?', $item)
                ->execute();
        }

        $this->renderText('{success:true}');
        return sfView::NONE;
    }










}
