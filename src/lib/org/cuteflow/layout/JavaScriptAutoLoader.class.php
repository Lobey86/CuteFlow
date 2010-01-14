<?php

class JavaScriptAutoLoader {

    public $data;
    public $folders;
    public $files;

    public function __construct() {
        $filepath = sfConfig::get('sf_app_dir') . '/modules/layout/config/template.yml';
        $array = sfYAML::Load($filepath);
        $this->data = $this->getDisabled($array);
        $this->getFolders();
        $this->files = $this->getFiles();
    }

    public function getJavaScriptFiles() {
        return $this->files;
    }



    public function getDisabled(array $disabled) {
        $result = array();
        foreach($disabled['disabled'] as $item) {
            $result[] = $item;
        }
        return $result;
    }



    public function getFolders() {
        $result = array();
        $filepath = sfConfig::get('sf_app_dir') . '/templates';
        $files = scandir($filepath);
        foreach($files as $folder) {
            if($folder != '.' AND $folder != '..' AND $folder != '.svn' AND $this->checkDisabled($folder) == false AND $folder != 'layout.php' ) {
                $result[] = $folder;
            }
        }

        $this->folders = $result;
    }

    public function checkDisabled($folder) {
        if(in_array($folder,$this->data) === true) {
            return true;
        }
        else {
            return false;
        }
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

    public function getFiles() {
        $result = array();
        $a = 0;
        foreach($this->folders as $folder){
            $path = sfConfig::get('sf_app_dir') . '/templates/' . $folder;
            $files = $this->getFilesInDirectory($path);
            foreach($files as $file) {
                $result[$a++] = '<script type="text/javascript" src="/djs/'.$folder.'/'.$file.'"></script>';
            }
        }
        return $result;
    }

    public function writeFile(){
        $fileString = '';
        $path = sfConfig::get('sf_app_dir') . '/modules/layout/templates/_javaScriptFiles.php';
        $file = fopen($path,'r+');
        rewind($file);
        foreach($this->files as $singleFile) {
            $fileString .= $singleFile . "\n";
        }
        fwrite($file, $fileString);
        fclose($file);
    }



}

?>
