<?php

/**
 * myprofile actions.
 *
 * @package    cf
 * @subpackage myprofile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class myprofileActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Load circulationsettings for an exisitng user
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadUserCirculationColumns(sfWebRequest $request) {
        $sysObj = new SystemSetting();
        $worklfosettings = Doctrine_Query::create()
            ->select('uwc.*')
            ->from('UserWorkflowConfiguration uwc')
            ->where('uwc.user_id = ?', $request->getParameter('id'))
            ->orderBy('uwc.position ASC')
            ->fetchArray();
        $worklfosettings = $sysObj->buildColumns($worklfosettings, $this->getContext());

        $this->renderText('{"result":'.json_encode($worklfosettings).'}');
        return sfView::NONE;
        
    }

}
