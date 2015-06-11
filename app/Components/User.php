<?php

namespace Components;

use DateTime;

class User extends ComponentAbstract
{
    /**
     * Retrieve a user by Gid.
     * 
     * @param string $gid Gid value.
     * @param boolean $createIfNotFound If set to `true`, create the user record if retrieve attempt returned null.
     * @param array $userData Additional user data.
     */
    public function retrieveUserByGid($gid, $createIfNotFound = false, $userData = array())
    {
        $user = $this->_app->db->getRepository('Models\User')->findOneBy(array('gid' => $gid));
        if(empty($user)) {
            //@TODO: Add fallbacks for name if 'givenName' is empty.
            if(isset($userData['givenName']) && !empty($userData['givenName'])) {
                $name = $userData['givenName'];
            }
            
            //Create User
            $newUser = new \Models\User();
            
            $newUser->setName($name);
            $newUser->setGid($gid);
            $newUser->setToken('token');
            $newUser->setCreated(new DateTime());
            
            $this->_app->db->persist($newUser);
            $this->_app->db->flush();
            
            return $newUser;
        } else {
            return $user;
        }
    }
    
    public function getUser()
    {
        return $this->retrieveUserByGid($_SESSION['user_gid']);
    }
}