<?php
/**
 * Class that handles the language operation
 *
 * @author Manuel Schäfer
 */
class Usermanagement {

        public function __construct() {

	}


        public function buildUser(Doctrine_Collection $data, $index) {
            $result = array();
            $a = 0;

            foreach($data as $item) {
                $role = $item->getRole();
                $result[$a]['id'] = $item->getId();
                $result[$a]['#'] = $index++;
                $result[$a]['firstname'] = $item->getFirstname();
                $result[$a]['lastname'] = $item->getLastname();
                $result[$a]['email'] = $item->getEmail();
                $result[$a]['username'] = $item->getUsername();
                $result[$a]['role_id'] = $role->getId();
                $result[$a]['role_description'] = $role->getDescription();
                $result[$a++]['action'] = $item->getId();
            }
            return $result;
        }

    public function buildRole(Doctrine_Collection $data, $index) {

        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['#'] = $index++;
            $result[$a]['id'] = $item->getId();
            $result[$a++]['description'] = $item->getDescription();
        }
        return $result;
    }


}
?>