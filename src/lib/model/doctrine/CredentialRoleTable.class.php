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

    /**
     * Delete CredentialROle
     * @param role to delete $id
     * @return true
     */
    public function deleteRoleById($id) {
        Doctrine_Query::create()
                ->update('CredentialRole cr')
                ->set('cr.deleted_at','?', date('Y-m-d'))
                ->where('role_id = ?', $id)
                ->execute();
        return true;
    }


    /**
     * Loads credential by its id
     * @param int $id
     * @return Doctrine_Collection
     */
    public function getCredentialById($id) {
        return Doctrine_Query::create()
                    ->select('cr.credential_id')
                    ->from('CredentialRole cr')
                    ->where('cr.role_id = ?',$id)
                    ->andWhere('cr.deleted_at IS NULL')
                    ->execute();
    }

    /**
     *
     * @param int $id, id of role to delete
     * @return true
     */
    public function deleteCredentialRole($id) {
        Doctrine_Query::create()
            ->delete('CredentialRole')
            ->from('CredentialRole cr')
            ->where('cr.role_id = ?',$id)
            ->execute();
        return true;
    }

}