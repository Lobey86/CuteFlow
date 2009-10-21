<?php

/**
 * mailinglist actions.
 *
 * @package    cf
 * @subpackage mailinglist
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class mailinglistActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    /**
     * Load all Documents
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllDocuments(sfWebRequest $request) {
        $result = FormTemplateTable::instance()->getAllFormTemplates(-1,-1)->toArray();
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }


    public function executeLoadAllSender(sfWebRequest $request) {
        $result = UserLoginTable::instance()->getAllSenderUser()->toArray();
        $this->renderText('{"result":'.json_encode($result).'}');
        return sfView::NONE;
    }
}
