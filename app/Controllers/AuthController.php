<?php

namespace Controllers;

// use DateTime;
use Google_Client;
use Google_Service_Oauth2;
use Components\User;

class AuthController extends ControllerAbstract
{
    public function login()
    {
        //@TODO Verify 'state' parameter before proceeding.
        
        $client = new Google_Client();
        
        $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
        $client->authenticate($_GET['code']);
        
        $oauthService = new Google_Service_Oauth2($client);
        $userData = $oauthService->userinfo->get();
        
        //Sign-in link needed for the Sign-in button.
        // var_dump($client->createAuthUrl());
        
        // Get your access and refresh tokens, which are both contained in the
        // following response, which is in a JSON structure:
        
        $tokenData = $client->verifyIdToken()->getAttributes();
        // echo "accessToken: ".$client->getAccessToken();
        // var_dump($tokenData);
        $userTokenData = $tokenData['payload'];
        
        
        $userComponent = new User();
        $user = $userComponent->retrieveUserByGid($userTokenData['sub'], true, $userData);
        
        
        //@TODO Set cookies or session
        $_SESSION['user_gid'] = $user->getGid();
    }
    
    // $client = new Google_Client();
    //     $client->setApplicationName('Google+ server-side flow');
    //     $client->setClientId('963025972162-q0hu2uu1dllvl4u0gdk7elojq6dsvatq.apps.googleusercontent.com');
    //     $client->setClientSecret('FLnCE5TtyXZ8-ihHrA812W_E');
    //     $client->setRedirectUri('https://images-javenlalla.c9.io/www/index.php/login');
    //     $client->setDeveloperKey('AIzaSyB2ycI_i9ONqUHhlgjkcto38ni6f4tXSMU');
    //     $client->addScope("https://www.googleapis.com/auth/urlshortener");
    //     // $plus = new Google_PlusService($client);
    //     // var_dump($_GET['code']);
    //     // $service = new Google_Service_Urlshortener($client);
    //     // $url = new Google_Service_Urlshortener_Url();
    // //   $url->longUrl = $_GET['url'];
    // //   $short = $service->url->insert($url);
    // //   var_dump($short);
    //     $client->authenticate($_GET['code']);
    //       // Get your access and refresh tokens, which are both contained in the
    //       // following response, which is in a JSON structure:
    //       $userData = $client->verifyIdToken()->getAttributes();
    //       var_dump($userData['payload']['sub']);
    //     //   $jsonTokens = $client->getAccessToken();
    //     //   var_dump($jsonTokens);
        
    //     //@TODO Query user by `sub`
    //     //@TODO If no user found, create user
    //     //@TODO Set cookies
    
}