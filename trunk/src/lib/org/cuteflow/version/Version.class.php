<?php

class Version {


    public function __construct() {
        
    }

    public static function getVersion() {
        $filepath = sfConfig::get('sf_app_dir') . '/config/version.yml';
        $array = sfYAML::Load($filepath);
        return $array['version'];
    }

}

?>