<?php
/**
 * Class enables to change the order of the menue items
 */

class MenueSetting extends MenueCredential {

    

    public function __construct() {
        sfLoader::loadHelpers('I18N');
    }


    
    public function buildModule(Doctrine_Collection $data) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $result[$a]['#'] = $a+1;
            $result[$a]['id'] = $item->getUsermodule();
            $result[$a]['group'] = $this->context->getI18N()->__($item->getUsermodule() ,null,'userrolemanagementpopup');
            $result[$a++]['module'] = $item->getUsermodule();
        }
       return $result;
    }

    public function buildGroup(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        
        foreach($data as $item) {
            $result[$a]['#'] = $a+1;
            $result[$a]['module_id'] = $item->getUsermodule();
            $result[$a]['module'] = $this->context->getI18N()->__($item->getUsermodule() ,null,'userrolemanagementpopup');
            $result[$a]['group_id'] = $item->getUsergroup();
            $result[$a++]['group'] = $this->context->getI18N()->__($item->getUsergroup() ,null,'userrolemanagementpopup');

        }

        return $result;
    }



    
}
?>