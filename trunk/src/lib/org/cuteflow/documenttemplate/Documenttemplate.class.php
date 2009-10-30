<?php


class Documenttemplate {

    public function  __construct() {
       sfLoader::loadHelpers('Date');
       sfLoader::loadHelpers('i18n');
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


    /**
     * Creates all Versions for the grid popup
     * @param Doctrine_Collection $data, data
     * @param string $culture, current culture of user
     * @param sfContext, $context
     * @return array $result, data
     */
    public function buildAllVersion(Doctrine_Collection $data, $culture, sfContext $context) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $template = $item->getDocumenttemplateTemplate();
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['activeversion'] = $item->getActiveversion() == 1 ? '<font color="green">' . $context->getI18N()->__('Yes' ,null,'documenttemplate') . '</font>' : '<font color="red">' . $context->getI18N()->__('No',null,'documenttemplate') . '</font>';
            $result[$a]['created_at'] = format_date($item->getCreatedAt(), 'g', $culture);
            $result[$a]['name'] = $template[0]->getName();
            $result[$a++]['documenttemplate_id'] = $item->getDocumenttemplateId();
        }
        return $result;
    }



}
?>