<?php

namespace controllers;

use core\Controller as Controller;
use controllers\registrationWays\vk as vk;
use controllers\registrationWays\fb as fb;
use controllers\registrationWays\custom as custom;

class RegistrationController extends Controller {
   
    /**
     * Login/registration via vk
     * @see controllers\registrationWays\vk
     */
    public function vk() {
        $code = $_GET['code'];
        $vk = new vk($code);
        $vk->tryToLogin();
    }
    
    /**
     * Login/registration via fb
     * @see controllers\registrationWays\fb
     */
    public function fb() {
        $code = $_GET['code'];
        $fb = new fb($code);
        $fb->tryToLogin();
    }
    
    /**
     * Login/registration via google+
     * @see controllers\registrationWays\google
     */
    public function custom(){
        $google = new google();
    }
     
}
