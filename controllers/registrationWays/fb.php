<?php


namespace controllers\registrationWays;

use core\Database as DB;

class fb {
    
    /**
     *
     * Id of my FB application
     * 
     * @var int
     */
    private $client_id = 401003196941968;

    /**
     *
     * Secret code of my FB application
     * 
     * @var string
     */
    private $client_secret = 'e2058041897f9e672936f77058beca46';

    /**
     *
     * Redirect URI 
     * 
     * @var string
     */
    private $redirect_uri = 'http://pretty-simple.mcdir.ru/auth/social/fb/';

    /**
     *
     * Contains user's data from FB api .
     * 
     * @var array
     */
    private $userData = [];

    /**
     *
     * Consist name of fields, which we wanna get from FB api.
     * 
     * @var array
     */
    private $necessaryData = ['email',
        'id', 'first_name', 'last_name', 'picture'];
    
    /**
     *
     * User id from DB.
     * 
     * @var int
     */
    private $id = null;
    
    /**
     *
     * User email from DB.
     * 
     * @var string
     */
    private $email = null;
    
    public function __construct($code) {
        $this->getAccessToken($code);
    }
    
    /**
     * 
     * Get access token from FB.
     * 
     * @param int $code Contain temporary code for get access token
     */
    private function getAccessToken($code) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v2.8/oauth/access_token?client_id={$this->client_id}&redirect_uri={$this->redirect_uri}&client_secret={$this->client_secret}&code={$code}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = json_decode(curl_exec($ch));
        curl_close($ch);
        $this->getUsersDataWithToken($answer->access_token);
    }
    
    /**
     * 
     * Get all necessary data from FB 
     * 
     * @param int $token Contain an access token from FB
     */
    private function getUsersDataWithToken($token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?access_token={$token}&fields=id,first_name,last_name,email,picture");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = json_decode(curl_exec($ch));
        curl_close($ch);
        $this->setUsersData($answer);
    }

    /**
     * 
     * Set user data in accordance with $this->necessaryData
     * 
     * @param \stdClass $data
     */
    private function setUsersData($data) {
        foreach ($this->necessaryData as $nameOfField) {
            if (isset($data->$nameOfField)) {
                if($nameOfField == "picture"){
                    $this->userData[$nameOfField] = $data->$nameOfField->data->url;
                    continue;
                }
                $this->userData[$nameOfField] = $data->$nameOfField;
            }
        }
    }

    /**
     * 
     * Check if the user already registered,
     * if yes - just login,
     * otherwise - register him and login
     * 
     * @return void
     */
    public function tryToLogin(){
        $this->id = DB::run("SELECT id FROM users WHERE fb_id = ?", [$this->userData['id']])->fetchColumn();
        if($this->id === FALSE || is_null($this->id)){
            $this->registration();
        } 
        $this->login();
    }
    
    /**
     * 
     * Set user's data in the session(login)
     * and redirect him to the main page.
     * 
     * @return void
     */
    private function login() {
        $_SESSION['user']['id'] = $this->id;
        $_SESSION['user']['name'] = $this->userData['first_name'];
        header('Location:/');
    }
    
    /**
     * 
     * Register a new user
     * 
     * @return void
     */
    private function registration() {
        $stmt = DB::run("INSERT INTO users VALUES(NULL,?,?,?,?,NULL,?)", [
                    $this->userData['first_name'],
                    $this->userData['last_name'],
                    $this->userData['email'],
                    $this->userData['picture'],
                    $this->userData['id']]);
        $this->id = DB::lastInsertId();
    }
}
