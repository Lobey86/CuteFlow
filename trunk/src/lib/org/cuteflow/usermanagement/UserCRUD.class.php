<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserCRUDclass
 *
 * @author Manu
 */
class UserCRUD {


    public function  __construct() {


    }
	
	public function prepareUpdateData(array $data) {
            if (isset($data['userThirdTab_street'])) {
                $data['userThirdTab_street'] = isset($data['userThirdTab_street']) ? $data['userThirdTab_street'] : '';
                $data['userThirdTab_zip'] = isset($data['userThirdTab_zip']) ? $data['userThirdTab_zip'] : '';
                $data['userThirdTab_city'] = isset($data['userThirdTab_city']) ? $data['userThirdTab_city'] : '';
                $data['userThirdTab_city'] = isset($data['userThirdTab_city']) ? $data['userThirdTab_city'] : '';
                $data['userThirdTab_country'] = isset($data['userThirdTab_country']) ? $data['userThirdTab_country'] : '';
                $data['userThirdTab_phone1'] = isset($data['userThirdTab_phone1']) ? $data['userThirdTab_phone1'] : '';
                $data['userThirdTab_phone2'] = isset($data['userThirdTab_phone2']) ? $data['userThirdTab_phone2'] : '';
                $data['userThirdTab_mobil'] = isset($data['userThirdTab_mobil']) ? $data['userThirdTab_mobil'] : '';
                $data['userThirdTab_fax'] = isset($data['userThirdTab_fax']) ? $data['userThirdTab_fax'] : '';
                $data['userThirdTab_organisation'] = isset($data['userThirdTab_organisation']) ? $data['userThirdTab_organisation'] : '';
                $data['userThirdTab_department'] = isset($data['userThirdTab_department']) ? $data['userThirdTab_department'] : '';
                $data['userThirdTab_burdencenter'] = isset($data['userThirdTab_burdencenter']) ? $data['userThirdTab_burdencenter'] : '';
                $data['userThirdTab_comment'] = isset($data['userThirdTab_comment']) ? $data['userThirdTab_comment'] : '';
            }
	    if(isset($data['userFourthTab_itemsperpage'])) {
                $data['userFourthTab_markyellow'] = $data['userFourthTab_markyellow'] == '' ? 7 : $data['userFourthTab_markyellow'];
                $data['userFourthTab_markorange'] = $data['userFourthTab_markorange'] == '' ? 9 : $data['userFourthTab_markorange'];
                $data['userFourthTab_markred'] = $data['userFourthTab_markred'] == '' ? 11 : $data['userFourthTab_markred'];
                $data['userFourthTab_showinpopup'] = isset($data['userFourthTab_showinpopup']) ? $data['userFourthTab_showinpopup'] : 0;
            }
            if (isset($data['userSecondTab_durationlength_type'])) {
                $data['userSecondTab_durationlength'] = $data['userSecondTab_durationlength'] == '' ? 5 : $data['userSecondTab_durationlength'];
            }
            return $data;
                
	}

    
   

    /**
     * Function updates data from tab: useragent Settings
     * @param array $data, POST data
     * @param int $user_id, userid
     * @return true
     */
    public function addUserAgent(array $data, $user_id) {
        $agents = array();
        if(isset($data['useragents'])) {
            $agents = $data['useragents'];
        }
        $position = 1;
        foreach($agents as $item) {
            $userAgent = new UserAgent();
            $userAgent->setUseragentId($item);
            $userAgent->setUserId($user_id);
            $userAgent->setPosition($position++);
            $userAgent->save();
        }
        return true;
    }

    /**
     * Create new user
     * 
     * @param array $data, POST data
     * @return int $id, current id of created user
     */
    public function saveLoginDataTab(array $data){
        $userObj = new UserLogin();
        $userObj->setUsername($data['userFirstTab_username']);
        $userObj->setEmail($data['userFirstTab_email']);
        $userObj->setRoleId($data['userFirstTab_userrole']);
        $userObj->setPassword($data['userFirstTab_password']);
        $userObj->save();
        $id = $userObj->getId();

        $userData = new UserData();
        $userData->setUserId($id);
        $userData->setFirstname($data['userFirstTab_firstname']);
        $userData->setLastname($data['userFirstTab_lastname']);
        $userData->save();

        $userSetting= new UserSetting();
        $userSetting->setUserId($id);
        $userSetting->setEmailformat($data['userFirstTab_emailformat']);
        $userSetting->setEmailtype($data['userFirstTab_emailtype']);
        $userSetting->save();
        return $id;

    }

    /**
     * Save changes when creating new user
     * 
     * @param array $data, POST data
     * @param int $user_id, user_id
     * @param array $defaultdata, default systemdata
     * @return true
     */
    public function saveGUISettingsTab(array $data, $user_id, array $defaultdata) {

        $data['userFourthTab_markyellow'] = isset($data['userFourthTab_markyellow']) ? $data['userFourthTab_markyellow'] : $defaultdata['markyellow'];
        $data['userFourthTab_markorange'] = isset($data['userFourthTab_markorange']) ? $data['userFourthTab_markorange'] : $defaultdata['markorange'];
        $data['userFourthTab_markred'] = isset($data['userFourthTab_markred']) ? $data['userFourthTab_markred'] : $defaultdata['markred'];


        $data['userFourthTab_markyellow'] = $data['userFourthTab_markyellow'] == '' ? $defaultdata['markyellow'] : $data['userFourthTab_markyellow'];
        $data['userFourthTab_markorange'] = $data['userFourthTab_markorange'] == '' ? $defaultdata['markorange'] : $data['userFourthTab_markorange'];
        $data['userFourthTab_markred'] = $data['userFourthTab_markred'] == '' ? $defaultdata['markred'] : $data['userFourthTab_markred'];
        $data['userFourthTab_showinpopup'] = isset($data['userFourthTab_showinpopup']) ? $data['userFourthTab_showinpopup'] : $defaultdata['showcirculationinpopup'];

        $data['userFourthTab_circulationdefaultsortcolumn'] = isset($data['userFourthTab_circulationdefaultsortcolumn']) ? $data['userFourthTab_circulationdefaultsortcolumn'] : $defaultdata['circulationdefaultsortcolumn'];
        $data['userFourthTab_circulationdefaultsortdirection'] = isset($data['userFourthTab_circulationdefaultsortdirection']) ? $data['userFourthTab_circulationdefaultsortdirection'] : $defaultdata['circulationdefaultsortdirection'];
        $data['userFourthTab_itemsperpage'] = isset($data['userFourthTab_itemsperpage']) ? $data['userFourthTab_itemsperpage'] : $defaultdata['displayeditem'];
        $data['userFourthTab_refreshtime'] = isset($data['userFourthTab_refreshtime']) ? $data['userFourthTab_refreshtime'] : $defaultdata['refreshtime'];



        $addData = Doctrine_Query::create()
           ->update('UserSetting us')
           ->set('us.markyellow','?',$data['userFourthTab_markyellow'])
           ->set('us.markred','?',$data['userFourthTab_markred'])
           ->set('us.markorange','?',$data['userFourthTab_markorange'])
           ->set('us.refreshtime','?',$data['userFourthTab_refreshtime'])
           ->set('us.displayeditem','?',$data['userFourthTab_itemsperpage'])
           ->set('us.circulationdefaultsortcolumn','?',$data['userFourthTab_circulationdefaultsortcolumn'])
           ->set('us.circulationdefaultsortdirection','?',$data['userFourthTab_circulationdefaultsortdirection'])
           ->set('us.showcirculationinpopup','?',$data['userFourthTab_showinpopup'])
           ->where('us.user_id = ?', $user_id)
           ->execute();
        
        return true;
    }

    /**
     *
     * @param array $data, POST data
     * @param int $user_id, user_id
     * @param array $defaultdata, default systemdata
     * @return true
     */
    public function saveUseragentSettings(array $data, $user_id, array $defaultdata) {
            $data['userSecondTab_durationlength'] = isset($data['userSecondTab_durationlength']) ? $data['userSecondTab_durationlength'] : $defaultdata['durationlength'];
            $data['userSecondTab_durationlength_type'] = isset($data['userSecondTab_durationlength_type']) ? $data['userSecondTab_durationlength_type'] : $defaultdata['durationtype'];
            $data['userSecondTab_durationlength'] = $data['userSecondTab_durationlength'] == '' ? $defaultdata['durationlength'] : $data['userSecondTab_durationlength'];
         
            $addData = Doctrine_Query::create()
               ->update('UserSetting us')
               ->set('us.durationtype','?',$data['userSecondTab_durationlength_type'])
               ->set('us.durationlength','?',$data['userSecondTab_durationlength'])
               ->where('us.user_id = ?', $user_id)
               ->execute();

            Doctrine_Query::create()
                ->delete('UserAgent')
                ->from('UserAgent ua')
                ->where('ua.user_id = ?',$user_id)
                ->execute();
            $agents = array();
            if(isset($data['useragents'])) {
                $agents = $data['useragents'];
            }
            $position = 1;
            foreach($agents as $item) {
                $userAgent = new UserAgent();
                $userAgent->setUseragentId($item);
                $userAgent->setUserId($user_id);
                $userAgent->setPosition($position++);
                $userAgent->save();
            }
        
        return true;
    }


    
    /**
     * Save worklfow settings for a user
     * @param array $data, POST Data
     * @param int $user_id, user id
     * @return true
     */
    public function saveWorklfowSettings(array $workflowData, $user_id, $position) {
		foreach($workflowData as $item => $key) {
            $workflow = new UserWorkflowConfiguration();
            $workflow->setUserId($user_id);
            $workflow->setColumntext($item);
            $workflow->setIsactive($key);
            $workflow->setPosition($position++);
            $workflow->save();
        }
        return true;
    }



    

}
?>
