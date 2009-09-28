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
        }
        return $data;
    }

  
}
?>