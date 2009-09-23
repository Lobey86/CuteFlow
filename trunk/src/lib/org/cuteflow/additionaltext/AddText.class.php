<?php
/**
 * Class that handles the additionaltext operation
 *
 * @author Manuel Schäfer
 */
class AddText {


    public function  __construct() {
        
    }

    /**
     * Function creates data for displaying all additional textes in datagrid
     * 
     * @param Doctrine_Collection $data, all records for grid
     * @return array $resultset, resultset.
     */
    public function buildAllText(Doctrine_Collection $data) {
        $a = 0;
        $result = array();
        foreach($data as $item) {
            $result[$a]['#'] = $a+1;
            $result[$a]['title'] = $item->getTitle();
            $result[$a]['contenttype'] = $item->getContenttype();
            $result[$a]['isactive'] = $item->getIsactive();
            $result[$a++]['id'] = $item->getId();
        }
        return $result;
    }


}
?>