<?php
class FieldClass {



    public function  __construct() {
        sfLoader::loadHelpers('I18N');
    }


    /**
     * Prepare data for displaxing in grid
     *
     * @param Doctrine_Collection $data, data from database
     * @param sfContext $context
     * @return array $result
     */
    public function buildField(Doctrine_Collection $data, sfContext $context) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getId();
            $result[$a]['title'] = $item->getTitle();
            $result[$a]['type'] = $context->getI18N()->__($item->getType(),null,'field');
            $write = $item->getWriteprotected() == 1 ? 'yes' : 'no';
            $result[$a++]['writeprotected'] = $context->getI18N()->__($write,null,'field');
        }
        return $result;
    }


    public function prepareSaveData(array $data) {
        $data['createFileWindow_color'] = $data['createFileWindow_color'] == '' ? '#FFFFFF' : $data['createFileWindow_color'];
        $data['createFileWindow_writeprotected'] = isset($data['createFileWindow_writeprotected']) ? $data['createFileWindow_writeprotected'] : 0 ;
        return $data;
    }


    public function buildTextfield(Doctrine_Collection $data) {
        $result = array();
        foreach($data as $item) {
            $textfield = $item->getFieldTextfield();
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['regex'] = $textfield[0]->getRegex();
            $result['defaultvalue'] = $textfield[0]->getDefaultvalue();
        }
        return $result;
    }

    public function buildCheckbox(Doctrine_Collection $data){
        $result = array();
        foreach($data as $item) {
            $textfield = $item->getFieldTextfield();
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
        }
        return $result;
    }

    public function buildNumber(Doctrine_Collection $data) {
        $result = array();
        foreach($data as $item) {
            $number = $item->getFieldNumber();
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['defaultvalue'] = $number[0]->getDefaultvalue();
            $result['regex'] = $number[0]->getRegex();
            $result['comboboxvalue'] = $number[0]->getComboboxvalue();
        }
        return $result;
    }

    public function buildDate(Doctrine_Collection $data) {
        $result = array();
        foreach($data as $item) {
            $date = $item->getFieldDate();
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['defaultvalue'] = $date[0]->getDefaultvalue();
            $result['regex'] = $date[0]->getRegex();
            $result['dateformat'] = $date[0]->getDateformat();
        }
        return $result;
    }


    public function buildTextarea(Doctrine_Collection $data) {
        $result = array();
        foreach($data as $item) {
            $textarea = $item->getFieldTextarea();
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['content'] = $textarea[0]->getContent();
            $result['contenttype'] = $textarea[0]->getContenttype();
        }
        return $result;
    }

    public function buildRadiogroup(Doctrine_Collection $data) {
        $radiogroup = FieldRadiogroupTable::instance()->findRadiogroupByFieldId($data[0]->getId());
        $result = array();
        foreach($data as $item) {
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['items'] = $this->getItems($radiogroup);
        }
        return $result;
    }

    
    public function buildCheckboxgroup(Doctrine_Collection $data) {
        $checkboxgroup = FieldCheckboxgroupTable::instance()->findCheckboxgroupByFieldId($data[0]->getId());
        $result = array();
        foreach($data as $item) {
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['items'] = $this->getItems($checkboxgroup);
        }
        return $result;
    }

    public function buildCombobox(Doctrine_Collection $data) {
        $checkboxgroup = FieldComboboxTable::instance()->findComboboxByFieldId($data[0]->getId());
        $result = array();
        foreach($data as $item) {
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['items'] = $this->getItems($checkboxgroup);
        }
        return $result;
    }

    public function buildFile(Doctrine_Collection $data) {
        $result = array();
        foreach($data as $item) {
            $file = $item->getFieldFile();
            $result['id'] = $item->getId();
            $result['title'] = $item->getTitle();
            $result['type'] = $item->getType();
            $result['writeprotected'] = $item->getWriteprotected();
            $result['color'] = $item->getColor();
            $result['regex'] = $file[0]->getRegex();
        }
        return $result;
    }

    public function getItems(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['id'] = $item->getId();
            $result[$a]['field_id'] = $item->getFieldId();
            $result[$a]['value'] = $item->getValue();
            $result[$a++]['isactive'] = $item->getIsactive();
        }
        return $result;
    }


    public function saveRadiogroup($id, $data) {
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
    }


    public function saveCheckboxgroup($id, $data) {
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
    }


    public function saveCombobox($id, $data) {
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
    }


}
?>