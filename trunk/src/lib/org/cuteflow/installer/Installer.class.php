<?php

class Installer {


    public function  __construct() {
        
    }


    public function createConfigFile(array $data) {
        $all = 'all:' . "\n";
        $doctrine = '  doctrine:' . "\n";
        $class = '    class: sfDoctrineDatabase' . "\n";
        $param = '    param:' . "\n";
        $dsn = '      dsn: '.$data['productive_database'].':host='.$data['productive_host'].';dbname='.$data['productive_databasename'] . "\n";
        $username = '      username: '.$data['productive_username'].'' . "\n";
        $password = '      password: '.$data['productive_password'].'' . "\n";

        $string = $all . $doctrine . $class . $param . $dsn . $username . $password;
        $file = sfConfig::get('sf_root_dir') . '/config/databases.yml';
        $fileHanlder = fopen($file,"w+");
        fwrite($fileHanlder, $string);
        fclose($fileHanlder);

        $file = sfConfig::get('sf_root_dir') . '/config/installed';
        $fileHanlder = fopen($file,"w+");
        fclose($fileHanlder);
        return true;
    }


    public static function getInstallerLanguage() {
        $file = sfConfig::get('sf_app_dir') . '/config/i18n.yml';
        $array = sfYAML::Load($file);
        $ymlCulture = $array['all']['default_culture'];
        sfLoader::loadHelpers('I18N');
        $result = array();
        $result = explode('_', $ymlCulture);
        return format_language($result[0]);
    }



    public static function getLanguage($language) {
        sfLoader::loadHelpers('I18N');
        $result = array();
        $result = explode('_', $language);
        return format_language($result[0]);
    }
}
?>