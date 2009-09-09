<?php
/**
 * Class builds the Menue for RegionWest in Extjs
 */

class Menue extends MenueCredential {

    private $userright;
    
    public function __construct() {
        sfLoader::loadHelpers('I18N');
        $this->moduleCounter = 0;
        $this->groupCounter = 0;
        $this->firstRun = true;
    }

    
    public function setUserright($right_in) {
        $this->userright = $right_in;
    }


    /**
     *
     * Function overrides buildTree and returns array for menue on region west.
     *
     * @param array $credentials, is null
     * @return array $result, resultset
     */
    public function buildTree() {
        $result = array();
        foreach($this->records as $item) {
            $module = '';
            $module = $this->checkModule($result,$item->getUserModule());
            if($module != '') {
                $result[$this->moduleCounter]['usermodule']['title'] = $module;
                $result[$this->moduleCounter]['usermodule']['id'] = 'usermodule_' . $module;
                $result[$this->moduleCounter]['usermodule']['server_id'] = $module;
                $result[$this->moduleCounter]['usermodule']['usermodule'] = $module;
                $result[$this->moduleCounter]['usermodule']['icon'] = 'usermodule_' . $module . '_Icon';
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
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['disabled'] = $this->checkRight($result[$this->moduleCounter]['usermodule']['title'] . '_' . $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['title'] . '_showModule');
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['object'] = $result[$this->moduleCounter]['usermodule']['title'] .'_' .$group;
                $result[$this->moduleCounter]['usermodule']['usergroup'][$this->groupCounter]['translation'] = $this->context->getI18N()->__($group ,null,'userrolemanagementpopup');
            }
        }
        return $result;
     }

     /**
      *
      * @param String $item, The right
      * @return boolean, true or false, if right is set
      */
     private function checkRight($item) {
         return  $this->userright[$item];
     }




}
?>