<?php

namespace Components;

class FileDownloader extends ComponentAbstract
{
    const IMAGES_FOLDER = "i";
    
    public function __construct()
    {
        $this->_verifyPath();
    }
    
    public function download($url)
    {
        // var_dump($url);
        // var_dump(ini_get("allow_url_fopen"));
        if(ini_get("allow_url_fopen") == 1) {
            $downloadPath = self::IMAGES_FOLDER . "/" . "tmp.jpg";
            
            if(copy($url, $downloadPath)) {
                return true;
            }
            
            return false;
        } else {
            throw new Exception("Setting 'allow_url_fopen' disabled.");
        }
    }
    
    //Ensure images directory is created.
    private function _verifyPath()
    {
        if(!is_dir(self::IMAGES_FOLDER)) {
            if(!mkdir(self::IMAGES_FOLDER)) {
                throw new Exception("Unable to create images directory.");
            }
        }
    }
}