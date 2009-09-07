<?php
/**
 * Class that handles the language operation
 *
 * @author Manuel Schäfer
 */
class UserRolemanagement {

    public function __construct() {

    }

    /**
     * Function loads all Roles and builds output for ExtJS
     *
     * @param Doctrine_Collection $data
     * @param <type> $index
     * @return <type>
     */
    public function buildRole(Doctrine_Collection $data, $index) {

        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['#'] = $index++;
            $result[$a]['id'] = $item->getId();
            $result[$a]['deleteable'] = $item->getDeleteable();
            $result[$a]['editable'] = $item->getEditable();
            $result[$a]['users'] = $item->getUsers();
            $result[$a]['action'] = $this->buildRoleAction($item);
            $result[$a++]['description'] = $item->getDescription();
        }
        return $result;
    }

    /**
     *
     * Function checks if ROle is deletable and Editable
     *
     * @param <type> $item
     * @return <type>
     */
    private function buildRoleAction($item) {
        if($item->getEditable() == 0 AND $item->getDeleteable() == 0) {
            return 0;
        }
        else {
            return 1;
        }
    }


    /**
     *
     * Builds role for extjs combobox
     *
     * @param Doctrine_Collection $data, resultset
     * @return <type>
     */
    public function buildRoleCombobox(Doctrine_Collection $data) {

        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['value'] = $item->getId();
            $result[$a++]['text'] = $item->getDescription();
        }
        return $result;
    }    

}

?>