<?php

namespace Controllers;

use Slim\Slim;

class ControllerAbstract
{
    protected $_app;
    
    public function __construct()
    {
        $this->_app = Slim::getInstance();
    }
    
    protected function _getRequestParams()
    {
        return json_decode($this->_app->request->getBody());
    }
    
    protected function _respond($data = array(), $code = 200, $message = null)
    {
        $response = $this->_app->response;
        
        if($code !== 200) {
            $response->setStatus($code);
        }
        
        if(!empty($message)) {
            echo json_encode( array(
                'code'      => $code,
                'message'   => $message,
                'data'      => $data
            ));
            return;
        }
        
        echo json_encode($data);
        return;
    }

}