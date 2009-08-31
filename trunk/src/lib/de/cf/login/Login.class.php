<?php
/**
 * class Handles Login functionality and loads userrights
 *
 */
class Login {

    public function __construct() {

    }


    /**
     *
     * This function loads from js/i18n/XXX/ext-lang-XX.js
     * the folder to load JS language files
     *
     * @param String $culture, the current Culture of the user.
     * @return String, default Language or culture if language exists
     */
    public static function buildExtjsLanguage($culture) {
        $folder = sfConfig::get('sf_web_dir') . '/js/i18n/' . $culture;
        if(is_dir($folder)) {
            return $culture;
        }
        else {
            return sfConfig::get('sf_default_culture');
        }

    }



}
?>