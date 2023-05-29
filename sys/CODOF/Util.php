<?php

/*
 * @CODOLICENSE
 */

namespace CODOF;

defined('IN_CODOF') or die();

//Assumes config.php has been included before

class Util
{

    //Not used now, since logging is done in the database
    public static $log = 'logs/file.log';
    private static $options = array();
    public static $use_normal_sessions = false;

    /**
     *
     * Logger function
     * @param string $message
     */
    public static function log($message)
    {

        if (CODO_DEBUG) {

            Log::info($message);
        }
    }

    public static function toAssociativeArray($obj) : mixed {
        return json_decode(json_encode($obj),true);
    }

    /**
     *
     * Gets all configuration information
     * @param \PDO $db -> DB connection
     * @param boolean Should the config be reloaded from database
     */
    public static function get_config($db, $reload = false)
    {

        if (!empty(self::$options) && !$reload) {
            return;
        }

        $qry = 'SELECT * FROM codo_config';
        $res = $db->query($qry);
        $conf = $res->fetchAll();

        $info = array();
        foreach ($conf as $c) {
            $info[$c['option_name']] = $c['option_value'];
        }
        self::$options = $info;
    }

    public static function get_smileys($db)
    {

        $qry = 'SELECT symbol, image_name FROM codo_smileys';
        $res = $db->query($qry);
        $smileys = $res->fetchAll();

        $_smileys = array();
        $i = 0;
        foreach ($smileys as $smiley) {

            $_smileys[$i]['image_name'] = $smiley['image_name'];
            $_smileys[$i]['symbol'] = explode("\n", $smiley['symbol']);

            $i++;
        }

        return $_smileys;
    }

    /**
     *
     * starts custom session that works on database rather than file
     * for faster access/write speed
     */
    public static function start_session()
    {

        //initiate/update/destroy user sessions
        if (!self::$use_normal_sessions) {

            new \CODOF\Session\Session();
        }

        session_start();
    }

    /**
     * Checks if the given option exists in codo_config table
     * @param string $option
     * @return bool
     */
    public static function optionExists($option)
    {
        return !empty(self::$options) && isset(self::$options[$option]);
    }
    
    public static function getOption($option,$defaultValue)
    {

        if (empty(self::$options) || !isset(self::$options[$option])) {
            return $defaultValue;
        } else {
            return self::$options[$option];
        }
    }

    public static function get_opt($option)
    {

        if (empty(self::$options) || !isset(self::$options[$option])) {
            return 'The option ' . $option . ' does not exist in the table';
        } else {
            return self::$options[$option];
        }
    }

    public static function set_opt($option, $value)
    {
        if (empty(self::$options)) {
            self::get_config(\DB::getPDO());
        }

        if (empty(self::$options) || !isset(self::$options[$option])) {
            \DB::table(PREFIX . 'codo_config')->insert(array(
                array(
                    'option_name' => $option,
                    'option_value' => $value,
                )));
        } else {
            \DB::table(PREFIX . 'codo_config')
                ->where('option_name', $option)
                ->update(['option_value' => $value]);
        }

        self::$options[$option] = $value;
    }

    public static function trim($str, $len)
    {

        //make sure trimmed string does not exceed given length
        return (strlen(trim($str)) > $len);
    }

    /**
     *
     * Checks if all $req_fields are present in $array
     * @param {array} $array
     * @param {array} $req_fields
     * @return boolean
     */
    public static function is_set($array, $req_fields)
    {

        foreach ($req_fields as $req_field) {

            if (!array_key_exists($req_field, $array)) {
                return false;
            }
        }

        return true;
    }

    public static function is_empty($array, $req_fields)
    {

        foreach ($req_fields as $req_field) {

            if (trim($array[$req_field]) == "") {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * Abbreviates a big number with k,m,b,t
     *
     * @param int $number -> input number
     * @param int $decPlaces -> precision
     * @return string -> abbreviated number
     */
    public static function abbrev_no($number, $decPlaces)
    {

        // 2 decimal places => 100, 3 => 1000, etc
        $decPlaces = pow(10, $decPlaces);

        // Enumerate number abbreviations
        $abbrev = array("k", "m", "b", "t");
        $i = 3; //size of above array
        // Go through the array backwards, so we do the largest first
        while ($i--) {

            // Convert array index to equivalent "1000", "1000000", etc
            $size = pow(10, ($i + 1) * 3);

            // If the number is bigger or equal do the abbreviation
            if ($size <= $number) {
                // Here, we multiply by decPlaces, round, and then divide by decPlaces.
                // This gives us nice rounding to a particular decimal place.

                $number = round($number * $decPlaces / $size) / $decPlaces;
                //              echo " ".$number. " ";
//                echo " ".($number/10);
                // Handle special case where we round up to the next abbreviation
                if (($number == 1000) && ($i < 3)) {
                    $number = 1;
                    $i++;
                }

                // Add the letter for the abbreviation
                //echo $number;
                $number .= $abbrev[$i];

                // We are done... stop
                break;
            }
        }

        return $number;
    }

    /**
     *
     */
    public static function inc_global_views()
    {

        $view = array('is there');
        $date = date('Y-m-d');

        if (!isset($_SESSION[UID . 'view_inserted'])) {

            //check if it exists first time;
            $view = \DB::table(PREFIX . 'codo_views')->where('date', '=', $date)->get();
        }

        if (count($view) == 0) {

            \DB::table(PREFIX . 'codo_views')->insert(array('date' => $date, 'views' => 1));
            $_SESSION[UID . 'view_inserted'] = true; //to prevent wasted queries
        } else {

            \DB::table(PREFIX . 'codo_views')->where('date', '=', $date)->increment('views');
        }
    }

    public static function re_array_files(&$file_post)
    {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query The sql query with parameter placeholders
     * @param array $params The array of substitution parameters
     * @return string The interpolated query
     */
    public static function interpolate_query($query, $params)
    {
        $keys = array();

        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }
        }

        $query = preg_replace($keys, $params, $query, 1, $count);

        //trigger_error('replaced '.$count.' keys');

        return $query;
    }

    public static function count_children($cat)
    {

        if (property_exists($cat, 'children')) {

            //find no of children upto single nest
            //we need to convert the object into array first
            //to use count()
            return count((array)$cat->children);
        }

        return 0;
    }

    /*
     *
     *
     * Shortens text by cutting middle portion and substituting the omitted
     * text by ...
     * For eg. The fox jumped over cat --> The fox ... cat
     */

    /**
     *
     * @param string $text
     * @param int $max_chars
     * @param string $mid
     * @return string
     */
    public static function mid_cut($text, $max_chars, $mid = "...")
    {
        $blocks = mb_split(" ", $text);
        $totalChars = 0;
        $formerBlocks = [];
        $latterBlocks = [];
        foreach ($blocks as $block) {
            $totalChars += mb_strlen($block);
            if ($totalChars > $max_chars) {
                break;
            } else {
                $formerBlocks[] = $block;
            }
        }

        if ($totalChars > $max_chars) {
            $totalChars = 0; //reset
            $index = count($blocks);

            while ($index) {
                $totalChars += mb_strlen($block);
                if ($totalChars > $max_chars) {
                    break;
                }
                $latterBlocks[] = $blocks[--$index];
            }

            $formerHalf = array_slice($formerBlocks, 0, count($formerBlocks) / 2);
            $latterHalf = array_slice($latterBlocks, 0, count($latterBlocks) / 2);

            return implode(" ", $formerHalf) . $mid . implode(" ", $latterHalf);
        }

        return $text;
    }

    /**
     * Shortens string by cutting the string at $max_chars after a whole word
     * @param string $text
     * @param int $max_chars
     * @return string
     */
    public static function start_cut($text, $max_chars)
    {

        if (strlen($text) > $max_chars) {

            return substr($text, 0, strrpos(substr($text, 0, $max_chars), ' ')) . "...";
        }

        return $text;
    }

    public static function get_avatar_path($name, $id = 0, $icon = true)
    {

        if ($name == null || $name == '') {

            return RURI . 'user/avatar/' . $id;
        }

        if (strpos($name, "http") !== FALSE) {

            return $name;
        }

        if ($icon) {

            return DURI . PROFILE_ICON_PATH . $name;
        }
        return DURI . PROFILE_IMG_PATH . $name;
    }

    public static function is_field_present($value, $field)
    {

        $db = \DB::getPDO();
        //no need for limit because the fields are always checked for uniqueness
        $qry = "SELECT id FROM codo_users WHERE $field=:value";
        $obj = $db->prepare($qry);
        $obj->execute(array("value" => $value));

        $res = $obj->fetch();

        if (!empty($res)) {


            return $res['id'];
        }

        return false;
    }

    /*
     * get all directories that need 0777 permissions
     */

    public static function get_777s()
    {

        return array("sites/default/assets/img/attachments",
            "sites/default/assets/img/cats",
            "sites/default/assets/img/cats/icons",
            "sites/default/assets/img/profiles",
            "sites/default/assets/img/profiles/icons",
            "sites/default/assets/img/smileys",
            "cache",
            "cache/css",
            "cache/js",
            "cache/smarty",
            "cache/smarty/cache",
            "cache/smarty/templates_c",
            "cache/HB",
            "cache/HB/compiled");
    }

    /**
     *
     * Converts an array of type
     *
     * (
     *     [0] =>
     *          ["A"] => ["hello"],
     *          ["B"] => ["hi"]
     *
     *
     * )
     */
    public static function flatten_2d_to_1d()
    {

        //flattens the array 2D to 1D
    }

    /**
     *
     * just a dummy echo function
     * @param type $message
     */
    public static function e($message)
    {

        echo $message;
    }

    public static function set_promoted_or_demoted_rid()
    {

        $user = User\User::get();
        $rids = \DB::table(PREFIX . 'codo_promotion_rules')
            ->where(function ($query) use ($user) {

                $query->where('reputation', '<=', $user->reputation)
                    ->where('type', '=', 1);
            })
            ->orWhere(function ($query) use ($user) {

                $query->where('posts', '<=', $user->no_posts)
                    ->where('type', '=', 1);
            })
            ->orWhere(function ($query) use ($user) {

                $query->where('reputation', '<=', $user->reputation)
                    ->where('posts', '<=', $user->no_posts)
                    ->where('type', '=', 0);
            })->pluck('rid');


        $current_roles = \DB::table(PREFIX . 'codo_user_roles')
            ->select('rid', 'is_promoted')
            ->where('uid', '=', $user->id)->get();

        $deletions = array();
        $additions = array();
        $current_rids = array();
        foreach ($current_roles as $role) {

            if ($role->is_promoted == '1' && !in_array($role->rid, $rids)) {

                //the promoted roles is no longer applicable
                //demote him i.e remove this role for the user
                $deletions[] = $role->rid;
            }

            $current_rids[] = $role->rid; //used in next loop
        }

        foreach ($rids as $rid) {

            if (!in_array($rid, $current_rids)) {

                //the user has a promoted role which is not added, so add it
                $additions[] = $rid;
            }
        }

        if (!empty($additions)) {

            $new_roles = array();
            foreach ($additions as $addition) {

                $new_roles[] = array(
                    'uid' => $user->id,
                    'rid' => $addition,
                    'is_primary' => 0,
                    'is_promoted' => 1
                );
            }
            \DB::table(PREFIX . 'codo_user_roles')
                ->insert($new_roles);
        }

        if (!empty($deletions)) {

            \DB::table(PREFIX . 'codo_user_roles')
                ->where('uid', '=', $user->id)
                ->whereIn('rid', $deletions)
                ->delete();
        }
    }

    /**
     * @param int $httpErrorCode
     * @param string $message
     */
    public static function halt($httpErrorCode, $message)
    {
        http_response_code($httpErrorCode);
        exit($message);
    }

    /**
     * Some security checks/sanitization
     *
     * @param [string] $name
     * @return bool
     */
    public static function isPathInBaseDir($path, $base)
    {
        $realpath = realpath($path);

        if ($realpath === false || strncmp($realpath, $base, strlen($base)) !== 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Gets names of all themes in reverse order of inheritance.
     * For Eg. customX inherits from vintage inherits from default theme
     * will return [default, vintage, customX]
     * @return array
     */
    public static function getThemesByInhereitance()
    {
        $theme = \CODOF\Util::get_opt('theme');
        $themes[] = $theme;
        do {
            require DATA_PATH . "themes/$theme/info.php";
            $theme = $info['parent_theme'];
            $themes[] = $theme;
        } while ($theme != 'default');

        return $themes;
    }

    /**
     * Checks if table exists in the database
     * @param $tableName
     * @return bool
     */
    public static function tableExists($tableName): bool
    {
        $db = \DB::getPDO();
        $databaseName = \DB::connection()->getDatabaseName();

        $stmt = $db->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=:schemaName AND TABLE_NAME=:tableName");
        $stmt->execute(array("schemaName" => $databaseName, "tableName" => $tableName));
        $tables = $stmt->fetchAll();
        return count($tables) > 0;
    }

    /**
     * Similar to array_column but for array of objects
     * @param $objects
     * @param $property
     * @return array
     */
    public static function getPropertyValuesAsArray($objects, $property): array {
        $values = [];
        foreach ($objects as $object) {
            $values[] = $object->$property;
        }
        return $values;
    }


}
