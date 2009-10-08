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
                $datefield->setDateformat($data['fieldDate_date']);
                $datefield->setDefaultvalue($data['fieldDate_format']);
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
                $records = $data['grid'];
                $position = 1;
                foreach($records as $item => $key) {
                    $radiogroup = new FieldRadiogroup();
                    $radiogroup->setValue($item);
                    $radiogroup->setIsactive($key);
                    $radiogroup->setFieldId($id);
                    $radiogroup->setPosition($position++);
                    $radiogroup->save();
                }
                break;
            case 'CHECKBOXGROUP':
                $records = $data['grid'];
                $position = 1;
                foreach($records as $item => $key) {
                    $checkboxgroup = new FieldCheckboxgroup();
                    $checkboxgroup->setValue($item);
                    $checkboxgroup->setIsactive($key);
                    $checkboxgroup->setFieldId($id);
                    $checkboxgroup->setPosition($position++);
                    $checkboxgroup->save();
                }
                break;
            case 'COMBOBOX':
                $records = $data['grid'];
                $position = 1;
                foreach($records as $item => $key) {
                    $combobox = new FieldCombobox();
                    $combobox->setValue($item);
                    $combobox->setIsactive($key);
                    $combobox->setFieldId($id);
                    $combobox->setPosition($position++);
                    $combobox->save();
                }
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



    public function executeLoadSingleField(sfWebRequest $request) {
        $fieldObject = new FieldClass();
        $data = FieldTable::instance()->getFieldById($request->getParameter('id'));
        switch ($data[0]->getType()) {
            case 'TEXTFIELD':
                $json_result = $fieldObject->buildTextfield($data);
                break;
            case 'CHECKBOX':
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


        $this->renderText('{"result":'.json_encode($json_result).'}');
        return sfView::NONE;
    }

}
