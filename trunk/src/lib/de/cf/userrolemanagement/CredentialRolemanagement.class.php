<?php
/**
 * Class builds some kind of tree for the role management
 *
 * @author Manuel Schï¿½fer
 */
class CredentialRolemanagement {

        public $records;
        public $moduleCounter;
        public $groupCounter;
        public $firstRun;
        public $rightCounter;
        public $context;

        /**
         *
         * @param Doctrine_Collection $data_in, records from database
         */
	public function __construct() {
            sfLoader::loadHelpers('I18N');
            $this->moduleCounter = 0;
            $this->groupCounter = 0;
            $this->rightCounter = 0;
            $this->firstRun = true;
        }

        public function setRecords(Doctrine_Collection $records_in) {
            $this->records = $records_in;
        }

        public function setContext(sfContext $context_in) {
            $this->context = $context_in;
        }
        
        /**
         *
         * Function builds out of the data, a tree to display all tabs, groups and rights
         * to the extjs popwindow
         *
         * @param array $credentials, array is set in editmode
         * 
         * @return array $result, resultset
         */
        public function buildTree(array $credentials = NULL) {
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
                    $result[$this->moduleCounter]['usermodule']['translation'] = $this->context->getI18N()->__($module ,null,'userrolemanagementpopup');
                    $result[$this->moduleCounter]['usermodule']['usergroup'] = '';
                }

                $group = '';
                $group = $this->checkGroup($result[$this->moduleCounter],$item->getUserGroup());
                if($group != ''){
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['title'] = $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['id'] = $result[$this->moduleCounter]['usermodule']['id'] . '_usergroup_' . $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['icon'] = 'usermanagement_' . $result[$this->moduleCounter]['usermodule']['id'] . '_usergroupIcon_' . $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['server_id'] = $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['usergroupe'] = $group;
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['translation'] = $this->context->getI18N()->__($group ,null,'userrolemanagementpopup');
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
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['translation'] = $this->context->getI18N()->__($right ,null,'userrolemanagementpopup');
                if ($credentials == NULL) {
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['checked'] = 0;
                }
                else {
                    $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter]['checked'] = $this->checkChecked($id, $credentials);
                }
                
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['userright'][$this->rightCounter++]['database_id'] = $id;
                

            }
            $result = $this->sortGroup($result);
            return $result;
        }



        /**
         *
         * Function checks when role is edited, if a checkbox is set or not
         * 
         * @param int $item, id of the item
         * @param array $credentials, credentials for the role.....
         * @return boolean, true if role is active, false if not.
         */
        public function checkChecked($item, array $credentials) {
            if(in_array($item, $credentials) == true) {
                return 1;
            }
            else {
                return 0;
            }
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
        public function checkGroup($result, $item) {
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
        public function checkModule($result, $item) {
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
        public function checkParent($item) {
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
        public function sortGroup(array $data) {
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


        /**
         *
         * Function builds an array out of the collection
         *
         * @param Doctrine_Collection $data
         * @return array $result, resultset
         */
        public function buildCredentials(Doctrine_Collection $data) {
            $result = array();
            foreach($data as $item) {

                $result[] = $item->getCredentialId();
            }
            return $result;
        }

}