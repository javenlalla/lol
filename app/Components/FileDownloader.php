<?php

namespace Components;

class FileDownloader extends ComponentAbstract
{
    public function __construct()
    {
        
    }
    
    public function download($url)
    {
        // var_dump($url);
        // var_dump(ini_get("allow_url_fopen"));
        if(ini_get("allow_url_fopen") == 1) {
            // if(copy($url, 'tmp.jpg') {
            //     return true;
            // }
            
            return false;
        }
    }
}