<?php

/**
 * documenttemplate actions.
 *
 * @package    cf
 * @subpackage documenttemplate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class documenttemplateActions extends sfActions {
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }


    /**
     * Load all Fields for second tab in popup window when creating /editing documenttemplate
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

    public function executeSaveDocumenttemplate(sfWebRequest $request) {
        $data = $request->getPostParameters();
        
        $docTemplate = new DocumenttemplateTemplate();
        $docTemplate->setName($data['documenttemplatePopUpFirstTab_fieldname']);
        $docTemplate->save();
        $template_id = $docTemplate->getId();
        $versionTemplate = new DocumenttemplateVersion();
        $versionTemplate->setDocumenttemplateId($template_id);
        $versionTemplate->setActiveversion(1);
        $versionTemplate->setVersion(1);
        $versionTemplate->save();
        $version_id = $versionTemplate->getId();

        $slots = $data['slot'];
        $slotPosition = 1;
        foreach($slots as $slot) {
            $slotTemplate = new DocumenttemplateSlot();
            $slotTemplate->setDocumenttemplateversionId($version_id);
            $slotTemplate->setName($slot['title']);
            $slotTemplate->setSendtoallreceivers($slot['receiver']);
            $slotTemplate->setPosition($slotPosition++);
            $slotTemplate->save();
            $slot_id = $slotTemplate->getId();
            $fields = $slot['grid'];
            $fieldPosition = 1;
            foreach ($fields as $field) {
                $fieldTemplate = new DocumenttemplateField();
                $fieldTemplate->setDocumenttemplateslotId($slot_id);
                $fieldTemplate->setFieldId($field['id']);
                $fieldTemplate->setPosition($fieldPosition++);
                $fieldTemplate->save();
            }
        }
        $this->renderText('{success:true}');
        return sfView::NONE;
    }


    public function executeLoadAllDocumenttemplates(sfWebRequest $request) {
        $anz = DocumenttemplateTemplateTable::instance()->getTotalSumOfDocumentTemplates();
        echo $anz[0]->getAnzahl();die;
        return sfView::NONE;
    }

}
