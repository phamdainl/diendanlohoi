<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\User;

use CODOF\Service\BadgeService;
use CODOF\User\CurrentUser\DefaultUser;
use CODOF\Util;
use PDO;

class User
{

    /**
     *
     * @var PDO
     */
    private $db;

    /**
     * Has current user been loaded
     * @var bool
     */
    protected static $has_user = false;

    /**
     * Current user information
     * @var User
     */
    protected static $curr_user;

    /**
     * Current user preferences
     * @var array
     */
    protected static $preferences = false;

    /**
     * Default user preferences
     * @var array
     */
    protected static $defPreferences = false;

    /**
     * A simple cache to save all roles data
     * @var bool
     */
    private static $roles = false;

    /**
     * user information of passed userid
     * @var CurrentUser\DefaultUser
     */
    private $user = false;

    /**
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {

        $this->db = $db;
    }

    /**
     * Check if user has permission to  perform an action
     * @param string|array $permission
     * @return boolean
     */
    public function can($permission, $cid = 0, $tid = 0)
    {

        return \CODOF\Access\Access::hasPermission($permission, $this->user->id, $cid, $tid);
    }

    /**
     * Returns true only if user has permission to perform any of the actions
     * @param array $permission
     * @return boolean
     */
    public function canAny($permission, $cid = 0, $tid = 0)
    {

        return \CODOF\Access\Access::hasPermission($permission, $this->user->id, $cid, $tid);
    }

    /**
     * Returns true only if user has permission to perform all actions
     * @param array $permissions
     * @return boolean
     */
    public function canAll(array $permissions, $cid = 0, $tid = 0)
    {

        return \CODOF\Access\Access::hasAllPermissions($permissions, $this->user->id, $cid, $tid);
    }

    /**
     * increments number of posts of user
     */
    public function incPostCount()
    {

        $uid = $this->user->id;

        $qry = "UPDATE codo_users SET no_posts=no_posts+1 WHERE id=$uid";
        $this->db->query($qry);
    }

    /**
     * Decrements number of posts of user
     */
    public function decPostCount()
    {

        $uid = $this->user->id;

        $qry = "UPDATE codo_users SET no_posts=no_posts-1 WHERE id=$uid";
        $this->db->query($qry);
    }

    /**
     * Logs the user out by resetting the SESSION
     */
    public function logout()
    {

        \CODOF\Hook::call('on_user_logout');

        unset($_SESSION[UID . 'USER']);
        //session_regenerate_id(true);
        self::$has_user = false;
        \CODOF\Cookie::Delete('codo_remember');
    }

    /**
     * Increments profile view by 1 of user
     */
    public function incProfileViews()
    {

        $qry = "UPDATE " . PREFIX . "codo_users SET profile_views=profile_views+1 WHERE id=" . $this->user->id;

        $this->db->query($qry);

        $this->user->profile_views++;
    }

    /**
     *
     * Checks if the password passed, matches the password of the current user
     * @param string $password
     * @return boolean
     */
    public function checkPassword($password)
    {

        $hasher = new \CODOF\Pass(8, false);

        return $hasher->CheckPassword($password, $this->user->pass);
    }

    /**
     *
     * Updates the password of the current user
     * @param string $new_pass
     * @return boolean true if password was updated
     */
    public function updatePassword($new_pass)
    {

        $hasher = new \CODOF\Pass(8, false);
        $hash = $hasher->HashPassword($new_pass);

        //update the new hashed password
        return $this->set(array("pass" => $hash));
    }

    /**
     * Returns true if current user is logged in
     */
    public function loggedIn()
    {

        return CurrentUser\CurrentUser::loggedIn();
    }

    /**
     * Returns user information as an array
     * @return array
     */
    public function getInfo()
    {

        return (array)$this->user;
    }

    /**
     *
     * $values is associative array to update the user
     * Array{ $field => $value }
     * if $id is not passed , the current user is updated
     * @param array $values
     * @return mixed
     */
    public function set($values)
    {
        $update_arr = array();

        $id = $this->user->id;
        foreach ($values as $field => $value) {

            $update_arr[] = "$field=:$field";
        }

        $update_str = implode(",", $update_arr);

        $qry = "UPDATE " . PREFIX . "codo_users SET $update_str WHERE id=:id";

        $stmt = $this->db->prepare($qry);

        $values = array_merge($values, array("id" => $id));
        //var_dump($values);
        return $stmt->execute($values);
    }

    private function getPreference($preference)
    {

        //return preference if exists else false
        return isset(self::$preferences[$preference]) ? self::$preferences[$preference] : false;
    }

    /**
     * Gets users' preference for a setting
     * @param string $preference
     * @return string|false
     */
    public function prefers($preference)
    {

        if (self::$preferences) {

            return $this->getPreference($preference);
        }

        $qry = 'SELECT p.preference,p.value FROM ' . PREFIX . 'codo_user_preferences AS p
                   WHERE p.uid=0 AND 
                    p.preference NOT IN
                    (SELECT preference FROM ' . PREFIX . 'codo_user_preferences WHERE uid=' . $this->user->id . ')
                UNION ALL
                SELECT preference,value FROM ' . PREFIX . 'codo_user_preferences WHERE uid=' . $this->user->id;

        //for optimized performance asuming no. of preferences wont be very high
        $qry = 'SELECT preference, value, uid FROM codo_user_preferences WHERE uid=0 OR uid=' . $this->user->id . ' ORDER BY uid';

        $res = $this->db->query($qry);

        $preferences = $res->fetchAll();
        $preferencesAssoc = array();
        $defPreferences = array();

        foreach ($preferences as $_preference) {

            if ($_preference['uid'] == '0') {

                $defPreferences[$_preference['preference']] = $_preference['value'];
            }
            $preferencesAssoc[$_preference['preference']] = $_preference['value'];
        }

        self::$preferences = $preferencesAssoc;
        self::$defPreferences = $defPreferences;

        return $this->getPreference($preference);
    }

    public function updatePreferences($updates)
    {

        $qry = 'SELECT preference FROM ' . PREFIX . 'codo_user_preferences WHERE uid=' . $this->user->id;
        $res = $this->db->query($qry);

        //get a 1D user preferences
        $hasPreferences = $res->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($updates as $preference => $value) {

            $currValue = $this->prefers($preference);

            //not a hack i.e such a preference exists in table
            //is the value changed from current ?
            if ($currValue === false || $currValue == $value) {

                continue;
            }


            //is the preference present in the table ?
            if (!in_array($preference, $hasPreferences)) {

                //preference does not exist, insert
                $insertData[] = array(
                    "uid" => $this->user->id,
                    "preference" => $preference,
                    "value" => $value
                );
            } else {

                //preference exists
                //is the value updated back to default ?
                if (self::$defPreferences[$preference] == $value) {

                    //so delete the user setting
                    \DB::table(PREFIX . 'codo_user_preferences')
                        ->where('uid', $this->user->id)
                        ->where('preference', $preference)
                        ->delete();
                } else {

                    //update preference
                    \DB::table(PREFIX . 'codo_user_preferences')
                        ->where('uid', $this->user->id)
                        ->where('preference', $preference)
                        ->update(array('value' => $value));
                }
            }
        }

        if (isset($insertData) && $insertData != null) {

            //do a multi insert
            \DB::table(PREFIX . 'codo_user_preferences')->insert($insertData);
        }
    }

    /**
     * Must be called after the user is logged in
     */
    public function rememberMe()
    {

        if (isset($_GET['remember']) && $_GET['remember'] == "true") {

            $rem = new RememberMe(\DB::getPDO());
            $rem->save_cookie($this->user->username);
        }
    }

    /**
     * add role
     * @param array $role_ids
     */
    public function addRoles($role_ids)
    {

        if (!is_array($role_ids)) {
            $role_ids = array($role_ids);
        }

        foreach ($role_ids as $role_id) {


            \DB::table(PREFIX . "codo_user_roles")
                ->insert(array('uid' => $this->user->id, 'rid' => $role_id, 'is_primary' => 0));
        }
    }

    /**
     * Delete all Roles
     */
    public function deleteAllRoles()
    {

        \DB::table(PREFIX . "codo_user_roles")
            ->where('uid', '=', $this->user->id)
            ->delete();
    }

    /**
     * Ban the user
     */
    public function banAccount()
    {

        $this->deleteAllRoles();

        \DB::table(PREFIX . "codo_user_roles")
            ->insert(array('uid' => $this->user->id, 'rid' => ROLE_BANNED, 'is_primary' => 1));
    }

    /**
     * Delete the user
     */
    public function deleteAccount()
    {

        $badgeService = new BadgeService();
        \DB::table(PREFIX . 'codo_topics')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_posts')->where('uid', $this->user->id)->delete();

        \DB::table(PREFIX . "codo_user_roles")->where('uid', '=', $this->user->id)->delete();
        \DB::table(PREFIX . "codo_user_preferences")->where('uid', '=', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_unread_topics')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_unread_categories')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_notify_subscribers')->where('uid', $this->user->id)->delete();
        $badgeService->deleteAllBadgesForUser($this->user->id);
        \DB::table(PREFIX . "codo_users")->where('id', '=', $this->user->id)->delete();
    }

    /**
     * Permanent Delete all user's content
     */
    public function deleteContent()
    {

        \DB::table(PREFIX . 'codo_topics')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_posts')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_unread_topics')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_unread_categories')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_notify_subscribers')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_notify')->where('uid', $this->user->id)->delete();
    }

    public function makeContentAnonymous()
    {


        $user = User::getByMail("anonymous@localhost");

        \DB::table(PREFIX . 'codo_topics')->where('uid', $this->user->id)->update(array('uid' => $user->id));
        \DB::table(PREFIX . 'codo_posts')->where('uid', $this->user->id)->update(array('uid' => $user->id));

        //delete unwanted records
        \DB::table(PREFIX . "codo_user_roles")->where('uid', '=', $this->user->id)->delete();
        \DB::table(PREFIX . "codo_user_preferences")->where('uid', '=', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_unread_topics')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_unread_categories')->where('uid', $this->user->id)->delete();
        \DB::table(PREFIX . 'codo_notify_subscribers')->where('uid', $this->user->id)->delete();
    }

    /**
     * Gets the full sized avatar image instead of icon
     */
    public function getAvatar()
    {

        return \CODOF\Util::get_avatar_path($this->user->rawAvatar, $this->user->id, false);
    }

    /**
     * Check if passed role is assigned to current user or not
     * @param int $rid
     * @return boolean
     */
    public function hasRoleId($rid)
    {

        return in_array($rid, $this->user->rids);
    }


    public function isAdminOrModerator(): bool
    {
        return in_array(ROLE_ADMIN, $this->user->rids) || in_array(ROLE_MODERATOR, $this->user->rids);
    }

    /**
     * Checks if the user has confirmed his email or not
     * @return boolean
     */
    public function isConfirmed()
    {

        return !($this->user->user_status == 0);
    }

    /**
     * Magic method to access user information directly
     * @param string $name
     * @return string
     */
    public function __get($name)
    {

        if ($this->user) {
            if (!property_exists($this->user, $name))
                var_dump(debug_backtrace());
            //let it throw an error if property does not exists
            return $this->user->$name;
        }

        return false; //everything false for a guest
    }

    public function __set($name, $value)
    {

        if ($this->user && property_exists($this->user, $name)) {

            $this->user->$name = $value;
        }
    }

    /** ------------------- Non authentication methods follow -------------------* */

    /**
     * Gets the login url for the user
     * @return string
     */
    public static function getLoginUrl()
    {
        if (\CODOF\Plugin::is_active('sso')) {
            $permission = new \CODOF\Permission\Permission();
            $guest_can_view_forum = $permission->hasGeneralPermission(ROLE_GUEST, 'view forum');
            $unverified_user_can_view_forum = $permission->hasGeneralPermission(ROLE_UNVERIFIED, 'view forum');
            if (!($guest_can_view_forum && $unverified_user_can_view_forum)) {
                return RURI . 'sso/authorize';
            } else {
                return \CODOF\Util::get_opt('sso_login_user_path');
            }
        }

        if (\CODOF\Plugin::is_active('azure_ad')) {
            return RURI . 'azure_ad/login';
        }

        return RURI . 'user/login';
    }

    /**
     * Gets the logout url for the user
     * @return string
     */
    public static function getLogoutUrl()
    {
        if (\CODOF\Plugin::is_active('azure_ad')) {
            $tenantId = \CODOF\Util::get_opt("AZURE_TENANT_ID");
            $redirect = RURI . "user/logout";
            return "https://login.microsoftonline.com/$tenantId/oauth2/logout?post_logout_redirect_uri=$redirect";
        }

        return RURI . 'user/logout';
    }

    /**
     * Gets the register url for the user
     * @return string
     */
    public static function getRegisterUrl()
    {

        if (\CODOF\Plugin::is_active('sso')) {

            return \CODOF\Util::get_opt('sso_register_user_path');
        }

        return RURI . 'user/register';
    }

    /**
     * Gets the profile url for the user
     * @return string
     */
    public static function getProfileUrl()
    {
        return RURI . 'user/profile';
    }

    /**
     * The url to redirect to after successful login
     * @return string|string[]
     */
    public static function getLoginSuccessRedirectUrl()
    {
        if (isset($_SESSION['redirect_after_login'])) {
            // since we suffix our domain and strip_tags, there shouldn't be open redirection vulnerability
            $path = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            return RURI . 'topic/' . $path;
        } else if (Util::get_opt('user_redirect_after_login') === 'topics') {
            return RURI;
        } else {
            return self::getProfileUrl();
        }
    }

    /**
     * Gets the role name from  passed role id of user
     *
     * @param int $rid
     * @return String role name of user
     */
    public static function getRoleName($rid)
    {

        $rid = (int)$rid; //primary role id
        $roles = self::getAllRoles();

        if ($roles) {
            foreach ($roles as $role) {
                if ($role['rid'] == $rid) {
                    return $role['rname'];
                }
            }
        }

        return false;
    }

    /**
     * Gets all roles from db and saves it in a static variable as cache
     * @return bool
     */
    public static function getAllRoles()
    {
        if (!self::$roles) {
            $qry = "SELECT * FROM codo_roles";
            $db = \DB::getPDO();
            $res = $db->query($qry);

            if ($res) {
                self::$roles = $res->fetchAll();
            }
        }

        return self::$roles;
    }

    /**
     * Checks if username exists in the users table
     * @param string $username
     * @return boolean
     */
    public static function usernameExists($username)
    {

        return \CODOF\Util::is_field_present($username, 'username');
    }

    /**
     * Checks if mail exists in the users table
     * @param string $mail
     * @return boolean
     */
    public static function mailExists($mail)
    {

        return \CODOF\Util::is_field_present($mail, 'mail');
    }

    /* -------------------- User Manager Methods Follow ------------------------- */

    /**
     * Return instance of class User with user information
     * @param int $id
     * @return boolean|\CODOF\User\User
     */
    public static function get($id = 0)
    {

        return self::loadUserObject($id);
    }

    /**
     * Return instance of class User with user information by mail
     * @param string $mail
     * @return boolean|\CODOF\User\User
     */
    public static function getByMail($mail)
    {

        $qry = 'SELECT u.*,r.rid,r.is_primary FROM codo_users AS u '
            . 'LEFT JOIN codo_user_roles r ON u.id=r.uid '
            . ' WHERE mail=:mail';
        $vals = array("mail" => $mail);

        $db = \DB::getPDO();
        $user = self::getUserObject($qry, $vals, $db);

        if (!$user) {

            return false; //wrong userid passed
        }

        $obj = new User($db);
        $obj->user = $user;

        return $obj;
    }

    /**
     * Return instance of class User with user information by mail or username
     * @param string $mail
     * @param string $username
     * @return boolean|\CODOF\User\User
     */
    public static function getByMailOrUsername($mail, $username = null)
    {

        if ($username == null) $username = $mail;

        $qry = 'SELECT u.*,r.rid,r.is_primary FROM codo_users AS u '
            . 'LEFT JOIN codo_user_roles r ON u.id=r.uid '
            . 'WHERE u.mail=:mail OR u.username=:username';
        $vals = array("mail" => $mail, "username" => $username);

        $db = \DB::getPDO();
        $user = self::getUserObject($qry, $vals, $db);

        if (!$user) {

            return false; //wrong userid passed
        }

        $obj = new User($db);
        $obj->user = $user;

        return $obj;
    }

    /**
     * Return instance of class User with user information by username
     * @param string $username
     * @return boolean|\CODOF\User\User
     */
    public static function getByUsername($username)
    {

        $qry = 'SELECT u.*,r.rid,r.is_primary FROM codo_users AS u '
            . 'LEFT JOIN codo_user_roles r ON u.id=r.uid '
            . 'WHERE u.username=:username';
        $vals = array("username" => $username);

        $db = \DB::getPDO();
        $user = self::getUserObject($qry, $vals, $db);

        if (!$user) {

            return false; //wrong userid passed
        }

        $obj = new User($db);
        $obj->user = $user;

        return $obj;
    }

    /**
     * Return instance of class User with user information by userid or username
     * @param string $id
     * @param string $username
     * @return boolean|\CODOF\User\User
     */
    public static function getByIdOrUsername($id, $username)
    {

        $qry = 'SELECT u.*,r.rid,r.is_primary FROM codo_users u '
            . 'LEFT JOIN codo_user_roles r ON u.id=r.uid '
            . 'WHERE u.id=:id OR u.username=:username';

        $vals = array("id" => $id, "username" => $username);

        $db = \DB::getPDO();
        $user = self::getUserObject($qry, $vals, $db);

        if (!$user) {

            return false; //wrong userid passed
        }

        $obj = new User($db);
        $obj->user = $user;

        return $obj;
    }

    /**
     * Log the user in by userid
     * @param int $id
     */
    public static function login($id)
    {

        self::_login($id, 'id');
    }

    /**
     * Log the user in by mail
     * @param string $mail
     */
    public static function loginByMail($mail)
    {

        self::_login($mail, 'mail');
    }

    /**
     * Logs the user in by setting the SESSION and last login time in database
     * @param string $value
     * @param string $col
     * @return boolean
     */
    protected static function _login($value, $col)
    {

        $qry = "SELECT id FROM " . PREFIX . "codo_users WHERE $col=:value";

        $db = \DB::getPDO();

        $obj = $db->prepare($qry);
        $obj->execute(array('value' => $value));
        $res = $obj->fetch();

        if ($res) {

            $id = $res['id'];
            //session_regenerate_id(true);

            $_SESSION[UID . 'USER']['id'] = $id;
            self::setLoginTime($id, $db);

            return true;
        }

        return false;
    }

    /**
     *
     * Sets the last access time for the logged in user
     * @param int $id
     */
    protected static function setLoginTime($id, $db)
    {

        $time = time();
        $qry = "UPDATE " . PREFIX . "codo_users SET last_access=$time WHERE id=$id";

        $db->query($qry);
    }

    /**
     * Create and returns instance of class User by userid
     * @param int $id
     * @return boolean|User|DefaultUser
     */
    protected static function loadUserObject($id)
    {

        $loggedIn = isset($_SESSION[UID . 'USER']['id']) && $_SESSION[UID . 'USER']['id'] !== "0";
        //if id is not passed, get current userid from session
        if (!$id) {

            if ($loggedIn) {
                $id = $_SESSION[UID . 'USER']['id'];
            } else {
                return new CurrentUser\DefaultUser(); //guest
            }
        }

        if (self::$has_user && self::$curr_user->user->id == $id) {

            return self::$curr_user;
        }

        //multiple rows is better than multiple queries
        $qry = 'SELECT u.*,r.rid,r.is_primary FROM codo_users AS u '
            . 'LEFT JOIN codo_user_roles r ON u.id=r.uid '
            . 'WHERE u.id=:id';
        $vals = array("id" => $id);
        $db = \DB::getPDO();
        $user = self::getUserObject($qry, $vals, $db);

        if (!$user) {

            return false; //wrong userid passed
        }

        $userObj = new User($db);
        $userObj->user = $user;

        if (isset($_SESSION[UID . 'USER']) && $id == $_SESSION[UID . 'USER']['id']) {

            //the user object created is of current user .
            //so we can store it statically for re-use.
            self::$curr_user = $userObj; //create a static current user object for reuse
            self::$has_user = true;
        }

        return $userObj;
    }

    /**
     * Returns user info from database
     * @param string $qry
     * @param array $vals
     * @param PDO $db
     * @return object
     */
    protected static function getUserObject($qry, $vals, $db)
    {

        $obj = $db->prepare($qry);
        $obj->execute($vals);

        $userDetails = $obj->fetchAll(PDO::FETCH_OBJ);

        foreach ($userDetails as $u) {

            $rids[] = $u->rid;
            if ($u->is_primary == '1') {
                $primary_rid = $u->rid;
            }
        }
        if (isset($userDetails[0])) {
            $user = $userDetails[0];
            $user->rids = $rids;
            $user->rid = $primary_rid;
            unset($user->is_primary); //not required and is wrong
        }

        if (isset($user) && property_exists($user, 'id')) {

            $user->rawAvatar = $user->avatar;
            $user->avatar = \CODOF\Util::get_avatar_path($user->avatar, $user->id);
            return $user;
        }

        \CODOF\Util::log('Unable to fetch user data User.php:39 vals= ' . print_r($vals, true) . ' ' . print_r($_SESSION, true));
        return false;
    }

}
