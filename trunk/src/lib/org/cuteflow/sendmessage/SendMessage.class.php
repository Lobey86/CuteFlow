<?php
class SendMessage {


    public function  __construct() {
        
    }


    /**
     * Function builds receiver for sending emails
     *
     * @param Doctrine_Collection $data, data with all receivers
     * @return array $result, data with all receivers
     */
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