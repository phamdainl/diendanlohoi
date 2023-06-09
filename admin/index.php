<?php

/*
 * @CODOLICENSE
 */

define('IN_CODOF',TRUE);
error_reporting(E_ALL);
ini_set("display_errors",1);

date_default_timezone_set('Europe/London'); 

require "adminload.php";
require "Lib.php";

$ruri = str_replace("index.php?u=/","",RURI);
define('A_RURI',$ruri . ADMIN); //http://root URI
define('A_DURI',str_replace(ADMIN ,"",DURI)); // http://~sites/
//define('DEFAULT_PATH',)//http://localhost/codoforum/sites/default/

//DATAPATH:  "/opt/lampp/htdocs/codoforum/sites/2013/12/20/xyz/"
//ABSPATH:  "/opt/lampp/htdocs/codoforum/"
\CODOF\Util::get_config(\DB::getPDO());
Constants::definePostBootConstants('');
//loads translation system
require DATA_PATH . 'locale/lang.php';

codoForumAdmin::$action["index"]="index";
codoForumAdmin::$action["categories"]="categories";
codoForumAdmin::$action["login"]="login";
codoForumAdmin::$action["users/manage"]="users/manage";
codoForumAdmin::$action["users/profile_fields"]="users/profile_fields";
codoForumAdmin::$action["config"]="config";
codoForumAdmin::$action["pages/pages"]="pages/pages";
codoForumAdmin::$action["ui/themes"]="ui/themes";
codoForumAdmin::$action["ui/blocks"]="ui/blocks";
codoForumAdmin::$action["ui/smileys"]="ui/smileys";
codoForumAdmin::$action["ui/roleColors"]="ui/roleColors";
codoForumAdmin::$action["mail/configuration"]="mail/configuration";
codoForumAdmin::$action["mail/templates"]="mail/templates";
codoForumAdmin::$action["sso"]="sso";
codoForumAdmin::$action["plugins/plugins"]="plugins/plugins";
codoForumAdmin::$action["ploader"]="ploader";
codoForumAdmin::$action["moderation/ban_user"]="moderation/ban_user";
codoForumAdmin::$action["moderation/approve_users"]="moderation/approve_users";
codoForumAdmin::$action["moderation/reports"]="moderation/reports";
codoForumAdmin::$action["permission/roles"]="permission/roles";
codoForumAdmin::$action["permission/role_edit"]="permission/role_edit";
codoForumAdmin::$action["permission/categories"]="permission/categories";
codoForumAdmin::$action["badges/settings"]="badges/settings";
codoForumAdmin::$action["reputation/settings"]="reputation/settings";
codoForumAdmin::$action["reputation/promotions"]="reputation/promotions";
codoForumAdmin::$action["spam/mldetect"]="spam/mldetect";
codoForumAdmin::$action["spam/recaptcha"]="spam/recaptcha";
codoForumAdmin::$action["manual_upgrade"]="manual_upgrade";
codoForumAdmin::$action["system/importer"]="system/importer";
codoForumAdmin::$action["system/cron"]="system/cron";
codoForumAdmin::$action["system/upgrade"]="system/upgrade";
codoForumAdmin::$action["system/massmail"]="system/massmail";
codoForumAdmin::$action["system/clear_cache"]="system/clear_cache";
codoForumAdmin::$action["system/language_settings"]="system/language_settings/language_settings";
codoForumAdmin::$action["system/edit_language"]="system/language_settings/edit_language";
codoForumAdmin::$action["system/add_language"]="system/language_settings/add_language";
codoForumAdmin::$action["system/delete_language"]="system/language_settings/delete_language";
codoForumAdmin::$action["system/default_language"]="system/language_settings/default_language";
codoForumAdmin::$action["license/index"]="license/index";
codoForumAdmin::$action["license/buy"]="license/buy";



//start 
codoForumAdmin::run();
