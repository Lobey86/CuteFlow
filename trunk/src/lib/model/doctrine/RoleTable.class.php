<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class RoleTable extends Doctrine_Table {

    /**
     * create new instance of RoleTable
     * @return object RoleTable
     */
    public static function instance() {
        return Doctrine::getTable('Role');
    }

    /**
     * Function loads all roles
     * @param id $noDeleteId, can be set, if a role will be deleted, then role is not loaded
     * @return Doctrine_Collection
     */
    public function getAllRole($noDeleteId = '') {
        $query = Doctrine_Query::create()
                    ->select('r.*')
                    ->from('Role r')
                    ->orderBy('r.id ASC')
                    ->where('r.deleted_at IS NULL');
        if($noDeleteId != '') {
            $query->andWhere('r.id != ?', $noDeleteId);
        }
        return $query->execute();
    }


   /**
    * Load all Roles with sum users in it
    *
    * @return Doctrine_Collection
    */
   public function getAllRoleWithUser() {
       return Doctrine_Query::create()
                ->select('r.*, count(ul.id) AS users')
                ->from('Role r')
                ->leftJoin('r.UserLogin ul')
                ->where ('r.deleted_at IS NULL')
                ->groupby('r.id')
                ->execute();
   }

   /**
    * Delete role
    * @param id $id, Role id
    * @return true
    */
   public function deleteRole($id) {
      Doctrine_Query::create()
            ->update('Role r')
            ->set('r.deleted_at','?', date('Y-m-d'))
            ->where('r.id = ?', $id)
            ->execute();
       return true;
   }

   /**
    * Function loads a single role
    * @param int $id , role id
    * @return Doctrine_Collection
    */
   public function getRoleById($id) {
       return Doctrine_Query::create()
            ->select('r.description')
            ->from('Role r')
            ->where('r.id = ?', $id)
            ->andWhere('r.deleted_at IS NULL')
            ->execute();
   }

   /**
    *
    * @param string $name, Rolename
    * @return Doctrine_Collection
    */
   public function getRoleByDescription($name) {
       return Doctrine_Query::create()
                ->from('Role r')
                ->where('r.description = ?', $name)
                ->execute();
   }

}