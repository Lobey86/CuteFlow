<?php
class FilterManagement {



    public function  __construct() {
        
    }


    public function getRunningStation(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['id'] = $item->getId();
            $user = UserLoginTable::instance()->findUserById($item->getUserId());
            $result[$a++]['name'] = $user[0]->getUsername();
        }
        return $result;
    }

}
?>
