<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UserConfigurationTable extends Doctrine_Table {


    /** 
     * create new instance of UserConfiguration
     * @return object UserConfiguration
     */
    public static function instance() {
        return Doctrine::getTable('UserConfiguration');
    }

    /**
     * Loads User config
     * @return Doctrine_Collection
     */
    public function getUserConfiguration() {
        return Doctrine_Query::create()
                ->select('uc.*')
                ->from('UserConfiguration uc')
                ->execute();
    }

    /**
     * Updates User settings
     * @param array $data
     * @return true
     */
    public function updateUserConfiguration (array $data) {
        Doctrine_Query::create()
            ->update('UserConfiguration uc')
            ->set('uc.durationtype', '?', $data['userTab_defaultdurationtype'])
            ->set('uc.durationlength', '?', $data['userTab_defaultdurationlength'])
            ->set('uc.displayeditem', '?', $data['userTab_itemsperpage'])
            ->set('uc.refreshtime', '?', $data['userTab_refreshtime'])
            ->set('uc.markyellow', '?', $data['userTab_markyellow'])
            ->set('uc.markred', '?', $data['userTab_markred'])
            ->set('uc.markorange', '?', $data['userTab_markorange'])
            ->set('uc.language', '?', $data['userTab_language'])
            ->set('uc.password', '?', $data['userTab_defaultpassword'])
            ->set('uc.emailformat', '?', $data['userTab_emailformat'])
            ->set('uc.emailtype', '?', $data['userTab_emailtype'])
            ->set('uc.circulationdefaultsortcolumn', '?', $data['userTab_circulationdefaultsortcolumn'])
            ->set('uc.circulationdefaultsortdirection', '?', $data['userTab_circulationdefaultsortdirection'])
            ->set('uc.role_id', '?', $data['userTab_userrole'])
            ->where('uc.id = ?',1)
            ->execute();
        return true;
    }

    public function updateTheme($theme) {
        Doctrine_Query::create()
            ->update('UserConfiguration uc')
            ->set('uc.theme', '?', $theme)
            ->where('uc.id = ?',1)
            ->execute();
        return true;

    }
}