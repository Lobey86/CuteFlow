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


}
?>