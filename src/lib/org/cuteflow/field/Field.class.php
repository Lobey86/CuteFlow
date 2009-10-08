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


}
?>