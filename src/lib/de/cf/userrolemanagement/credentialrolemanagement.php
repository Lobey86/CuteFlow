<?php
/**
 * Class builds some kind of tree for the role management
 *
 * @author Manuel Schï¿½fer
 */
class CredentialRolemanagement {

        private $records;
        private $moduleCounter;
        private $groupCounter;
        private $firstRun;
        private $rightCounter;

        /**
         *
         * @param Doctrine_Collection $data_in, records from database
         */
	public function __construct(Doctrine_Collection $data_in) {
            $this->records = $data_in;
            $this->moduleCounter = 0;
            $this->groupCounter = 0;
            $this->rightCounter = 0;
            $this->firstRun = true;
        }

        /**
         *
         * Function builds out of the data, a tree to display all tabs, groups and rights
         * to the extjs popwindow
         * 
         * @return array $result, resultset
         */
        public function buildTabpanel() {
            $result = array();
            $a=1;
            foreach($this->records as $item) {
                $module = '';
                $module = $this->checkModule($result,$item->getUserModule());
                if($module != '') {
                    $result[$this->moduleCounter]['usermodule']['title'] = $module;
                    $result[$this->moduleCounter]['usermodule']['id'] = 'usermodule_' . $module;
                    $result[$this->moduleCounter]['usermodule']['server_id'] = $module;
                    $result[$this->moduleCounter]['usermodule']['usermodule'] = $module;
                    $result[$this->moduleCounter]['usermodule']['usergroup'] = '';
                }

                $group = '';
                $group = $this->checkGroup($result[$this->moduleCounter],$item->getUserGroup());
                if($group != ''){
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['title'] = $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['id'] = $result[$this->moduleCounter]['usermodule']['id'] . '_usergroup_' . $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['icon'] = $result[$this->moduleCounter]['usermodule']['id'] . '_usergroupIcon_' . $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['server_id'] = $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['usergroupe'] = $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'] = '';
                }

               
                $right = $item->getUserRight();
                $id = $item->getId();
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['title'] = $right;
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['id'] =  $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['id'] . '_userright_' . $right;
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['server_id'] = $right;
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['userright'] = $right;
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['name'] = $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['id'];
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['parent'] = $this->checkParent($right);
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['parentelement'] = $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['id'];
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter++]['database_id'] = $id;
                

            }
            #print_r ($result);die;
            $result = $this->sortGroup($result);
            return $result;
        }


        /**
         * Function checks for equal group in the resultset and the current item
         * if a group is already in the resultset, nothing is done.
         * If group is not in resultset, tab is added.
         *
         * @param array $result, Resultset with the current data
         * @param string $item, value of the current item
         * @return string $item, retursn item or nothing.
         */
        private function checkGroup($result, $item) {
            $flag = false;
            if ($this->firstRun == false OR $this->groupCounter > 0) {
                foreach($result['usermodule']['usergroup'] as $group) {
                    if($group['server_id'] == $item) {
                        $flag = true;
                    }
                }
                if($flag == false) {
                    $this->groupCounter++;
                    $this->rightCounter = 0;
                    return $item;
                }
                
            }
            else {
                $this->firstRun = false;
                return $item;
            }
            
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
                    $this->groupCounter = 0;
                    $this->rightCounter = 0;
                    $this->firstRun = true;
                    return $item;
                }
                else {
                    return '';
                }
            }
            else {
                //$this->firstRun = false;
                return $item;
            }
        }

        /**
         * Checks for Parent item.
         * Parent item must be: showModule
         *
         * @param String $item
         * @return boolean, 1 for is Parent, 0 for non-parent
         */
        private function checkParent($item) {
            if($item == 'showModule') {
                return 1;
            }
            else {
                return 0;
            }
        }

        /**
         * Function sorts the builded tree, that module enabling is on top of an array.
         * Necessarry that in ExtJS frontend, the fat black module name is on top.
         *
         * @param array $data
         * @return array $data
         */
        private function sortGroup(array $data) {
            $store_showModule = array();
            $store_firtsElement = array();
            
            for($a=0;$a<count($data);$a++) {
                for($b=0;$b<count($data[$a]['usermodule']['usergroup']);$b++) {
                    for($c=0;$c<count($data[$a]['usermodule']['usergroup'][$b]['userright']);$c++) {
                        if($data[$a]['usermodule']['usergroup'][$b]['userright'][$c]['server_id'] == 'showModule') {
                            if($c > 0) {
                                $store_showModule = $data[$a]['usermodule']['usergroup'][$b]['userright'][$c];
                                $store_firtsElement = $data[$a]['usermodule']['usergroup'][$b]['userright'][0];
                                $data[$a]['usermodule']['usergroup'][$b]['userright'][$c] = $store_firtsElement;
                                $data[$a]['usermodule']['usergroup'][$b]['userright'][0] = $store_showModule;
                            }
                        }
                    }
                }
            }
            return $data;
        }
}