<?php

namespace Components;

//@TODO:  Is this line needed? Verify if FileDownloader still operates correctly.
use Slim\Slim;

class ComponentAbstract
{
    protected $_app;
    
    public function __construct()
    {
        $this->_app =  Slim::getInstance();
    }
}