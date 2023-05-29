<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\User;

use CODOF\Util;

class Login
{

    //put your code here

    public $identifier;
    public $password;
    private $db;

    public function __construct($db = false)
    {
        $this->db = $db;
    }

    /**
     *
     * Checks if username and password is not empty
     * Checks if user exists and password matches
     * Logs the user in
     * remember_me() is called
     *
     * @return array
     */
    public function process_login()
    {

        //don't neeed much validation since we use prepared queries    
        $identifier = strip_tags(trim($this->identifier));

        $hasher = new \CODOF\Pass(8, false);
        $password = $this->password;

        $errors = array();

        if (strlen($identifier) == 0) {
            $errors["msg"] = _t("username/email field cannot be left empty");
        }

        if (strlen($password) == 0) {
            $errors["msg"] = _t("password field cannot be left empty");
        }

        if (strlen($password) < 72 && empty($errors)) {

            $user = $this->getUserByIdentifier($identifier);
            $ip = $_SERVER['REMOTE_ADDR']; //cannot be trusted at all ;)
            $ban = new Ban($this->db);

            if ($user && $ban->is_banned(array($ip, $user->username, $user->mail))) {

                $ban_len = '';

                if ($ban->expires > 0) {

                    $ban_len = _t("until ") . date('d-m-Y h:m:s', $ban->expires);
                }

                return array("msg" => _t("You have been banned ") . $ban_len);
            }

            if ($user && $hasher->CheckPassword($password, $user->pass)) {

                User::login($user->id);
                $user = User::get();
                $user->rememberMe();
                setcookie("user_id", $user->id);
                return array("msg" => "success", "uid" => $user->id, "rid" => $user->rid,
                    "role" => User::getRoleName($user->rid), "redirect" => User::getLoginSuccessRedirectUrl());
            } else {

                \CODOF\Log::info('Failed login attempt for ' . $identifier . '. Wrong credentials');
                return array("msg" => _t("Wrong username/email or password"));
            }
        } else {
            return $errors;
        }
    }

    private function getUserByIdentifier($identifier)
    {
        $loginBy = Util::get_opt("login_by");

        if ($loginBy === "USERNAME")
            return User::getByUsername($identifier);
        else if ($loginBy === "EMAIL")
            return User::getByMail($identifier);
        else
            return User::getByMailOrUsername($identifier);
    }
}
