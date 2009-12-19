<?php

/**
 * theme actions.
 *
 * @package    cf
 * @subpackage theme
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class themeActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    
    public function executeLoadAllTheme(sfWebRequest $request) {
        $tm = new ThemeManagement();
        $defaultTheme = UserConfigurationTable::instance()->getUserConfiguration()->toArray();
        $tm->setContext($this->getContext());
        $data = $tm->getThemes();
        $data = $tm->checkDefault($data, $defaultTheme[0]['theme']);
        $this->renderText('({"result":'.json_encode($data).'})');
        return sfView::NONE;
    }
    
    public function executeLoadUserTheme(sfWebRequest $request) {
        $tm = new ThemeManagement();
        $defaultTheme = UserSettingTable::instance()->getUserSettingById($request->getParameter('id'))->toArray();
        $tm->setContext($this->getContext());
        $data = $tm->getThemes();
        $data = $tm->checkDefault($data, $defaultTheme[0]['theme']);
        $this->renderText('({"result":'.json_encode($data).'})');
        return sfView::NONE;
    }

}
