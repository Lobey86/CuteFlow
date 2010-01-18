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
        $filepath = sfConfig::get('sf_app_dir') . '/templates_uncompressed';
        $files = scandir($filepath);
        foreach($files as $folder) {
            if($folder != '.' AND $folder != '..' AND $folder != '.svn' AND $this->checkFolder($folder) == false) {
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
            $path = sfConfig::get('sf_app_dir') . '/templates_uncompressed/' . $folder;
            $files = $this->getFilesInDirectory($path);
            foreach($files as $file) {
                $result['template'][$a] = sfConfig::get('sf_app_dir') . '/templates/' .$folder.'/'.$file;
                $result['djs'][$a] = '/djs/' .$folder.'/'.$file;
                $result['template_uncompressed'][$a++]  = sfConfig::get('sf_app_dir') . '/templates_uncompressed/' .$folder.'/'.$file;
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
                        self::getFiles($directory.$resource.'/',$exempt,$files));
                else
                    $files[] = $resource;
            }
        }
        closedir($handle);
        return $files;
    }
    

    
}
?>