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

    /**
    * Load all data for grid
    *
    * @param sfRequest $request A request object
    */
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


    /**
     * Save fields
     * @param sfWebRequest $request
     * @return <type>
     */
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
                $numberfield = new FieldNumber();
                $numberfield->setRegex($data['fieldNumber_regularexpression']);
                $numberfield->setDefaultvalue($data['fieldNumber_standard']);
                $numberfield->setComboboxvalue($data['fieldNumber_regularexpressioncombo']);
                $numberfield->setFieldId($id);
                $numberfield->save();
                break;
            case 'DATE':
                $datefield = new FieldDate();
                $datefield->setRegex($data['fieldDate_regularexpression']);
                $datefield->setDateformat($data['fieldDate_format']);
                $datefield->setDefaultvalue($data['fieldDate_date']);
                $datefield->setFieldId($id);
                $datefield->save();
                break;
            case 'TEXTAREA':
                $data['fieldTextarea_content'] = $data['fieldTextarea_contenttype'] == 'plain' ? $data['fieldTextarea_textarea']: $data['fieldTextarea_htmlarea'];
                $textarea = new FieldTextarea();
                $textarea->setContent($data['fieldTextarea_content']);
                $textarea->setContenttype($data['fieldTextarea_contenttype']);
                $textarea->setFieldId($id);
                $textarea->save();
                break;
            case 'RADIOGROUP':
                $fieldClass->saveRadiogroup($id, $data);
                break;
            case 'CHECKBOXGROUP':
                $fieldClass->saveCheckboxgroup($id, $data);
                break;
            case 'COMBOBOX':
                $fieldClass->saveCombobox($id, $data);
                break;
            case 'FILE':
                $file = new FieldFile();
                $file->setRegex($data['fieldFile_regularexpression']);
                $file->setFieldId($id);
                $file->save();
                break;
        }
        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    /**
     * Load single Field
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadSingleField(sfWebRequest $request) {
        $fieldObject = new FieldClass();
        $data = FieldTable::instance()->getFieldById($request->getParameter('id'));
        switch ($data[0]->getType()) {
            case 'TEXTFIELD':
                $json_result = $fieldObject->buildTextfield($data);
                break;
            case 'CHECKBOX':
                $json_result = $fieldObject->buildCheckbox($data);
                break;
            case 'NUMBER':
                $json_result = $fieldObject->buildNumber($data);
                break;
            case 'DATE':
                $json_result = $fieldObject->buildDate($data);
                break;
            case 'TEXTAREA':
                $json_result = $fieldObject->buildTextarea($data);
                break;
            case 'RADIOGROUP':
                $json_result = $fieldObject->buildRadiogroup($data);
                break;
            case 'CHECKBOXGROUP':
                $json_result = $fieldObject->buildCheckboxgroup($data);
                break;
            case 'COMBOBOX':
                $json_result = $fieldObject->buildCombobox($data);
                break;
            case 'FILE':
                $json_result = $fieldObject->buildFile($data);
                break;
        }


        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }


    public function executeUpdateField(sfWebRequest $request) {
        $fieldType = FieldTable::instance()->getFieldById($request->getParameter('id'));
        $data = $request->getPostParameters();
        $fieldClass = new FieldClass();
        $data = $fieldClass->prepareSaveData($data);
        FieldTable::instance()->updateFieldById($request->getParameter('id'), $data);
        switch ($fieldType[0]->getType()) {
            case 'TEXTFIELD':
                FieldTextfieldTable::instance()->updateFieldTextfieldById($request->getParameter('id'), $data);
                break;
            case 'CHECKBOX':
                break;
            case 'NUMBER':
                FieldNumberTable::instance()->updateFieldNumberById($request->getParameter('id'), $data);
                break;
            case 'DATE':
                FieldDateTable::instance()->updateFieldDateById($request->getParameter('id'), $data);
                break;
            case 'TEXTAREA':
                $data['fieldTextarea_content'] = $data['fieldTextarea_contenttype'] == 'plain' ? $data['fieldTextarea_textarea']: $data['fieldTextarea_htmlarea'];
                FieldTextareaTable::instance()->updateFieldTextareaById($request->getParameter('id'), $data);
                break;
            case 'RADIOGROUP':
                FieldRadiogroupTable::instance()->deleteRadiogroupByFieldId($request->getParameter('id'));
                $fieldClass->saveRadiogroup($request->getParameter('id'), $data);
                break;
            case 'CHECKBOXGROUP':
                FieldCheckboxgroupTable::instance()->deleteCheckboxgroupByFieldId($request->getParameter('id'));
                $fieldClass->saveCheckboxgroup($request->getParameter('id'), $data);
                break;
            case 'COMBOBOX':
                FieldComboboxTable::instance()->deleteComboboxByFieldId($request->getParameter('id'));
                $fieldClass->saveCombobox($request->getParameter('id'), $data);
                break;
            case 'FILE':
                FieldFileTable::instance()->updateFieldFileById($request->getParameter('id'), $data);
                break;
        }

        return sfView::NONE;
    }

}
