<?php

namespace Components;

class FileDownloader extends ComponentAbstract
{
    const IMAGES_FOLDER = "i";
    
    const FILENAME_ALLOwED_CHARACTERS = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    
    const FILENAME_LENGTH = 6;
    
    public function __construct()
    {
        $this->_verifyPath();
    }
    
    public function download($url)
    {
        if(ini_get("allow_url_fopen") == 1) {
            $randomFilename = $this->_getRandomFilename();
            $downloadFileExtension = $this->_getFileExtension($url);
            
            $downloadFilename = $randomFilename . $downloadFileExtension;
            
            $downloadPath = self::IMAGES_FOLDER . "/" . $downloadFilename;
            
            if(copy($url, $downloadPath)) {
                return $downloadFilename;
            }
            
            return false;
        } else {
            throw new Exception("Setting 'allow_url_fopen' disabled.");
        }
    }
    
    /**
     * Retrieve file extension from target URL.
     * 
     * @param $url string Target URL to retrieve file extension from.
     * @return string File extension.
     */
    private function _getFileExtension($url)
    {
        $extSeparatorPostion = strrpos($url, ".");
        return substr($url, $extSeparatorPostion, strlen($url) - $extSeparatorPostion);
    }
    
    /**
     * Generate a random file name.
     * Logic derived from: http://stackoverflow.com/a/4356295
     * 
     * @return string Randomized file name.
     */
    private function _getRandomFilename()
    {
        $characters = self::FILENAME_ALLOwED_CHARACTERS;
        $charactersLength = strlen($characters);
        
        $randomFilename = '';
        for ($i = 0; $i < self::FILENAME_LENGTH; $i++) {
            $randomFilename .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomFilename;
    }
    
    /**
     * Ensure images directory is created.
     */
    private function _verifyPath()
    {
        if(!is_dir(self::IMAGES_FOLDER)) {
            if(!mkdir(self::IMAGES_FOLDER)) {
                throw new Exception("Unable to create images directory.");
            }
        }
    }
}