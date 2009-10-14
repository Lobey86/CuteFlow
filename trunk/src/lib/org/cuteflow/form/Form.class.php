<?php

class Form {

    public function   __construct() {

    }


    public function buildSingleForm(Doctrine_Collection $data) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $slot = $item->getFormSlot();
            $result['title'] = $item->getName();
            $result['id'] = $item->getId();
            $result['slot'] = $this->buildSlot($slot);
        }
        return $result;
    }


    private function buildSlot(Doctrine_Collection $data) {
        $result = array();
        $a = 0;

        foreach ($data as $item) {
          
            $field = FormFieldTable::instance()->getFormFieldBySlotId($item->getId());
            $result[$a]['id'] = $item->getId();
            $result[$a]['formtemplateid'] = $item->getFormtemplateId();
            $result[$a]['title'] = $item->getName();
            $result[$a]['receiver'] = $item->getSendtoallreceivers();
            $result[$a++]['field'] = $this->buildField($field);
            
        }
        return $result;
    }
    
    
    private function buildField(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach ($data as $item) {
            $field = $item->getField();
            $result[$a]['id'] = $item->getId();
            $result[$a]['formtemplateid'] = $item->getFormslotId();
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
     * @param array $grid, post data to store
     * @return true
     */
    public function saveForm($id, array $grid) {
        foreach($grid as $slot) {
            $position = 1;
            $slottemplate = new FormSlot();
            $slottemplate->setFormtemplateId($id);
            $slottemplate->setName($slot['title']);
            $slottemplate->setSendtoallreceivers($slot['receiver']);
            $slottemplate->save();
            $slot_id = $slottemplate->getId();
            $items = $slot['grid'];
            foreach($items as $gridrow) {
                $formfield = new FormField();
                $formfield->setFormslotId($slot_id);
                $formfield->setFieldId($gridrow);
                $formfield->setFormtemplateId($id);
                $formfield->setPosition($position++);
                $formfield->save();
            }
        }
        return true;
    }




}
?>