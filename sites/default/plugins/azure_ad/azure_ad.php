<?php

/*
 * @CODOLICENSE
 */


use CODOF\User\Ban;

/**
 * All files should include below defined or die line
 *
 */
defined('IN_CODOF') or die();


$uni = new \AzureAD();


dispatch_get('/azure_ad/authorize', function () use ($uni) {

    $uni->authenticate();
    header('Location: ' . CODOF\User\User::getLoginSuccessRedirectUrl());
});

dispatch_get('/azure_ad/login', function () use ($uni) {
    $user = \CODOF\User\User::get();
    if (!$user->can('view forum') && \CODOF\Util::get_opt('AZURE_CUSTOM_LOGIN') === 'yes') {
        require 'custom_login.php';
    } else {
        $uni->authenticate();
    }
});

class AzureAD
{
    public function getAzureConfig()
    {
        $tenantId = \CODOF\Util::get_opt("AZURE_TENANT_ID");

        return [
            //Location where to redirect users once they authenticate with Facebook
            //For this example we choose to come back to this same script
            'callback' => str_replace(":443", "", RURI) . "azure_ad/authorize",

            'keys' => [
                'id' => \CODOF\Util::get_opt("AZURE_CLIENT_ID"),
                'secret' => \CODOF\Util::get_opt("AZURE_CLIENT_SECRET")
            ],

            'endpoints' => [
                'authorize_url' => "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/authorize",
                'access_token_url' => "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token"
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function authenticate()
    {
        try {
            //Instantiate Azure AD's adapter directly
            $adapter = new \Hybridauth\Provider\AzureAD($this->getAzureConfig());

            //Attempt to authenticate the user with Azure AD
            $adapter->authenticate();

            //Returns a boolean of whether the user is connected with Azure AD
            $isConnected = $adapter->isConnected();

            //Retrieve the user's profile
            $userProfile = $adapter->getUserProfile();

            $oauthId = md5("AZURE_AD_" . $userProfile->identifier);
            $db = \DB::getPDO();

            $qry = 'SELECT id, username, avatar, mail FROM ' . PREFIX . 'codo_users WHERE oauth_id=:oauth_id';
            $stmt = $db->prepare($qry);
            $stmt->execute(array(":oauth_id" => $oauthId));

            $username = CODOF\Filter::clean_username($userProfile->displayName);
            $profile = $stmt->fetch();

            if (!empty($profile)) {
                if ($username != $profile['username'] || $userProfile->photoURL != $profile['avatar']) {

                    //profile has been updated remotely
                    $qry = 'UPDATE ' . PREFIX . 'codo_users SET username=:name WHERE oauth_id=:id';
                    $stmt = $db->prepare($qry);
                    $stmt->execute(array(":name" => $username, ":id" => $oauthId));
                }
                $ip = $_SERVER['REMOTE_ADDR']; //cannot be trusted at all ;)
                $ban = new Ban($db);
                if (!$ban->is_banned(array($ip, $username, $profile['mail']))) {
                    CODOF\User\User::login($profile['id']);
                }
            } else {
                //no local copy of this profile yet
                $mail = $userProfile->email;
                $create_account = true;

                if ($mail == null) {

                    $mail = '';
                } else {

                    //we got an email, lets check if it exists
                    $qry = "SELECT id FROM " . PREFIX . "codo_users WHERE mail=:mail";
                    $stmt = $db->prepare($qry);
                    $stmt->execute(array(":mail" => $mail));
                    $res = $stmt->fetch();

                    if (!empty($res)) {

                        //looks like this user has already registered
                        $create_account = false;
                        CODOF\User\User::login($res['id']);
                    }
                }

                if ($create_account) {

                    $reg = new CODOF\User\Register($db);
                    $reg->mail = $mail;
                    $reg->name = $userProfile->displayName;
                    $reg->oauth_id = $oauthId;
                    $reg->username = $username;
                    $reg->avatar = '';
                    $reg->user_status = 1; //approved user
                    $reg->register_user();
                    $reg->login();
                }
            }

        } /**
         * Catch API Requests Errors
         *
         * This usually happen when requesting a:
         *     - Wrong URI or a mal-formatted http request.
         *     - Protected resource without providing a valid access token.
         */
        catch (\Hybridauth\Exception\HttpRequestFailedException $e) {
            echo 'Raw API Response: Failed. Please make sure callback uri is set. Error Message: ' . $e->getMessage();
        } /**
         * This fellow will catch everything else
         */
        catch (\Exception $e) {
            echo 'Oops! We ran into an unknown issue: ' . $e->getMessage();
        }

        return $adapter;
    }
}


\CODOF\Hook::add('before_logout', function () use ($uni) {
    $adapter = new \Hybridauth\Provider\AzureAD($uni->getAzureConfig());
    if ($adapter->isConnected()) {
        $adapter->disconnect();
    }
});