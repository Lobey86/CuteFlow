<?php
/**
 * Class that handles the language operation
 *
 * @author Manuel Schäfer
 */
class Usermanagement {

    public function __construct() {

    }

    /**
     *
     * Function creates resultset for ExtJS Grid
     *
     * @param Doctrine_Collection $data, results
     * @param int $index, Index for Paging
     * @return array $result, resultset
     */
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

    /**
     * Function builds Role for Extjs
     *
     * @param Doctrine_Collection $data
     * @param int $index, index for counter
     * @return array $result, resultset
     */
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


    public function buildSuperBoxUser(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['id'] = $item->getId();
            $result[$a++]['text'] = $item->getText();
        }
        return $result;

    }


}
?>