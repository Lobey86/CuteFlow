<?php
class SendMessage {


    public function  __construct() {
        
    }


    public function buildReceiver(Doctrine_Collection $data) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $result[$a]['name'] = $item->getFirstname() . ' ' . $item->getLastname();
            $result[$a++]['email'] = $item->getEmail();

        }

        return $result;
    }



}
?>