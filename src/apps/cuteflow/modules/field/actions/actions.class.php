<?php

/**
 * field actions.
 *
 * @package    cf
 * @subpackage field
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class fieldActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request){
        $this->forward('default', 'module');
    }


    public function executeLoadAllFields(sfWebRequest $request) {
        $fieldObj = new FieldClass();
        $result = FieldTable::instance()->getAllFields();
        $json_result = $fieldObj->buildField($result, $this->getContext());
        $this->renderText('({"result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }


    /**
     * Delete record
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeDeleteField(sfWebRequest $request) {
        FieldTable::instance()->deleteField($request->getParameter('id'));
        return sfView::NONE;
    }


    
    public function executeSaveField(sfWebRequest $request) {
        $data = $request->getPostParameters();
        $fieldClass = new FieldClass();
        $data = $fieldClass->prepareSaveData($data);
        
        $fieldObj = new Field();
        $fieldObj->setTitle($data['createFileWindow_fieldname']);
        $fieldObj->setType($data['createFileWindow_fieldtype']);
        $fieldObj->setwriteprotected($data['createFileWindow_writeprotected']);
        $fieldObj->setColor($data['createFileWindow_color']);
        $fieldObj->save();
        $id = $fieldObj->getId();



        switch ($data['createFileWindow_fieldtype']) {
            case 'TEXTFIELD':
                $textfield = new FieldTextfield();
                $textfield->setFieldId($id);
                $textfield->setRegex($data['fieldTextfield_regularexpression']);
                $textfield->setDefaultvalue($data['fieldTextfield_standard']);
                $textfield->save();
                break;
            case 'CHECKBOX':
                // do nothing
                break;
            case 'NUMBER':
                break;
            case 'DATE':
                break;
            case 'TEXTAREA':
                break;
            case 'RADIOGROUP':
                break;
            case 'CHECKBOXGROUP':
                break;
            case 'COMBOBOX':
                break;
            case 'FILE':
                break;
        }







        $this->renderText('{success:true}');
        return sfView::NONE;
    }

}
