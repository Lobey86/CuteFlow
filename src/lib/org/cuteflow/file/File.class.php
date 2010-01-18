<?php



class File {


    public function  __construct() {

    }


    public function getFileContent($filepath) {

        $fc = @file_get_contents($filepath);
        return $fc;
    }

    public function getContenttype($file) {
        $data = array();
        $data = explode('.', $file);
        $type =  strtolower($data[count($data)-1]);

    }

    
}
?>