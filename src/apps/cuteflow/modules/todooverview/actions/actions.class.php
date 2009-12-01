<?php

/**
 * todooverview actions.
 *
 * @package    cf
 * @subpackage todooverview
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class todooverviewActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request){
        $this->forward('default', 'module');
    }


    public function executeLoadAllOwnWorkflow(sfWebRequest $request) {
        $workflow = new WorkflowOverview($this->getContext(), $this->getUser());
        $workflow->setUserId($this->getUser()->getAttribute('id'));
        $workflow->setCulture($this->getUser()->getCulture());
        $data = WorkflowTemplateTable::instance()->getAllToDoWorkflowTemplates(-1, -1,$this->getUser()->getAttribute('id'));
        $json_data = $workflow->buildData($data);
        $this->renderText('({"result":'.json_encode($json_data).'})');
        return sfView::NONE;
    }


}
