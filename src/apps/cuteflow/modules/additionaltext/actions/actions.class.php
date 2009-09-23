<?php

/**
 * additionaltext actions.
 *
 * @package    cf
 * @subpackage additionaltext
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class additionaltextActions extends sfActions {
    /**
    *
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Function loads all Additional Textes for DataGrid.
     * @param sfWebRequest $request
     * @return <type> 
     */
    public function executeLoadAllText(sfWebRequest $request) {
       $addTextObj = new AddText();
       $result = Doctrine_Query::create()
            ->from('AdditionalText at')
            ->select('at.*')
            ->orderBy('at.id DESC')
            ->execute();
       $json_result = $addTextObj->buildAllText($result);
       $this->renderText('{"result":'.json_encode($json_result).'}');
       return sfView::NONE;
    }

    /**
     * Saves a new Text to database
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveText(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $textObj = new AdditionalText();
        $textObj->setTitle($data['title']);
        $textObj->setContent($data['content']);
        $textObj->setContenttype($data['contenttype']);
        $textObj->setIsactive(0);
        $textObj->save();
        

        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     * Changes standrad radio button
     * 
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSetStandard(sfWebRequest $request) {
        Doctrine_Query::create()
            ->update('AdditionalText at')
            ->set('at.isactive','?',0)
            ->execute();
        Doctrine_Query::create()
            ->update('AdditionalText at')
            ->set('at.isactive','?',1)
            ->where('at.id = ?', $request->getParameter('id'))
            ->execute();
        return sfView::NONE;
    }

    /**
     * Load a single Text
     * @param sfWebRequest $request
     */
    public function executeLoadText(sfWebRequest $request) {
       $result = Doctrine_Query::create()
            ->from('AdditionalText at')
            ->select('at.*')
            ->where('at.id = ?', $request->getParameter('id'))
            ->fetchArray();

       $this->renderText('{"result":'.json_encode($result[0]).'}');
       return sfView::NONE;
    }

    /**
     * Update existing text
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeUpdateText(sfWebRequest $request) {
        $data = $request->getPostParameters();
        Doctrine_Query::create()
            ->update('AdditionalText at')
            ->set('at.title','?',$data['title'])
            ->set('at.contenttype','?',$data['contenttype'])
            ->set('at.content','?',$data['content'])
            ->where('at.id = ?', $request->getParameter('id'))
            ->execute();
        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     * Delete Text from database
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeDeleteText(sfWebRequest $request) {
        Doctrine_Query::create()
            ->delete('AdditionalText')
            ->from('AdditionalText at')
            ->where('at.id = ?',$request->getParameter('id'))
            ->execute();
        return sfView::NONE;
    }

    public function executeCopyText(sfWebRequest $request) {
       $result = Doctrine_Query::create()
            ->from('AdditionalText at')
            ->select('at.*')
            ->where('at.id = ?', $request->getParameter('id'))
            ->fetchArray();

        
        $textObj = new AdditionalText();
        $textObj->setTitle('Kopie ' . $result[0]['title']);
        $textObj->setContent($result[0]['content']);
        $textObj->setContenttype($result[0]['contenttype']);
        $textObj->setIsactive(0);
        $textObj->save();

    
        return sfView::NONE;
    }

}
