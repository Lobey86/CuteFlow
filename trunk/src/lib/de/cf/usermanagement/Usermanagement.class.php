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
    public function buildSuperBoxUser(Doctrine_Collection $data) {
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['id'] = $item->getId();
            $result[$a++]['text'] = $item->getText();
        }
        return $result;

    }


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
            $useragentItem = $item->getUserAgent();
            $userrole = $item->getRole();
            $result['rolename'] = $userrole->getDescription();
            $result['useragent'] = $this->buildUserAgent($item->getUserAgent());
            $result['durationlength'] = $useragentItem[0]->getDurationlength();
            $result['durationtype'] = $useragentItem[0]->getDurationtype();
        }
        return $result;
    }

    /**
     *
     * function builds for one user, an all useragnet and returns a string. this is needed
     * for superselectbox in extjs gui
     *
     * @param Doctrine_Collection $useragent, data for useragents
     * @return string $result, returns an string that contains all useragents
     */
    public function buildUserAgent(Doctrine_Collection $useragent) {
        $string = '';

        $result = array();
        foreach ($useragent as $item) {
            $result[] = $item->getUseragentId();
        }
       
        if (count($result) == 1) {
            return $result[0];
        }
        elseif (count($result) > 1) {
            $string = $result[0];
            for($a=1;$a<count($result);$a++){
                $string = $string .','. $result[$a];
            }
            return $string;
        }
        else {
            return '';
        }
        
    }










}
?>