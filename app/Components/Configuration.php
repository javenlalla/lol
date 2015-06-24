<?php

namespace Components;

class Configuration extends ComponentAbstract
{
    CONST CONFIG_FILE_EXTENSION = ".ini";
    
    CONST CONFIG_FILE_PATH_BASE = "../../config/";
    
    public function __construct()
    {
    }
    
    public function getEnvironment()
    {
        $env = getenv('SITE_ENVIRONMENT') ? getenv('SITE_ENVIRONMENT') : 'production';
        return strtolower($env);
    }
    
    public function getConfiguration()
    {
        $configFilename = $this->_getConfigFilename();
        
        $configPath = $this->_getConfigPath($configFilename);
        
        if(is_file($configPath)) {
            //@TODO: Implement better way of converting array to object.
            return json_decode(json_encode(parse_ini_file($configPath, true)));
        }
        
        throw new Exception("Unable to load file ".$configFilename." at location ".$configPath.".");
    }
    
    protected function _getConfigFilename()
    {
        $env = $this->getEnvironment();
        return $env.self::CONFIG_FILE_EXTENSION;
    }
    
    protected function _getConfigPath($filename)
    {
        $basePath = dirname(__FILE__)."/".self::CONFIG_FILE_PATH_BASE;
        return $basePath.$filename;
    }
}