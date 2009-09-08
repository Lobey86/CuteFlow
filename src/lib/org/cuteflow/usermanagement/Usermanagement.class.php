<?php
/**
 * Class that handles the language operation
 *
 * @author Manuel Schäfer
 */
class Usermanagement {

    public function __construct() {

    }

    /**
     *
     * Function creates resultset for ExtJS Grid
     *
     * @param Doctrine_Collection $data, results
     * @param int $index, Index for Paging
     * @return array $result, resultset
     */
    public function buildUser(Doctrine_Collection $data, $index) {
        $result = array();
        $a = 0;

        foreach($data as $item) {
            $role = $item->getRole();
            $result[$a]['id'] = $item->getId();
            $result[$a]['#'] = $index++;
            $result[$a]['firstname'] = $item->getFirstname();
            $result[$a]['lastname'] = $item->getLastname();
            $result[$a]['email'] = $item->getEmail();
            $result[$a]['username'] = $item->getUsername();
            $result[$a]['role_id'] = $role->getId();
            $result[$a]['role_description'] = $role->getDescription();
            $result[$a++]['action'] = $item->getId();
        }
        return $result;
    }

    /**
     * Function builds Role for Extjs
     *
     * @param Doctrine_Collection $data
     * @param int $index, index for counter
     * @return array $result, resultset
     */
    public function buildRole(Doctrine_Collection $data, $index) {

        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['#'] = $index++;
            $result[$a]['id'] = $item->getId();
            $result[$a++]['description'] = $item->getDescription();
        }
        return $result;
    }

    /**
     *  Builds data for the superselectbox in edit / new User
     *
     * @param Doctrine_Collection $data, data
     * @return array $result
     */
    public function buildUserGrid(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['id'] = $item->getId();
            $result[$a]['unique_id'] = $a;
            $result[$a++]['text'] = $item->getText();
        }
        return $result;

    }


    /**
     * Function loads useragents when in editmode.
     *
     * @param Doctrine_Collection $data, data with all useragents
     * @return array $result, resultset for the grid
     * @todo, problem mit useragent_id, query nur beispielhaft.
     */
    public function builUserAgentGrid(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $username = Doctrine_Query::create()
                ->select('CONCAT(u.firstname,\' \',u.lastname) AS text')
                ->from('User u')
                ->where('u.id = ?', $item->getUseragentId())
                ->execute();

            $user = $item->getUser();
            $result[$a]['unique_id'] = $a;
            $result[$a]['user_id'] = $item->getUseragentId();
            $result[$a++]['text'] = $username[0]->getText();
        }
        return $result;
    }


    /**
    * Loads all data for a user, when editing user
    *
    * @param Doctrine_Collection $data, data for a single user
    * @return array $result, returns an resultset with items
    */
    public function buildSingleUser(Doctrine_Collection $data) {
        $result = array();

        foreach($data as $item) {
            $result['id'] = $item->getId();
            $result['username'] = $item->getUsername();
            $result['firstname'] = $item->getFirstname();
            $result['lastname'] = $item->getLastname();
            $result['email'] = $item->getEmail();
            $result['password'] = $item->getPassword();
            $result['role_id'] = $item->getRoleId();
            $result['street'] = $item->getStreet();
            $result['zip'] = $item->getZip();
            $result['city'] = $item->getCity();
            $result['country'] = $item->getCountry();
            $result['phone1'] = $item->getPhone1();
            $result['phone2'] = $item->getPhone2();
            $result['mobil'] = $item->getMobile();
            $result['fax'] = $item->getFax();
            $result['organisation'] = $item->getOrganisation();
            $result['department'] = $item->getDepartment();
            $result['burdencenter'] = $item->getBurdencenter();
            $result['comment'] = $item->getComment();
            $userrole = $item->getRole();
            $result['rolename'] = $userrole->getDescription();
            $result['durationlength'] = $item->getDurationlength();
            $result['durationtype'] = $item->getDurationtype();
        }
        return $result;
    }


}
?>