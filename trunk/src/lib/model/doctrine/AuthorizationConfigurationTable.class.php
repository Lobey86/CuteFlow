<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AuthorizationConfigurationTable extends Doctrine_Table {


    /**
     * create new instance of AuthorizationConfiguration
     * @return object AuthorizationConfiguration
     */
    public static function instance() {
        return Doctrine::getTable('AuthorizationConfiguration');
    }



    /**
     * Load Workflowconfiguration
     * @param string $orderby, orderby if needed
     * @return Doctrine_Collection
     */
    public function getAuthorizationConfiguration() {
        return Doctrine_Query::create()
            ->select('ac.*')
            ->from('AuthorizationConfiguration ac')
            ->execute();
    }

    /**
     * Update table items for AuthorizationConfiguration
     * @param int $id, id of the row to update
     * @param string $column, column of the tablerow which is to update
     * @return true;
     */
    public function updateAuthorizationConfigurationById($id, $column) {
        Doctrine_Query::create()
            ->update('AuthorizationConfiguration ac')
            ->set('ac.'.$column,'?', 1)
            ->where ('ac.id = ?',$id)
            ->execute();
        return true;
    }

    /**
     * set all Columns to 0
     * @return true
     */
    public function setAuthorizationConfigurationToNull() {
        Doctrine_Query::create()
            ->update('AuthorizationConfiguration ac')
            ->set('ac.deleteworkflow','?', 0)
            ->set('ac.archiveworkflow','?', 0)
            ->set('ac.stopneworkflow','?', 0)
            ->set('ac.detailsworkflow','?', 0)
            ->execute();
        return true;
    }


}