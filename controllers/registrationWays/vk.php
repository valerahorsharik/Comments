<?php

namespace controllers\registrationWays;

use core\Database as DB;

class vk {

    /**
     *
     * Id of my VK application
     * 
     * @var int
     */
    private $client_id = 5904978;

    /**
     *
     * Secret code of my VK application
     * 
     * @var string
     */
    private $client_secret = 'U0JEQEOJj9pqA50gSnOa';

    /**
     *
     * Redirect URI 
     * 
     * @var string
     */
    private $redirect_uri = 'http://pretty-simple.mcdir.ru/auth/social/vk/';

    /**
     *
     * Contains user's data from VK api .
     * 
     * @var array
     */
    private $userData = [];

    /**
     *
     * Consist name of fields, which we wanna get from VK api.
     * 
     * @var array
     */
    private $necessaryData = ['email',
        'user_id', 'first_name', 'last_name', 'photo_100'];

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

    /**
     * 
     * Create an instance and call getAccessToken method
     * 
     * @param int $code Code for the request for user access token
     */
    public function __construct($code) {
        $this->getAccessToken($code);
    }

    /**
     * 
     * Get access token from VK.
     * 
     * @param int $code Contain temporary code for get access token
     */
    private function getAccessToken($code) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://oauth.vk.com/access_token?client_id={$this->client_id}&client_secret={$this->client_secret}&redirect_uri={$this->redirect_uri}&code={$code}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = json_decode(curl_exec($ch));
        curl_close($ch);
        $this->setUsersData($answer);
        $this->getUsersDataWithToken($answer->access_token);
    }

    /**
     * 
     * Get all necessary data from VK 
     * 
     * @param int $token Contain an access token from VK
     */
    private function getUsersDataWithToken($token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.vk.com/method/users.get?user_ids={$this->userData['user_id']}&fields=photo_100&access_token={$token}&v=5.62");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = json_decode(curl_exec($ch));
        curl_close($ch);
        $this->setUsersData($answer->response[0]);
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
    public function tryToLogin() {
        $this->checkUser();
        $this->login();
    }

    /**
     * Checking by social id if user already in our base
     * if not - checking by email from social
     * and if email already in base - just adding social id
     * otherwise - register him
     */
    private function checkUser() {
        $this->id = DB::run("SELECT id FROM users WHERE vk_id = ?", [$this->userData['user_id']])->fetchColumn();
        if ($this->id === FALSE || is_null($this->id)) {
            $this->email = DB::run("SELECT email FROM users WHERE email = ?", [$this->userData['email']])->fetchColumn();
            if ($this->email === FALSE || is_null($this->email)) {
                $this->registration();
            } else {
                $this->addSocial();
            }
        }
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
     * Add social ID to the user
     * 
     * @return void 
     */
    private function addSocial() {
        $stmt = DB::run("UPDATE users SET vk_id = ?", [$this->userData['user_id']]);
        $this->id = DB::lastInsertId();
    }

    /**
     * 
     * Register a new user
     * 
     * @return void
     */
    private function registration() {
        $stmt = DB::run("INSERT INTO users VALUES(NULL,?,?,?,?,?,NULL)", [
                    $this->userData['first_name'],
                    $this->userData['last_name'],
                    $this->userData['email'],
                    $this->userData['photo_100'],
                    $this->userData['user_id']]);
        $this->id = DB::lastInsertId();
    }

}
