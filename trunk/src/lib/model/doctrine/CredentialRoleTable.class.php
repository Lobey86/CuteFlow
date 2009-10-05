<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CredentialRoleTable extends Doctrine_Table {

    /**
     * create new instance of CredentialRoleTable
     * @return object CredentialRoleTable
     */
    public static function instance() {
        return Doctrine::getTable('CredentialRole');
    }


    /**
     * Function gets Credential Roles for an existing user by its ID
     * @param int $id, id of the user
     * @return Doctrine_Collection
     */
    public function getCredentialRoleById($id) {
        return Doctrine_Query::create()
                    ->select('cr.*')
                    ->from('CredentialRole cr, Role r, UserLogin ul')
                    ->where ('ul.role_id = r.id')
                    ->andWhere('r.id = cr.role_id')
                    ->andWhere('ul.id = ?', $id)
                    ->execute();
    }

}