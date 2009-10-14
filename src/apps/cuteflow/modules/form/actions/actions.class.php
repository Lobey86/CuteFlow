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
        $form = new Form();
        $data = FormTemplateTable::instance()->getAllFormTemplates();
        $json_result = $form->buildAllForms($data);
        $this->renderText('({"result":'.json_encode($json_result).'})');
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

    /**
     * Load single record
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeLoadSingleForm(sfWebRequest $request) {
        $form = new Form();
        $data = FormTemplateTable::instance()->getFormTemplateById($request->getParameter('id'));
        $json_result = $form->buildSingleForm($data);
        $this->renderText('({"result":'.json_encode($json_result).'})');
        return sfView::NONE;
    }

    /**
     * update record
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeUpdateForm(sfWebRequest $request) {
        $form = new Form();
        $data = $request->getPostParameters();
        $id = $request->getParameter('id');
        FormFieldTable::instance()->deleteFormFieldByTemplateId($id);
        FormSlotTable::instance()->deleteFormSlotByTemplateId($id);
        FormTemplateTable::instance()->updateNameById($id,$data['createFileWindow_fieldname']);
        $grid = $data['slot'];
        $form->saveForm($id, $grid);
        $this->renderText('{success:true}');
        return sfView::NONE;
    }

    /**
     * Create new record
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeSaveForm(sfWebRequest $request) {
        $form = new Form();
        $data = $request->getPostParameters();
        $formtemplate = new FormTemplate();
        $formtemplate->setName($data['createFileWindow_fieldname']);
        $formtemplate->save();
        $template_id = $formtemplate->getId();
        $grid = $data['slot'];
        $form->saveForm($template_id, $grid);
        $this->renderText('{success:true}');
        return sfView::NONE;
    }



    /**
     * Delete Form
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeDeleteForm(sfWebRequest $request) {
        FormTemplateTable::instance()->deleteFormTemplate($request->getParameter('id'));
        return sfView::NONE;
    }

}
