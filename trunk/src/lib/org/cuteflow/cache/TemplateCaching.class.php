<?php

class TemplateCaching {

    public $files;

    public function setFiles() {
        $files = new JavaScriptLoader();
        $files->addNameSpaceFiles();
        $this->files = $files->getAllFiles();

    }


    public function getLastModifiedFile() {
        $files = new JavaScriptLoader();
        $files->addNameSpaceFiles();
        $filesArray = $files->getAllFiles();
        $mod = array();
        foreach($this->files as $file) {
            $mod[] =  filemtime($file);
        }
        uasort($mod, 'cmp');
        $lastModified = end($mod);
        return $lastModified;
    }


    public function getCurrentCacheStamp() {
        $dir = array_diff(scandir(sfConfig::get('sf_app_dir') . '/cache'), Array( ".", "..",".svn"));
        if(isset($dir[2])) {
         return str_replace('.js', '', $dir[2]);
        }
        return '';
    }

    
    public function createCache($lastModified, $cacheStamp) {
        @unlink (sfConfig::get('sf_app_dir') . '/cache/' . $cacheStamp);
        $js = '';
        foreach($this->files as $file) {
            $jsMin = JSMin::minify(file_get_contents($file));
            $js .= $jsMin;
        }
        $dir = sfConfig::get('sf_app_dir') . '/cache/';
        file_put_contents($dir . $lastModified .'.js',$js);
        return true;
    }
}
function cmp ($a, $b) {
    return strcmp($a, $b);
}
?>
