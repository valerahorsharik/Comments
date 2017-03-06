<?php

namespace controllers;

use core\Controller as Controller;

class UserController extends Controller {
    
       
    /**
     * 
     * Display login form.
     * 
     */
    public function login() {
        if(!self::isGuest()){
            header('Location:/');
        }
        $this->view->display('registration/login');
    }
    
    /**
     * 
     * Logout from account.
     * 
     */
    public function logout() {
        session_destroy();
        header('Location:/');
    }
    
    /**
     * 
     * Checking, if user is guest.
     * 
     * @return boolean
     */
    public static function isGuest(){
        if(isset($_SESSION['user'])){
            return false;
        }
        return true;
    }
}
