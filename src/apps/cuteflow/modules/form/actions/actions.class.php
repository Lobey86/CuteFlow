<?php

/**
 * form actions.
 *
 * @package    cf
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class formActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Load all Document templates
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllForms(sfWebRequest $request) {
        return sfView::NONE;
    }

    /**
     * Load all fields for creating/editing document templates
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadAllFields(sfWebRequest $request) {
        $fieldObj = new FieldClass();
        $result = FieldTable::instance()->getAllFields();
        $json_result = $fieldObj->buildField($result, $this->getContext());
        $this->renderText('({"result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }

}
