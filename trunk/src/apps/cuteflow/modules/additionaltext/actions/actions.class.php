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
       $result = AdditionalTextTable::instance()->getAllAdditionalTextes();
       
       $json_result = $addTextObj->buildAllText($result, $this->getContext());
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
        if($data['contenttype'] == 'plain') {
            $data['content'] = $data['content_textarea'];
        }
        else {
            $data['content'] = $data['content_htmleditor'];
        }

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
        $result = AdditionalTextTable::instance()->setActive($request->getParameter('id'));
        return sfView::NONE;
    }

    /**
     * Load a single Text
     * @param sfWebRequest $request
     */
    public function executeLoadText(sfWebRequest $request) {
       $result = AdditionalTextTable::instance()->getSingleText($request->getParameter('id'));

       $this->renderText('{"result":'.json_encode($result[0]->toArray()).'}');
       return sfView::NONE;
    }

    /**
     * Update existing text
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeUpdateText(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $data['content'] = $data['contenttype'] == 'plain' ? $data['content_textarea'] : $data['content_htmleditor'];        
        $result = AdditionalTextTable::instance()->updateText($data,$request->getParameter('id'));
        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     * Delete Text from database
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeDeleteText(sfWebRequest $request) {
        AdditionalTextTable::instance()->deleteText($request->getParameter('id'));
        return sfView::NONE;
    }

    /**
     * Copy an additional text
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeCopyText(sfWebRequest $request) {
        $result = AdditionalTextTable::instance()->getSingleText($request->getParameter('id'));
        $result->toArray();
        
        $textObj = new AdditionalText();
        $textObj->setTitle('Kopie ' . $result[0]['title']);
        $textObj->setContent($result[0]['content']);
        $textObj->setContenttype($result[0]['contenttype']);
        $textObj->setIsactive(0);
        $textObj->save();
        return sfView::NONE;
    }

}