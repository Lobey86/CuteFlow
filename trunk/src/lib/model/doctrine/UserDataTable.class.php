<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UserDataTable extends Doctrine_Table {


    /**
     * create new instance of UserDataTable
     * @return object UserDataTable
     */
    public static function instance() {
        return Doctrine::getTable('UserData');
    }

    /**
     * Function loads full username 
     * @return Doctrine_Collection
     */
    public function getAllUserFullname() {
        return Doctrine_Query::create()
            ->select('ud.user_id, CONCAT(ud.firstname,\' \',ud.lastname) AS text')
            ->from('UserData ud')
            ->leftJoin('ud.UserLogin ul')
            ->where('ul.deleted = ?', 0)
            ->execute();
    }

    /**
     * update firstname and lastname of a user
     * @param array $data, post data
     * @param int $user_id, user_id
     * @return true
     */
    public function updateUserFirstnameAndLastname($data, $user_id) {
        Doctrine_Query::create()
               ->update('UserData ud')
               ->set('ud.firstname','?' ,$data['userFirstTab_firstname'])
               ->set('ud.lastname','?' ,$data['userFirstTab_lastname'])
               ->where('ud.user_id = ?', $user_id)
               ->execute();
        return true;
    }

    /**
     * Update additional Data for a user
     *
     * @param array $data POST data
     * @param int $user_id, user_id
     * @return true
     */
    public function updateUserAdditinalData($data, $user_id) {
        Doctrine_Query::create()
                ->update('UserData ud')
                ->set('ud.street','?',$data['userThirdTab_street'])
                ->set('ud.zip','?',$data['userThirdTab_zip'])
                ->set('ud.city','?',$data['userThirdTab_city'])
                ->set('ud.country','?',$data['userThirdTab_country'])
                ->set('ud.phone1','?',$data['userThirdTab_phone1'])
                ->set('ud.phone2','?',$data['userThirdTab_phone2'])
                ->set('ud.mobile','?',$data['userThirdTab_mobil'])
                ->set('ud.fax','?',$data['userThirdTab_fax'])
                ->set('ud.organisation','?',$data['userThirdTab_organisation'])
                ->set('ud.department','?',$data['userThirdTab_department'])
                ->set('ud.burdencenter','?',$data['userThirdTab_burdencenter'])
                ->set('ud.comment','?',$data['userThirdTab_comment'])
                ->where('ud.user_id = ?', $user_id)
                ->execute();
        return true;
    }



}