<?php

/**
* menue actions.
*
* @package    cf
* @subpackage menue
* @author     Your name here
* @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
*/
class menueActions extends sfActions {
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    public function executeLoadMenue(sfWebRequest $request) {
        $menueObject = new Menue();

        $result = Doctrine_Query::create()
            ->from('Credential c')
            ->orderby('c.usermodule asc,c.usergroup asc')
            ->execute();
        
        $menueObject->setRecords($result);
        $menueObject->setUserright($this->getUser()->getAttribute('credential'));
        $menueObject->setContext($this->getContext());

        $json_result = $menueObject->buildTree();
        $this->renderText('{"result":'.json_encode($json_result).'}');
     
        return sfView::NONE;
    }



}
