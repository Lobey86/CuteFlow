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
    }
    


}
?>