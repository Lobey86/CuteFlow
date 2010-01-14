<?php

class JavaScriptCompressor {

    public $folders;
    public $files;

    public function __construct() {
        $this->getFolders();
        $this->files = $this->getFiles();
        $this->writeJavaScript();
    }


    public function writeJavaScript() {
        for($a=0;$a<count($this->files['template_uncompressed']);$a++) {
            $fileContent = file_get_contents($this->files['template_uncompressed'][$a]);
            $t1 = microtime(true);
            $packer = new JavaScriptPacker($fileContent, 'None', true, false);
            $packed = $packer->pack();
            $path = $this->files['template'][$a];
            $file = fopen($path,'w+');
            rewind($file);
            fwrite($file, $packed);
            fclose($file);
        }




        
    }




    public function getFolders() {
        $result = array();
        $filepath = sfConfig::get('sf_app_dir') . '/templates_uncompressed';
        $files = scandir($filepath);
        foreach($files as $folder) {
            if($folder != '.' AND $folder != '..' AND $folder != '.svn') {
                $result[] = $folder;
            }
        }
        $this->folders = $result;
    }



    public function getFiles() {
        $result = array();
        $a = 0;
        foreach($this->folders as $folder){
            $path = sfConfig::get('sf_app_dir') . '/templates_uncompressed/' . $folder;
            $files = $this->getFilesInDirectory($path);
            foreach($files as $file) {
                $result['template'][$a] = sfConfig::get('sf_app_dir') . '/templates/' .$folder.'/'.$file;
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
