<?php

class JavaScriptLoader {

    public $folders;
    public $files;
    public $disabled;

    public function __construct() {
        $this->getDisabledFolders();
        $this->getFolders();
        $this->files = $this->getFiles();

    }


    public function getAllFiles() {
        return $this->files;
    }


    public function addNameSpaceFiles() {
        $result = array();
        $dir = sfConfig::get('sf_app_dir') . '/templates/namespace/';
        $result[0] = $dir . 'main.js';
        $a = 1;
        foreach($this->files['template'] as $item) {
            $result[$a++] = $item;
        }
        $this->files = $result;
    }


    public function getDisabledFolders() {
        $filepath = sfConfig::get('sf_app_dir') . '/config/template.yml';
        $array = sfYAML::Load($filepath);
        $a = 0;
        foreach($array['disabled'] as $module) {
            $this->disabled[$a++] = $module;
        }
    }





    public function getFolders() {
        $result = array();
        $filepath = sfConfig::get('sf_app_dir') . '/templates';
        $files = scandir($filepath);
        foreach($files as $folder) {
            if($folder != '.' AND $folder != '..' AND $folder != '.svn' AND $folder != 'layout.php' AND $this->checkFolder($folder) == false) {
                $result[] = $folder;
            }
        }
        $this->folders = $result;
    }




    public function checkFolder($folder) {
        if(in_array($folder, $this->disabled) == true) {
            return true;
        }
        else {
            return false;
        }
    }



    public function getFiles() {
        $result = array();
        $a = 0;
        foreach($this->folders as $folder){
            $path = sfConfig::get('sf_app_dir') . '/templates/' . $folder;
            $files = $this->getFilesInDirectory($path);
            foreach($files as $file) {
                $result['template'][$a] = sfConfig::get('sf_app_dir') . '/templates/' .$folder.'/'.$file;
                $result['djs'][$a++] = '/djs/' .$folder.'/'.$file;
            }
        }
        return $result;
        
    }

    public function getFilesInDirectory($directory,$exempt = array('.','..','.svn'),&$files = array()) {
        $handle = opendir($directory);
        while(false !== ($resource = readdir($handle))) {
            if(!in_array(strtolower($resource),$exempt)) {
                if(is_dir($directory.$resource.'/'))
                    array_merge($files,
                        self::getFilesInDirectory($directory.$resource.'/',$exempt,$files));
                else
                    $files[] = $resource;
            }
        }
        closedir($handle);
        return $files; 
    }
    

    
}
?>
