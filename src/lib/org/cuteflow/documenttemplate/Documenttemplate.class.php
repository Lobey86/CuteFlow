<?php


class Documenttemplate {

    public function  __construct() {
       
    }


    public function buildAllDocumenttemplates(array $data) {
        $result = array();
        $a = 0;
        for($a=0;$a<count($data);$a++) {
            $data[$a]['#'] = $a+1;
        }
        return $data;
    }


    public function buildSingleDocumenttemplates(Doctrine_Collection $data, $id) {
        $item = $data[0];
        $slots = $item->getDocumenttemplateVersion()->toArray();

        $result['documenttemplate_id'] = $item->getId();
        $result['name'] = $item->getName();
        $result['id'] = $slots['id'];
        $result['documenttemplate_id'] = $slots['documenttemplate_id'];
        $result['slots'] = $this->buildSlots($id);
        return $result;
    }

    public function buildSlots($slot_id) {
        $slots = DocumenttemplateSlotTable::instance()->getSlotByDocumentTemplateId($slot_id);
        $result = array();
        $a = 0;
        foreach ($slots as $slot) {
            $result[$a]['slot_id'] = $slot->getId();
            $result[$a]['name'] = $slot->getName();
            $result[$a]['receiver'] = $slot->getSendtoallreceivers();
            $result[$a++]['fields'] = $this->buildFields($slot->getId());
            
        }
        return $result;
    }


    public function buildFields($slot_id) {
        
        $result = array();
        $a = 0;
        $fields = DocumenttemplateFieldTable::instance()->getAllFieldsBySlotId($slot_id);
        foreach($fields as $item) {
            $fieldname = $item->getField();
            $result[$a]['id'] = $item->getId();
            $result[$a]['title'] = $fieldname[0]->getTitle();
            $result[$a]['slot_id'] = $item->getDocumenttemplateslotId();
            $result[$a++]['field_id'] = $item->getFieldId();
        }
        return $result;
    }

}
?>