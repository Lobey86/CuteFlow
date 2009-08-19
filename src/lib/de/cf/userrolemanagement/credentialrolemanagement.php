<?php
/**
 * Class builds some kind of tree for the role management
 *
 * @author Manuel Sch�fer
 */
class CredentialRolemanagement {

        private $records;
        private $moduleCounter;
        private $groupCounter;
        private $firstRun;
        private $rightCounter;
    
	public function __construct(Doctrine_Collection $data_in) {
            $this->records = $data_in;
            $this->moduleCounter = 0;
            $this->groupCounter = 0;
            $this->rightCounter = 0;
            $this->firstRun = true;
        }

        public function buildTabpanel() {
            $result = array();
            $a=1;
            foreach($this->records as $item) {
                $module = '';
                $module = $this->checkModule($result,$item->getUsermodule());
                if($module != '') {
                    $result[$this->moduleCounter]['usermodule']['title'] = $module;
                    $result[$this->moduleCounter]['usermodule']['id'] = 'usergroup_' . $module;
                    $result[$this->moduleCounter]['usermodule']['server_id'] = $module;
                    $result[$this->moduleCounter]['usermodule']['usergroup'] = '';
                }
                //$a++;
            }
 

            print_r ($result);die;
            return $result;
        }

        /**
         * Function checks for equal tabs in the resultset and the current item
         * if a tab is already in the resultset, nothing is done.
         * If tab is not in resultset, tab is added.
         *
         * @param array $result, Resultset with the current data
         * @param string $item, value of the current item
         * @return string $item, retursn item or nothing.
         */
        private function checkModule($result, $item) {
            $flag = false;
            if ($this->firstRun == false OR $this->moduleCounter > 0) {
                foreach($result as $module) {
                    if($module['usermodule']['title'] == $item) {
                        $flag = true;
                    }
                }

                if($flag == false) {
                    $this->moduleCounter++;
                    return $item;
                }
                else {
                    return '';
                }
            }
            else {
                $this->firstRun = false;
                return $item;
            }
        }


        private function checkParent($item) {
            if($item == 'showModule') {
                return 1;
            }
            else {
                return 0;
            }
        }
}