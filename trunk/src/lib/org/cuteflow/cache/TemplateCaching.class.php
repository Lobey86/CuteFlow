<?php

class TemplateCaching {

    public $files;

    public function setFiles() {
        $files = new JavaScriptLoader();
        $files->addNameSpaceFiles();
        $this->files = $files->getAllFiles();
    }

    public function checkCacheDir() {
        if(is_dir(sfConfig::get('sf_cache_dir')) == true) {
            if(is_dir(sfConfig::get('sf_cache_dir') . '/javaScriptCache') == false )  {
                mkdir(sfConfig::get('sf_cache_dir') . '/javaScriptCache');
            }
        }
        else {
            mkdir(sfConfig::get('sf_cache_dir'));
        }
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
        $dir = array_diff(scandir(sfConfig::get('sf_cache_dir') . '/javaScriptCache'), Array());
        if(!empty($dir)) {
            $lastIndex =  $dir[count($dir)-1];
        }
        else {
            return '';
        }
        if(substr_count($lastIndex, '.js') == 1) {
            return str_replace('.js', '', $lastIndex);
        }
        return '';
    }

    
    public function createCache($lastModified, $cacheStamp) {
        @unlink (sfConfig::get('sf_cache_dir') . '/javaScriptCache/' . $cacheStamp);
        $js = '';
        foreach($this->files as $file) {
            $jsMin = JSMin::minify(file_get_contents($file));
            $js .= $jsMin;
        }
        $dir = sfConfig::get('sf_cache_dir') . '/javaScriptCache/';
        file_put_contents($dir . $lastModified .'.js',$js);
        return true;
    }
}
function cmp ($a, $b) {
    return strcmp($a, $b);
}
?>
