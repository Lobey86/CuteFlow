<?php

class Form {

    public function   __construct() {

    }


    /**
     * Builds datatree for a template, when in edit mode
     *
     * @param Doctrine_Collection $data
     * @param boolean $getSlotFields, is true by default, to get fields for each slot
     * @return <type>
     */
    public function buildSingleForm(Doctrine_Collection $data, $getSlotFields = true) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $slot = $item->getFormSlot();
            $result['title'] = $item->getName();
            $result['id'] = $item->getId();
            $result['slot'] = $this->buildSlot($slot,$getSlotFields); // add slots
        }
        return $result;
    }

    /**
     * Adds slots to a DocumentTemplate, is calles by buildSingleForm
     *
     * @param Doctrine_Collection $data, data
     * @param boolean $getSlotFields, is true by default, to get fields for each slot
     * @return array $result
     */
    private function buildSlot(Doctrine_Collection $data,$getSlotFields) {
        $result = array();
        $a = 0;

        foreach ($data as $item) {
          
            $field = FormFieldTable::instance()->getFormFieldBySlotId($item->getId());
            $result[$a]['id'] = $item->getId();
            $result[$a]['formtemplateid'] = $item->getFormtemplateId();
            $result[$a]['title'] = $item->getName();
            $result[$a]['receiver'] = $item->getSendtoallreceivers();
            if($getSlotFields == true) {
                $result[$a]['field'] = $this->buildField($field);
            }
            $a++;
        }
        return $result;
    }
    

    /**
     * Adds fields to a slot, is called by buildSlot
     *
     * @param Doctrine_Collection $data, data
     * @return array $result, Slots
     */
    private function buildField(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach ($data as $item) {
            $field = $item->getField();
            $result[$a]['id'] = $item->getId();
            $result[$a]['formslotid'] = $item->getFormslotId();
            $result[$a]['fieldid'] = $item->getFieldId();
            $result[$a++]['fieldname'] = $field->getTitle();
        }
        return $result;

    }

    /**
     * Builds forms for extjs grid output
     * 
     * @param Doctrine_Collection $data, data for output
     * @return array $result, formatted resultset
     */
    public function buildAllForms(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        
        foreach($data as $item) {
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['name'] = $item->getName();
            $result[$a++]['mountofslots'] = $item->getNumber();
            
        }
        return $result;
    }

    /**
     *
     * adds slots and fields to database
     *
     * @param int $id, id to add
     * @param array $data, post data to store
     * @return true
     */
    public function saveForm(array $data) {
        $formtemplate = new FormTemplate();
        $formtemplate->setName($data['createFileWindow_fieldname']);
        $formtemplate->setVersion(1);
        $formtemplate->setActiveversion(1);
        $formtemplate->save();
        $id = $formtemplate->getId();
        $grid = $data['slot'];
        $slotposition = 1;
        foreach($grid as $slot) {
            $fieldposition = 1;
            $slottemplate = new FormSlot();
            $slottemplate->setFormtemplateId($id);
            $slottemplate->setName($slot['title']);
            $slottemplate->setPosition($slotposition++);
            $slottemplate->setSendtoallreceivers($slot['receiver']);
            $slottemplate->save();
            $slot_id = $slottemplate->getId();
            $items = $slot['grid'];
            foreach($items as $gridrow) {
                $formfield = new FormField();
                $formfield->setFormslotId($slot_id);
                $formfield->setFieldId($gridrow['id']);
                $formfield->setPosition($fieldposition++);
                $formfield->save();
            }
        }
        return true;
    }

    /**
     * Update an docuemtn template
     *
     * @param int $id, id of the entry
     * @param array $data, post data
     * @return true
     */
    public function updateForm($id, array $data) {
        $formtemplate = Doctrine::getTable('FormTemplate')->find($id);
        $formtemplate->setName($data['createFileWindow_fieldname']);
        $formtemplate->save();
        $formtemplate_id = $formtemplate->getId();
        if($data['deletedSlots'] != '') {
            $deleted_slots = explode(',', $data['deletedSlots']);
            MailinglistUserTable::instance()->deleteUserBySlotIdInArray($deleted_slots);
            MailinglistSlotTable::instance()->deleteSlotByIdInArray($deleted_slots);
            FormFieldTable::instance()->deleteFieldBySlotIdInArray($deleted_slots);
            FormSlotTable::instance()->deleteSlotByArray($deleted_slots);

        }
        if($data['deletedFields'] != '') {
            $delted_fields = explode(',', $data['deletedFields']);
            FormFieldTable::instance()->deleteFieldByIdInArray($delted_fields);
        }

        $grid = $data['slot'];
        $slotposition = 1;
        foreach($grid as $slot) {
            $slottemplate = $slot['slot_id'] == '' ? new FormSlot() : Doctrine::getTable('FormSlot')->find($slot['slot_id']);
            $slottemplate->setFormtemplateId($id);
            $slottemplate->setName($slot['title']);
            $slottemplate->setPosition($slotposition++);
            $slottemplate->setSendtoallreceivers($slot['receiver']);
            $slottemplate->save();
            $slottemtplate_id = $slottemplate->getId();
            $gridItems = $slot['grid'];
            if($slot['slot_id'] == '') {
                $this->insertNewSlotToMailinglist($slotposition, $slottemtplate_id, $formtemplate_id);
            }
            $fieldposition = 1;
            foreach($gridItems as $gridRow) {
                $formfield = $gridRow['isNew'] == '' ? new FormField() : Doctrine::getTable('FormField')->find($gridRow['isNew']);
                $formfield->setFormslotId($slottemtplate_id);
                $formfield->setFieldId($gridRow['id']);
                $formfield->setPosition($fieldposition++);
                $formfield->save();
            }
        }
        return true;
    }

    /**
     *
     * @param int $position, new position of the record
     * @param int $slot_id, id of the slot
     * @param int $template_id, id of the templated which is currently used
     * @return true
     */
    private function insertNewSlotToMailinglist($position, $slot_id, $template_id) {
        $templates = MailinglistTemplateTable::instance()->getMailinglistByDocumentTemplateId($template_id)->toArray();
        foreach($templates as $item) {
            $slotobj = new MailinglistSlot();
            $slotobj->setPosition($position);
            $slotobj->setSlotId($slot_id);
            $slotobj->setMailinglisttemplateId($item['id']);
            $slotobj->save();
        }
        return true;
    }




}
?>