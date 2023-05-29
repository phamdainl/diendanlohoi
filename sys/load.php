<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Booter;

use CODOF\Hook;
use CODOF\Util;
use Illuminate\Database\Capsule\Manager as Capsule;

define('ABSPATH', (((dirname(dirname(__FILE__))))) . '/');
define('CODO_SITE', 'default');


require 'CODOF/Booter/Load.php';
require ABSPATH . 'sites/' . CODO_SITE . '/constants.php';


if (file_exists(DATA_PATH . 'config.php')) {
    //contains valuable db information
    require DATA_PATH . 'config.php';

    if (!$CF_installed) {
        $path = getRootPath();
        $r_path = str_replace("index.php", "", $path);
        header('Location: ' . $r_path . 'install');
    }

    \Constants::defineConfigConstants($CONF);
    connectDB();
    Util::get_config(\DB::getPDO());
    \Constants::definePathConstants(getRootPath());
    require SYSPATH . 'globals/global.php';
    postBoot();
    Hook::call('after_config_loaded');
} else {
    die('Codoforum is not installed!');
}


function connectDB()
{
    require ABSPATH . 'sys/vendor/autoload.php';

    $capsule = new Capsule();
    $config = get_codo_db_conf();
    $capsule->addConnection($config);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
}

function getRootPath()
{
    if (@$_SERVER["HTTPS"] == "on" || Util::get_opt('force_https') == 'yes') {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }

    if (isset($_SERVER['SERVER_NAME']) && isset($_SERVER['SERVER_PORT'])) {
        if ($_SERVER['SERVER_PORT'] != '80') {
            $host = $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
        } else {
            $host = $_SERVER['SERVER_NAME'];
        }
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }

    return $protocol . $host . $_SERVER['SCRIPT_NAME'];
}

function postBoot()
{
    date_default_timezone_set(Util::get_opt('default_timezone'));
    Util::start_session();
    //initiate all plugins
    //Now the plugins can work on the data available
    $plg = new \CODOF\Plugin();
    $plg->init();

    $subscriber = new \CODOF\Forum\Notification\Subscriber();
    $subscriber->registerCoreTypes();
}