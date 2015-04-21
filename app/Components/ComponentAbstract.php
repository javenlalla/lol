<?php

namespace Components;

class ComponentAbstract
{
    protected $_app;
    
    public function __construct()
    {
        $this->_app =  Slim::getInstance();
    }
}