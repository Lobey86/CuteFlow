<?php
/**
 * Class that handles the system settings operation
 *
 * @author Manuel Schäfer
 */
class SystemSetting {


    public function __construct() {
        sfLoader::loadHelpers('I18N');
    }

    /**
     * Function builds the data for the Extjs Grid, to change the order
     * of circulation overview Columns.
     *
     * @param array $data
     * @param sfContext, Context symfony object
     * @return array $data, resultset
     */
    public function buildColumns(array $data, sfContext $context) {
        for($a = 0;$a<count($data);$a++) {
            $data[$a]['columntext'] = $context->getI18N()->__($data[$a]['columntext'],null,'systemsetting');
            $data[$a]['column'] = $data[$a]['columntext'];
        }
        return $data;
    }


    /**
     * Cleans data updating the system settings
     *
     * @param array $data
     * @return array $data
     */
    public function buildSystemSetting(array $data) {
        $data['systemsetting_showposition'] = isset($data['systemsetting_showposition']) ? $data['systemsetting_showposition'] : 0;
        $data['systemsetting_allowunencryptedrequest'] = isset($data['systemsetting_allowunencryptedrequest']) ? $data['systemsetting_allowunencryptedrequest'] : 0;
        $data['systemsetting_sendreceivermail'] = isset($data['systemsetting_sendreceivermail']) ? $data['systemsetting_sendreceivermail'] : 0;
        $data['systemsetting_sendremindermail'] = isset($data['systemsetting_sendremindermail']) ? $data['systemsetting_sendremindermail'] : 0;
        return $data;
    }

    /**
     * Cleans data for updating email settings
     * @param array $data
     * @return array $data
     */
    public function buildEmailSetting(array $data) {
        $data['emailtab_encryption'] = $data['emailtab_encryption'] == 'NONE' ? '' : $data['emailtab_encryption'];
        $data['email_smtp_auth'] = isset($data['email_smtp_auth']) ? 1 : 0;
        return $data;
    }


    /**
     * Cleans data for updating user settings
     * @param array $data
     * @return array $data
     */
    public function buildUserSetting(array $data) {
        $data['userTab_markred'] = $data['userTab_markred'] == '' ? 12 : $data['userTab_markred'];
        $data['userTab_markyellow'] = $data['userTab_markyellow'] == '' ? 7 : $data['userTab_markyellow'];
        $data['userTab_markorange'] = $data['userTab_markorange'] == '' ? 10 : $data['userTab_markorange'];
        $data['userTab_defaultdurationlength'] = $data['userTab_defaultdurationlength'] == '' ? 3 : $data['userTab_defaultdurationlength'];
        $data['userTab_showinpopup'] = isset($data['userTab_showinpopup']) ? $data['userTab_showinpopup'] : 0;
        return $data;
    }


    /**
     * Loads firstlogin flag
     *
     * @return bool true/false
     */
    public static function getFirstLogin() {
        $result = AuthenticationConfigurationTable::instance()->getFirstLogin()->toArray();
        return $result[0]['firstlogin'];
    }

    /**
     * Function builds the data for the Extjs Grid, to change the order
     * of circulation overview Columns.
     *
     * @param array $data
     * @param sfContext, Context symfony object
     * @return array $data, resultset
     */
    public function buildAuthorizationColumns(array $data, sfContext $context) {
        
        $result = array();
        $a = 0;
        foreach($data as $item) {
            $result[$a]['type'] = $context->getI18N()->__($item['type'],null,'systemsetting');
            $result[$a]['raw_type'] = $item['type'];
            $result[$a]['id'] = $item['id'];
            $result[$a]['deleteworkflow'] = $item['deleteworkflow'];
            $result[$a]['archiveworkflow'] = $item['archiveworkflow'];
            $result[$a]['stopneworkflow'] = $item['stopneworkflow'];
            $result[$a++]['detailsworkflow'] = $item['detailsworkflow'];
        }
        return $result;
    }
  
}
?>